<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Story;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Fills every empty field with clearly-dummy content so the full design can be reviewed locally.
 *
 * Only empty values are filled, so anything entered from the admin panel is never overwritten.
 * Runs from DatabaseSeeder in the local environment only — never ship this data to production.
 */
class DemoContentSeeder extends Seeder
{
    public const string DEMO_DIRECTORY = 'demo';

    /**
     * Background gradients for generated photos: [from, to] RGB triples.
     *
     * @var list<array{array{int, int, int}, array{int, int, int}}>
     */
    private const array PALETTES = [
        [[27, 77, 62], [16, 185, 129]],
        [[20, 56, 45], [52, 211, 153]],
        [[211, 47, 47], [251, 146, 60]],
        [[30, 64, 175], [56, 189, 248]],
        [[124, 58, 237], [236, 72, 153]],
        [[180, 83, 9], [250, 204, 21]],
    ];

    public function run(): void
    {
        $this->fillSettings();
        $this->fillPrograms();
        $this->fillStories();
        $this->fillTeam();
        $this->fillInbox();
    }

    private function fillSettings(): void
    {
        $defaults = [
            'address' => [
                'en' => 'House 12, Road 5, Block C, Mirpur 10, Dhaka 1216, Bangladesh (sample address)',
                'bn' => 'বাড়ি ১২, রোড ৫, ব্লক সি, মিরপুর ১০, ঢাকা ১২১৬, বাংলাদেশ (নমুনা ঠিকানা)',
            ],
            'about_image' => fn () => $this->image('about/community.jpg', 'Community meeting', 0, 1200, 800),
            'facebook_url' => 'https://www.facebook.com/',
            'youtube_url' => 'https://www.youtube.com/',
            'linkedin_url' => 'https://www.linkedin.com/',
            'x_url' => 'https://x.com/',
            'donation_bkash' => '01700-000000 (sample)',
            'donation_nagad' => '01800-000000 (sample)',
            'donation_bank_name' => 'Sample Bank PLC',
            'donation_account_name' => 'Tomader Jonno',
            'donation_account_number' => '0000-0000-0000-0 (sample)',
            'donation_branch' => 'Mirpur Branch, Dhaka',
            'donation_routing_number' => '000000000',
        ];

        foreach ($defaults as $key => $value) {
            $current = Setting::get($key);

            if (blank(is_array($current) ? array_filter($current) : $current)) {
                Setting::store([$key => value($value)]);
            }
        }
    }

    private function fillPrograms(): void
    {
        $wingImageCounts = [4, 2, 1, 3];

        Program::query()->get()->each(function (Program $program, int $index) use ($wingImageCounts): void {
            $title = $program->translate('title', 'en');
            $updates = [];

            if (blank($program->image)) {
                $updates['image'] = $this->image("programs/{$program->slug}.jpg", $title, $index, 1200, 800);
            }

            $wings = $program->wings ?? [];

            foreach ($wings as $wingIndex => $wing) {
                if (filled(array_filter($wing['images'] ?? []))) {
                    continue;
                }

                $wingTitle = $wing['title']['en'] ?? 'Wing';
                $count = $wingImageCounts[$wingIndex] ?? 2;

                $wings[$wingIndex]['images'] = collect(range(1, $count))
                    ->map(fn (int $number) => $this->image(
                        'programs/wings/'.Str::slug($wingTitle)."-{$number}.jpg",
                        "{$wingTitle} {$number}",
                        $wingIndex + $number,
                        900,
                        700,
                    ))
                    ->all();

                $updates['wings'] = $wings;
            }

            if ($updates !== []) {
                $program->update($updates);
            }
        });
    }

    private function fillStories(): void
    {
        $bodies = $this->storyBodies();

        Story::query()->get()->each(function (Story $story, int $index) use ($bodies): void {
            $updates = [];

            if (blank($story->image)) {
                $updates['image'] = $this->image("stories/{$story->slug}.jpg", $story->translate('title', 'en'), $index, 1200, 800);
            }

            if (blank($story->translate('body', 'en')) && isset($bodies[$story->slug])) {
                $updates['body'] = $bodies[$story->slug];
            }

            if ($updates !== []) {
                $story->update($updates);
            }
        });
    }

    private function fillTeam(): void
    {
        $names = [
            ['Ayesha Rahman', 'আয়েশা রহমান'],
            ['Mahmudul Hasan', 'মাহমুদুল হাসান'],
            ['Farhana Akter', 'ফারহানা আক্তার'],
            ['Tanvir Ahmed', 'তানভীর আহমেদ'],
        ];

        TeamMember::query()->orderBy('sort_order')->get()->each(function (TeamMember $member, int $index) use ($names): void {
            $updates = [];
            [$nameEn, $nameBn] = $names[$index] ?? ['Team Member '.($index + 1), 'দলের সদস্য '.($index + 1)];

            if (blank($member->translate('name', 'en'))) {
                $updates['name'] = ['en' => "{$nameEn} (sample)", 'bn' => "{$nameBn} (নমুনা)"];
            }

            if (blank($member->photo)) {
                $initials = collect(explode(' ', $nameEn))->map(fn (string $part) => $part[0])->implode('');
                $updates['photo'] = $this->image("team/member-{$member->id}.jpg", $initials, $index, 400, 400, large: true);
            }

            if ($updates !== []) {
                $member->update($updates);
            }
        });
    }

