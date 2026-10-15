<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    /**
     * Seed the sample impact stories from the approved demo design.
     */
    public function run(): void
    {
        $stories = [
            [
                'slug' => 'from-skill-training-to-self-reliance',
                'category' => ['en' => 'Women', 'bn' => 'নারী'],
                'title' => ['en' => 'From Skill Training to Self-Reliance: A Mother\'s Journey', 'bn' => 'দক্ষতা প্রশিক্ষণ থেকে স্বনির্ভরতা: এক মায়ের পথচলা'],
                'excerpt' => [
                    'en' => 'After tailoring training, Rahima runs a home stitching unit that pays her children\'s school fees.',
                    'bn' => 'দর্জি প্রশিক্ষণের পর রহিমা এখন বাড়িতে সেলাইয়ের কাজ চালান, যার আয়ে তার সন্তানদের স্কুলের বেতন দেওয়া হয়।',
                ],
            ],
            [
                'slug' => 'a-salon-of-her-own',
                'category' => ['en' => 'Women', 'bn' => 'নারী'],
                'title' => ['en' => 'A Salon of Her Own', 'bn' => 'নিজের একটি স্যালন'],
                'excerpt' => [
                    'en' => 'Nasrin trained in bridal artistry and opened a village salon that employs two other young women.',
                    'bn' => 'নাসরিন ব্রাইডাল সাজসজ্জার প্রশিক্ষণ নিয়ে গ্রামে একটি স্যালন খুলেছেন, যেখানে আরও দুজন তরুণী কাজ করেন।',
                ],
            ],
            [
                'slug' => 'baskets-to-markets',
                'category' => ['en' => 'Artisans', 'bn' => 'কারিগর'],
                'title' => ['en' => 'Baskets to Markets', 'bn' => 'ঝুড়ি থেকে বাজারে'],
                'excerpt' => [
                    'en' => 'An artisan group now sells handwoven baskets at regional fairs and has raised members\' incomes.',
                    'bn' => 'একটি কারিগর দল এখন আঞ্চলিক মেলায় হাতে বোনা ঝুড়ি বিক্রি করে, এতে সদস্যদের আয় বেড়েছে।',
                ],
            ],
            [
                'slug' => 'digital-skills-in-the-village-classroom',
                'category' => ['en' => 'Education', 'bn' => 'শিক্ষা'],
                'title' => ['en' => 'Digital Skills in the Village Classroom', 'bn' => 'গ্রামের শ্রেণিকক্ষে ডিজিটাল দক্ষতা'],
                'excerpt' => [
                    'en' => 'Rural students gained their first hands-on computer lessons through our digital literacy sessions.',
                    'bn' => 'আমাদের ডিজিটাল সাক্ষরতা সেশনের মাধ্যমে গ্রামের শিক্ষার্থীরা প্রথমবার হাতে-কলমে কম্পিউটার শিখেছে।',
                ],
            ],
            [
                'slug' => 'early-support-for-every-child',
                'category' => ['en' => 'Rehabilitation', 'bn' => 'পুনর্বাসন'],
                'title' => ['en' => 'Early Support for Every Child', 'bn' => 'প্রতিটি শিশুর জন্য শুরুতেই সহায়তা'],
                'excerpt' => [
                    'en' => 'A family found early intervention therapy and a welcoming community for their child.',
                    'bn' => 'একটি পরিবার তাদের শিশুর জন্য শুরুতেই থেরাপি সহায়তা ও আন্তরিক একটি সমাজ খুঁজে পেয়েছে।',
                ],
            ],
            [
                'slug' => 'greener-roadsides-together',
                'category' => ['en' => 'Community', 'bn' => 'সমাজ'],
                'title' => ['en' => 'Greener Roadsides, Together', 'bn' => 'একসঙ্গে সবুজ রাস্তার ধার'],
                'excerpt' => [
                    'en' => 'Volunteers planted saplings and cleaned local spaces during a seasonal plantation drive.',
                    'bn' => 'মৌসুমি বৃক্ষরোপণ কর্মসূচিতে স্বেচ্ছাসেবকেরা চারা রোপণ করেছেন এবং স্থানীয় এলাকা পরিচ্ছন্ন করেছেন।',
                ],
            ],
        ];

        foreach ($stories as $index => $story) {
            Story::query()->updateOrCreate(
                ['slug' => $story['slug']],
                [...$story, 'is_published' => true, 'published_at' => now()->subDays($index)],
            );
        }
    }
}
