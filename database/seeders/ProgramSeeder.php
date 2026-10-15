<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Seed the four core programs from the approved demo design.
     */
    public function run(): void
    {
        foreach ($this->programs() as $index => $program) {
            Program::query()->updateOrCreate(
                ['slug' => $program['slug']],
                [...$program, 'sort_order' => $index + 1, 'is_published' => true],
            );
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function programs(): array
    {
        return [
            [
                'slug' => 'education',
                'icon' => 'education',
                'title' => ['en' => 'Education', 'bn' => 'শিক্ষা'],
                'summary' => ['en' => 'Schooling, early childhood and digital literacy', 'bn' => 'বিদ্যালয় শিক্ষা, শৈশবকালীন বিকাশ ও ডিজিটাল সাক্ষরতা'],
                'description' => [
                    'en' => 'Every child deserves a place to learn. We support rural students from the first classroom to the first computer.',
                    'bn' => 'প্রতিটি শিশুর শেখার সুযোগ পাওয়ার অধিকার আছে। প্রথম শ্রেণিকক্ষ থেকে প্রথম কম্পিউটার পর্যন্ত আমরা গ্রামের শিক্ষার্থীদের পাশে থাকি।',
                ],
                'focus_areas' => [
                    $this->area('Foundational Schooling', 'প্রাথমিক বিদ্যালয় শিক্ষা', 'Learning support and materials for children from underprivileged families.', 'সুবিধাবঞ্চিত পরিবারের শিশুদের জন্য পড়াশোনার সহায়তা ও শিক্ষা উপকরণ।'),
                    $this->area('Early Childhood Development', 'শৈশবকালীন বিকাশ', 'Play-based learning centres that prepare young children for school.', 'খেলার মাধ্যমে শেখার কেন্দ্র, যা ছোট শিশুদের বিদ্যালয়ের জন্য প্রস্তুত করে।'),
                    $this->area('Teacher Training Workshops', 'শিক্ষক প্রশিক্ষণ কর্মশালা', 'Practical training that helps local teachers deliver better lessons.', 'ব্যবহারিক প্রশিক্ষণ, যা স্থানীয় শিক্ষকদের আরও ভালোভাবে পাঠদানে সহায়তা করে।'),
                    $this->area('Digital Literacy', 'ডিজিটাল সাক্ষরতা', 'Basic computer skills for rural students to open future opportunities.', 'গ্রামের শিক্ষার্থীদের জন্য কম্পিউটারের মৌলিক দক্ষতা, যা ভবিষ্যতের সুযোগ খুলে দেয়।'),
                ],
            ],
            [
                'slug' => 'rehabilitation',
                'icon' => 'rehabilitation',
                'title' => ['en' => 'Rehabilitation & Support', 'bn' => 'পুনর্বাসন ও সহায়তা'],
                'summary' => ['en' => 'Special needs care and disability inclusion', 'bn' => 'বিশেষ চাহিদাসম্পন্নদের যত্ন ও প্রতিবন্ধী অন্তর্ভুক্তি'],
                'description' => [
                    'en' => 'We stand with children and families living with disability, so no one is left behind.',
                    'bn' => 'প্রতিবন্ধিতা নিয়ে বেঁচে থাকা শিশু ও পরিবারের পাশে আমরা আছি, যাতে কেউ পিছিয়ে না পড়ে।',
                ],
                'focus_areas' => [
                    $this->area('Special Needs Care', 'বিশেষ চাহিদাসম্পন্নদের যত্ন', 'Individual care and learning plans for children with special needs.', 'বিশেষ চাহিদাসম্পন্ন শিশুদের জন্য ব্যক্তিগত যত্ন ও শিক্ষা পরিকল্পনা।'),
                    $this->area('Autism Support', 'অটিজম সহায়তা', 'Guidance and structured activities for children with autism and their parents.', 'অটিজমে আক্রান্ত শিশু ও তাদের অভিভাবকদের জন্য দিকনির্দেশনা ও পরিকল্পিত কার্যক্রম।'),
                    $this->area('Early Intervention Therapies', 'প্রাথমিক থেরাপি সহায়তা', 'Therapy support that begins early, when it matters most.', 'সবচেয়ে প্রয়োজনীয় সময়ে, শুরুতেই থেরাপি সহায়তা।'),
                    $this->area('Disability Inclusion', 'প্রতিবন্ধী অন্তর্ভুক্তি', 'Community awareness so every person is welcomed and included.', 'সামাজিক সচেতনতা, যাতে প্রত্যেক মানুষ সমাদৃত ও অন্তর্ভুক্ত হয়।'),
                ],
            ],
            [
                'slug' => 'womens-empowerment',
                'icon' => 'women',
                'title' => ['en' => 'Women\'s Empowerment', 'bn' => 'নারীর ক্ষমতায়ন'],
                'summary' => ['en' => 'Crafts, training and entrepreneurship', 'bn' => 'হস্তশিল্প, প্রশিক্ষণ ও উদ্যোক্তা উন্নয়ন'],
                'description' => [
                    'en' => 'Our flagship ecosystem: rural employment generation, handicrafts and artisan incubation that give women income, confidence and a voice.',
                    'bn' => 'আমাদের প্রধান কার্যক্রম: গ্রামীণ কর্মসংস্থান, হস্তশিল্প ও কারিগর উন্নয়ন, যা নারীদের আয়, আত্মবিশ্বাস ও কণ্ঠস্বর এনে দেয়।',
                ],
                'overview' => [
                    'en' => 'Women in our project areas learn market-ready skills, form artisan groups and earn steady incomes. Four wings, each with its own training track, work together to build lasting self-reliance.',
                    'bn' => 'আমাদের প্রকল্প এলাকার নারীরা বাজার-উপযোগী দক্ষতা শেখেন, কারিগর দল গঠন করেন এবং নিয়মিত আয় করেন। নিজস্ব প্রশিক্ষণ ধারাসহ চারটি শাখা একসঙ্গে কাজ করে স্থায়ী স্বনির্ভরতা গড়ে তোলে।',
                ],
                'wings' => [
                    $this->wing('Wing A', 'Textiles & Nakshi Kantha', 'বস্ত্র ও নকশিকাঁথা', 'Traditional needlework and kantha stitch workshops that preserve Bengali craft heritage and bring steady family income.', 'ঐতিহ্যবাহী সূচিকর্ম ও কাঁথা সেলাই কর্মশালা, যা বাঙালি কারুশিল্পের ঐতিহ্য রক্ষা করে এবং পরিবারে নিয়মিত আয় আনে।'),
                    $this->wing('Wing B', 'Artisan Crafts & Basketry', 'কারুশিল্প ও ঝুড়ি তৈরি', 'Handwoven straw and polymer-braided storage baskets made by rural artisans for local and regional markets.', 'গ্রামীণ কারিগরদের হাতে বোনা খড় ও পলিমার-বেণির ঝুড়ি, যা স্থানীয় ও আঞ্চলিক বাজারে বিক্রি হয়।'),
                    $this->wing('Wing C', 'Vocational & Beauty Care', 'বৃত্তিমূলক ও বিউটি কেয়ার', 'Salon management and bridal artistry training so young women can start their own local enterprises.', 'স্যালন ব্যবস্থাপনা ও ব্রাইডাল সাজসজ্জা প্রশিক্ষণ, যাতে তরুণীরা নিজেদের স্থানীয় উদ্যোগ শুরু করতে পারেন।'),
                    $this->wing('Wing D', 'Grassroots Mobilization', 'তৃণমূল সংগঠন', 'Village courtyard meetings and leadership circles that build community-led decision making.', 'গ্রামের উঠান বৈঠক ও নেতৃত্ব চক্র, যা সমাজভিত্তিক সিদ্ধান্ত গ্রহণের সক্ষমতা তৈরি করে।'),
                ],
            ],
            [
                'slug' => 'agriculture',
                'icon' => 'agriculture',
                'title' => ['en' => 'Agriculture & Social Development', 'bn' => 'কৃষি ও সামাজিক উন্নয়ন'],
                'summary' => ['en' => 'Tree plantation and environment', 'bn' => 'বৃক্ষরোপণ ও পরিবেশ'],
                'description' => [
                    'en' => 'Healthy communities need a healthy environment. We plant, clean and educate together with local people.',
                    'bn' => 'সুস্থ সমাজের জন্য প্রয়োজন সুস্থ পরিবেশ। স্থানীয় মানুষের সঙ্গে মিলে আমরা গাছ লাগাই, পরিচ্ছন্নতা রক্ষা করি এবং সচেতনতা ছড়াই।',
                ],
                'focus_areas' => [
                    $this->area('Tree Plantation Drives', 'বৃক্ষরোপণ কর্মসূচি', 'Community plantation campaigns that green villages and roadsides.', 'সামাজিক বৃক্ষরোপণ অভিযান, যা গ্রাম ও রাস্তার ধার সবুজ করে তোলে।'),
                    $this->area('Environmental Awareness', 'পরিবেশ সচেতনতা', 'Sessions that help families protect land, water and air.', 'মাটি, পানি ও বাতাস রক্ষায় পরিবারগুলোকে সহায়তা করার সচেতনতামূলক সেশন।'),
                    $this->area('Clean Area Maintenance', 'পরিচ্ছন্ন এলাকা রক্ষণাবেক্ষণ', 'Volunteer-led clean-up activities that keep public spaces healthy.', 'স্বেচ্ছাসেবকদের নেতৃত্বে পরিচ্ছন্নতা কার্যক্রম, যা জনসাধারণের স্থান স্বাস্থ্যকর রাখে।'),
                ],
            ],
        ];
    }

    /**
     * @return array{title: array{en: string, bn: string}, text: array{en: string, bn: string}}
     */
    private function area(string $titleEn, string $titleBn, string $textEn, string $textBn): array
    {
        return [
            'title' => ['en' => $titleEn, 'bn' => $titleBn],
            'text' => ['en' => $textEn, 'bn' => $textBn],
        ];
    }

    /**
     * @return array{label: string, title: array{en: string, bn: string}, text: array{en: string, bn: string}, images: list<string>}
     */
    private function wing(string $label, string $titleEn, string $titleBn, string $textEn, string $textBn): array
    {
        return [
            'label' => $label,
            'title' => ['en' => $titleEn, 'bn' => $titleBn],
            'text' => ['en' => $textEn, 'bn' => $textBn],
            'images' => [],
        ];
    }
}