    private function fillInbox(): void
    {
        if (ContactMessage::query()->exists()) {
            return;
        }

        $messages = [
            ['Shirin Sultana', 'shirin@example.com', 'I would like to volunteer as a tutor on weekends. How can I join?', 'en', null],
            ['রফিকুল ইসলাম', '01711-000000', 'আমাদের গ্রামে একটি বৃক্ষরোপণ কর্মসূচি করতে চাই। আপনাদের সহযোগিতা পাওয়া যাবে কি?', 'bn', now()],
            ['David Miller', 'david@example.org', 'Our company would like to sponsor the digital literacy sessions. Please share a proposal.', 'en', null],
        ];

        foreach ($messages as $index => [$name, $contact, $message, $locale, $readAt]) {
            ContactMessage::factory()->create([
                'name' => $name,
                'contact' => $contact,
                'message' => $message,
                'locale' => $locale,
                'read_at' => $readAt,
                'created_at' => now()->subDays($index * 2),
            ]);
        }
    }

    /**
     * Generate a gradient placeholder photo on the public disk (once) and return its stored path.
     */
    private function image(string $path, string $label, int $paletteIndex, int $width, int $height, bool $large = false): string
    {
        $path = self::DEMO_DIRECTORY.'/'.$path;
        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            return $path;
        }

