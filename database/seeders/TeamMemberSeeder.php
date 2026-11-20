<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Seeds the management team with their photos (supplied as public/images/Management Team/<English name>.png).
 *
 * Members are matched by English name, so names, roles and photos edited in the admin panel are kept.
 * Unnamed or sample placeholder members from earlier seeds are removed.
 */
class TeamMemberSeeder extends Seeder
{
    private const string SOURCE_DIRECTORY = 'images/Management Team';

    private const string TARGET_DIRECTORY = 'photos/management-team';

    private const int PHOTO_SIZE = 480;

    public function run(): void
    {
        $members = [
            ['Md. Forid Ahmed', 'মো. ফরিদ আহমেদ', 'President', 'সভাপতি'],
            ['Md. Hamidul Haque', 'মো. হামিদুল হক', 'Secretary General', 'সাধারণ সম্পাদক'],
            ['Barrister H M Sanzid Siddiqi', 'ব্যারিস্টার এইচ এম সানজিদ সিদ্দিকী', 'Executive Member', 'নির্বাহী সদস্য'],
            ['Khandoker Fuyad Oman', 'খন্দকার ফুয়াদ ওমান', 'Member', 'সদস্য'],
            ['Alve Shabab Bin Sharif', 'আলভে শাবাব বিন শরীফ', 'Member', 'সদস্য'],
        ];

        $this->removePlaceholders(array_column($members, 0));

        foreach ($members as $index => [$nameEn, $nameBn, $roleEn, $roleBn]) {
            $member = TeamMember::query()->where('name->en', $nameEn)->first()
                ?? TeamMember::query()->create([
                    'name' => ['en' => $nameEn, 'bn' => $nameBn],
                    'role' => ['en' => $roleEn, 'bn' => $roleBn],
                    'bio' => ['en' => null, 'bn' => null],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]);

            if ($this->isReplaceable($member->photo) && ($photo = $this->copyPhoto($nameEn)) !== null) {
                $member->update(['photo' => $photo]);
            }
        }
    }

    /**
     * Delete unnamed or "(sample)" members that are not part of the real team.
     *
     * @param  list<string>  $realNames
     */
    private function removePlaceholders(array $realNames): void
    {
        TeamMember::query()->get()
            ->filter(function (TeamMember $member) use ($realNames): bool {
                $name = $member->translate('name', 'en');

                return ! in_array($name, $realNames, true) && (blank($name) || Str::endsWith($name, '(sample)'));
            })
            ->each(fn (TeamMember $member) => $member->delete());
    }

    private function isReplaceable(?string $path): bool
    {
        return blank($path) || Str::startsWith($path, DemoContentSeeder::DEMO_DIRECTORY.'/');
    }

    /**
     * Copy the member's portrait onto the public disk as a square JPEG cropped from the top, returning its path.
     */
    private function copyPhoto(string $name): ?string
    {
        $source = public_path(self::SOURCE_DIRECTORY.DIRECTORY_SEPARATOR."{$name}.png");

        if (! is_file($source) || ($image = @imagecreatefrompng($source)) === false) {
            return null;
        }

        $side = min(imagesx($image), imagesy($image));
        $square = imagecreatetruecolor(self::PHOTO_SIZE, self::PHOTO_SIZE);
        imagefill($square, 0, 0, imagecolorallocate($square, 255, 255, 255));
        imagecopyresampled($square, $image, 0, 0, (int) ((imagesx($image) - $side) / 2), 0, self::PHOTO_SIZE, self::PHOTO_SIZE, $side, $side);

        ob_start();
        imagejpeg($square, null, 85);
        imagedestroy($image);
        imagedestroy($square);

        $target = self::TARGET_DIRECTORY.'/'.Str::slug($name).'.jpg';
        Storage::disk('public')->put($target, (string) ob_get_clean());

        return $target;
    }
}
