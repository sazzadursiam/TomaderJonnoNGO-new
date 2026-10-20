<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Setting;
use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Places the organisation's real photos (supplied under public/images/<Program name>/) across the site.
 *
 * Photos are copied (resized to a web-friendly width) onto the public disk so they behave exactly like
 * admin uploads. Only empty fields or generated demo placeholders are replaced — photos chosen in the
 * admin panel are never overwritten. Safe to run in every environment.
 */
class SitePhotoSeeder extends Seeder
{
    private const string SOURCE_DIRECTORY = 'images';

    private const string TARGET_DIRECTORY = 'photos';

    private const int MAX_WIDTH = 1600;

    private const string EDUCATION = 'Education';

    private const string REHABILITATION = 'Rehabilitation & Support';

    private const string WOMEN = 'Women\'s Empowerment';

    private const string AGRICULTURE = 'Agriculture & Social Development';

    public function run(): void
    {
        $this->placeProgramPhotos();
        $this->placeStoryPhotos();
        $this->placeAboutPhoto();
    }

    private function placeProgramPhotos(): void
    {
        $programs = [
            'education' => ['cover' => [self::EDUCATION, 6], 'gallery' => [self::EDUCATION, [2, 7, 1, 4, 3, 5]]],
            'rehabilitation' => ['cover' => [self::REHABILITATION, 1], 'gallery' => [self::REHABILITATION, [2, 3, 1]]],
            'womens-empowerment' => ['cover' => [self::WOMEN, 15]],
            'agriculture' => ['cover' => [self::AGRICULTURE, 3], 'gallery' => [self::AGRICULTURE, [1, 2, 3]]],
        ];

        $womenWings = [
            [1, 19, 8, 27, 28, 20, 21, 23],
            [11, 12, 10, 17, 2, 4, 13, 14],
            [16, 24],
            [22, 18, 15],
        ];

        foreach ($programs as $slug => $photos) {
            $program = Program::query()->where('slug', $slug)->first();

            if ($program === null) {
                continue;
            }

            $updates = [];

            if ($this->isReplaceable($program->image)) {
                $updates['image'] = $this->copy(...$photos['cover']);
            }

            if (isset($photos['gallery']) && $this->isReplaceableList($program->gallery)) {
                [$folder, $numbers] = $photos['gallery'];
                $updates['gallery'] = $this->copyMany($folder, $numbers);
            }

            if ($slug === 'womens-empowerment' && filled($program->wings)) {
                $wings = $program->wings;

                foreach ($wings as $index => $wing) {
                    if (isset($womenWings[$index]) && $this->isReplaceableList($wing['images'] ?? [])) {
                        $wings[$index]['images'] = $this->copyMany(self::WOMEN, $womenWings[$index]);
                    }
                }

                $updates['wings'] = $wings;
            }

            $updates = array_filter($updates, fn (mixed $value): bool => filled($value));

            if ($updates !== []) {
                $program->update($updates);
            }
        }
    }

    private function placeStoryPhotos(): void
    {
        $stories = [
            'from-skill-training-to-self-reliance' => [self::WOMEN, 1],
            'a-salon-of-her-own' => [self::WOMEN, 16],
            'baskets-to-markets' => [self::WOMEN, 11],
            'digital-skills-in-the-village-classroom' => [self::EDUCATION, 7],
            'early-support-for-every-child' => [self::REHABILITATION, 1],
            'greener-roadsides-together' => [self::AGRICULTURE, 1],
        ];

        foreach ($stories as $slug => [$folder, $number]) {
            $story = Story::query()->where('slug', $slug)->first();

            if ($story !== null && $this->isReplaceable($story->image) && ($path = $this->copy($folder, $number)) !== null) {
                $story->update(['image' => $path]);
            }
        }
    }

    private function placeAboutPhoto(): void
    {
        if ($this->isReplaceable(Setting::get('about_image')) && ($path = $this->copy(self::WOMEN, 22)) !== null) {
            Setting::store(['about_image' => $path]);
        }
    }

    private function isReplaceable(?string $path): bool
    {
        return blank($path) || Str::startsWith($path, DemoContentSeeder::DEMO_DIRECTORY.'/');
    }

    /**
     * @param  array<int, string|null>|null  $paths
     */
    private function isReplaceableList(?array $paths): bool
    {
        $paths = array_filter($paths ?? []);

        return $paths === [] || collect($paths)->every(fn (string $path): bool => $this->isReplaceable($path));
    }

    /**
     * @param  list<int>  $numbers
     * @return list<string>
     */
    private function copyMany(string $folder, array $numbers): array
    {
        return collect($numbers)
            ->map(fn (int $number): ?string => $this->copy($folder, $number))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Copy public/images/<folder>/<number>.jpeg onto the public disk (resized), returning the stored path.
     */
    private function copy(string $folder, int $number): ?string
    {
        $source = public_path(self::SOURCE_DIRECTORY.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR."{$number}.jpeg");

        if (! is_file($source)) {
            return null;
        }

        $target = self::TARGET_DIRECTORY.'/'.Str::slug($folder)."/{$number}.jpg";
        $disk = Storage::disk('public');

        if (! $disk->exists($target)) {
            $disk->put($target, $this->resized($source));
        }

        return $target;
    }

    /**
     * JPEG bytes of the image scaled down to MAX_WIDTH (never up), honouring EXIF orientation.
     */
    private function resized(string $source): string
    {
        $image = @imagecreatefromjpeg($source);

        if ($image === false) {
            return (string) file_get_contents($source);
        }

        $orientation = function_exists('exif_read_data') ? (@exif_read_data($source)['Orientation'] ?? 1) : 1;
        $image = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };

        if (imagesx($image) > self::MAX_WIDTH) {
            $image = imagescale($image, self::MAX_WIDTH);
        }

        ob_start();
        imagejpeg($image, null, 82);
        imagedestroy($image);

        return (string) ob_get_clean();
    }
}
