<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the site contact details from the demo design. Donation details are left for the admin to fill in.
     */
    public function run(): void
    {
        $defaults = [
            'phone' => '+8801922232794',
            'email' => 'info@tomaderjonno.org',
            'address' => ['en' => null, 'bn' => null],
            'donation_note' => [
                'en' => 'Please write "Donation" and your name in the payment reference.',
                'bn' => 'অনুগ্রহ করে পেমেন্টের রেফারেন্সে "Donation" ও আপনার নাম লিখুন।',
            ],
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::flushCache();
    }
}