        [$from, $to] = self::PALETTES[$paletteIndex % count(self::PALETTES)];
        $canvas = imagecreatetruecolor($width, $height);

        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / max($height - 1, 1);
            $color = imagecolorallocate(
                $canvas,
                (int) ($from[0] + ($to[0] - $from[0]) * $ratio),
                (int) ($from[1] + ($to[1] - $from[1]) * $ratio),
                (int) ($from[2] + ($to[2] - $from[2]) * $ratio),
            );
            imageline($canvas, 0, $y, $width, $y, $color);
        }

        $soft = imagecolorallocatealpha($canvas, 255, 255, 255, 105);
        imagefilledellipse($canvas, (int) ($width * 0.82), (int) ($height * 0.22), (int) ($width * 0.5), (int) ($width * 0.5), $soft);
        imagefilledellipse($canvas, (int) ($width * 0.12), (int) ($height * 0.9), (int) ($width * 0.35), (int) ($width * 0.35), $soft);

        $font = $this->fontPath();
        $white = imagecolorallocate($canvas, 255, 255, 255);
        $faint = imagecolorallocatealpha($canvas, 255, 255, 255, 50);

        if ($font !== null) {
            $size = $large ? (int) ($width / 5) : (int) min($width / 22, 40);
            $text = Str::limit($label, 40);
            $box = imagettfbbox($size, 0, $font, $text);
            $textX = (int) (($width - ($box[2] - $box[0])) / 2);
            $textY = (int) (($height + ($large ? $size : $size / 2)) / 2);
            imagettftext($canvas, $size, 0, $textX, $textY, $white, $font, $text);

            if (! $large) {
                imagettftext($canvas, (int) ($size * 0.5), 0, 24, $height - 24, $faint, $font, 'Sample photo · Tomader Jonno');
            }
        } else {
            imagestring($canvas, 5, 20, (int) ($height / 2), $label, $white);
        }

        ob_start();
        imagejpeg($canvas, null, 82);
        $disk->put($path, (string) ob_get_clean());
        imagedestroy($canvas);

        return $path;
    }

    private function fontPath(): ?string
    {
        $candidates = [
            'C:\\Windows\\Fonts\\arialbd.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/System/Library/Fonts/Supplemental/Arial Bold.ttf',
        ];

        return collect($candidates)->first(fn (string $candidate) => is_file($candidate));
    }

    /**
     * Two-paragraph sample bodies for the seeded stories, keyed by slug.
     *
     * @return array<string, array{en: string, bn: string}>
     */
    private function storyBodies(): array
    {
        return [
            'from-skill-training-to-self-reliance' => [
                'en' => "Rahima joined our three-month tailoring course with no sewing experience and two children in primary school. By the end of the course she could cut patterns, stitch blouses and repair garments for her neighbours.\n\nToday she runs a small stitching unit from home. The steady income covers her children's school fees, and she now trains two younger women from her village.",
                'bn' => "রহিমা কোনো সেলাইয়ের অভিজ্ঞতা ছাড়াই আমাদের তিন মাসের দর্জি প্রশিক্ষণে যোগ দেন। তখন তাঁর দুই সন্তান প্রাথমিক বিদ্যালয়ে পড়ে। কোর্স শেষে তিনি নকশা কাটা, ব্লাউজ সেলাই ও প্রতিবেশীদের কাপড় মেরামত করতে শিখে যান।\n\nআজ তিনি বাড়িতে একটি ছোট সেলাই ইউনিট চালান। নিয়মিত আয়ে সন্তানদের স্কুলের বেতন দেন, আর এখন গ্রামের দুজন তরুণীকেও প্রশিক্ষণ দিচ্ছেন।",
            ],
            'a-salon-of-her-own' => [
                'en' => "Nasrin always loved bridal makeup but had no way to learn it professionally. Through our vocational and beauty care wing she completed a salon management and bridal artistry course.\n\nWith a small start-up kit she opened a salon in her village market. It now employs two other young women and is booked for most wedding seasons.",
                'bn' => "ব্রাইডাল সাজসজ্জা নাসরিনের সবসময়ই পছন্দের ছিল, কিন্তু পেশাদারভাবে শেখার সুযোগ ছিল না। আমাদের বৃত্তিমূলক ও বিউটি কেয়ার শাখার মাধ্যমে তিনি স্যালন ব্যবস্থাপনা ও ব্রাইডাল সাজসজ্জার কোর্স সম্পন্ন করেন।\n\nছোট একটি শুরুর কিট নিয়ে তিনি গ্রামের বাজারে একটি স্যালন খোলেন। এখন সেখানে আরও দুজন তরুণী কাজ করেন, আর বিয়ের মৌসুমে প্রায় সব দিনই বুকিং থাকে।",
            ],
            'baskets-to-markets' => [
                'en' => "A group of twelve artisans in our basketry wing used to sell their work to middlemen at very low prices. We helped them improve finishing, agree on fair prices and take part in regional fairs.\n\nThe group now sells directly to buyers and shops, and members report a clear rise in monthly income.",
                'bn' => "আমাদের ঝুড়ি তৈরি শাখার বারোজন কারিগরের একটি দল আগে খুব কম দামে মধ্যস্বত্বভোগীদের কাছে পণ্য বিক্রি করত। আমরা তাঁদের পণ্যের মান উন্নত করতে, ন্যায্য দাম ঠিক করতে এবং আঞ্চলিক মেলায় অংশ নিতে সাহায্য করেছি।\n\nদলটি এখন সরাসরি ক্রেতা ও দোকানে বিক্রি করে, আর সদস্যদের মাসিক আয় স্পষ্টভাবে বেড়েছে।",
            ],
            'digital-skills-in-the-village-classroom' => [
                'en' => "Most students at the village school had never used a computer. Our digital literacy sessions brought laptops and a trained instructor to the classroom twice a week.\n\nStudents learned typing, safe internet use and basic documents. Several now help their families fill in online forms and check exam results.",
                'bn' => "গ্রামের স্কুলের বেশিরভাগ শিক্ষার্থী আগে কখনো কম্পিউটার ব্যবহার করেনি। আমাদের ডিজিটাল সাক্ষরতা সেশনে সপ্তাহে দুদিন ল্যাপটপ ও একজন প্রশিক্ষিত শিক্ষক শ্রেণিকক্ষে আসেন।\n\nশিক্ষার্থীরা টাইপিং, নিরাপদ ইন্টারনেট ব্যবহার ও সাধারণ ডকুমেন্ট তৈরি শিখেছে। অনেকে এখন পরিবারের অনলাইন ফর্ম পূরণ ও পরীক্ষার ফল দেখতে সাহায্য করে।",
            ],
            'early-support-for-every-child' => [
                'en' => "When a young family noticed their son was not speaking at the expected age, they did not know where to turn. Our rehabilitation team connected them with early intervention therapy.\n\nWith weekly sessions and guidance for the parents, the child is making steady progress and now attends a local play-based learning centre.",
                'bn' => "একটি তরুণ পরিবার যখন খেয়াল করল তাদের ছেলে বয়স অনুযায়ী কথা বলছে না, তখন তারা কোথায় যাবে বুঝতে পারছিল না। আমাদের পুনর্বাসন দল তাদের শুরুতেই থেরাপি সহায়তার সঙ্গে যুক্ত করে।\n\nসাপ্তাহিক সেশন ও অভিভাবকদের দিকনির্দেশনায় শিশুটি নিয়মিত উন্নতি করছে, আর এখন একটি স্থানীয় খেলাভিত্তিক শিক্ষাকেন্দ্রে যাচ্ছে।",
            ],
            'greener-roadsides-together' => [
                'en' => "During the monsoon plantation drive, more than sixty volunteers planted saplings along village roads and around two schools.\n\nLocal families agreed to water and protect the young trees, and the same volunteers now organise a monthly clean-up of the market area.",
                'bn' => "বর্ষার বৃক্ষরোপণ কর্মসূচিতে ষাটজনেরও বেশি স্বেচ্ছাসেবক গ্রামের রাস্তার ধারে ও দুটি স্কুলের চারপাশে চারা রোপণ করেছেন।\n\nস্থানীয় পরিবারগুলো চারাগাছে পানি দেওয়া ও রক্ষা করার দায়িত্ব নিয়েছে, আর একই স্বেচ্ছাসেবকেরা এখন প্রতি মাসে বাজার এলাকা পরিষ্কারের আয়োজন করেন।",
            ],
        ];
    }
}
