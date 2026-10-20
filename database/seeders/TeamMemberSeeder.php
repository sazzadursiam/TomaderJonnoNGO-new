<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Seed the leadership roles from the demo design; names are added later from the admin panel.
     */
    public function run(): void
    {
        $roles = [
            ['Chairperson', 'চেয়ারপারসন', 'Strategic guidance and oversight', 'কৌশলগত দিকনির্দেশনা ও তত্ত্বাবধান'],
            ['Executive Director', 'নির্বাহী পরিচালক', 'Day-to-day leadership of programs', 'কার্যক্রমের দৈনন্দিন নেতৃত্ব'],
            ['Program Manager', 'প্রোগ্রাম ম্যানেজার', 'Field delivery across all four programs', 'চারটি কার্যক্রমের মাঠপর্যায়ের বাস্তবায়ন'],
            ['Finance & Admin Officer', 'অর্থ ও প্রশাসনিক কর্মকর্তা', 'Accountability and operations', 'জবাবদিহি ও পরিচালনা'],
        ];

        foreach ($roles as $index => [$roleEn, $roleBn, $bioEn, $bioBn]) {
            TeamMember::query()->updateOrCreate(
                ['sort_order' => $index + 1],
                [
                    'name' => ['en' => null, 'bn' => null],
                    'role' => ['en' => $roleEn, 'bn' => $roleBn],
                    'bio' => ['en' => $bioEn, 'bn' => $bioBn],
                    'is_published' => true,
                ],
            );
        }
    }
}
