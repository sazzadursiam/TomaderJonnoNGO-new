<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Sample donation values written by earlier demo seeds; they are replaced or cleared.
     *
     * @var list<string>
     */
    private const array DEMO_DONATION_VALUES = [
        '01700-000000 (sample)',
        '01800-000000 (sample)',
        'Sample Bank PLC',
        'Tomader Jonno',
        '0000-0000-0000-0 (sample)',
        'Mirpur Branch, Dhaka',
        '000000000',
    ];

    /**
     * Seed the site address, contact and donation details (as published on tomaderjonno.org/sponsor-a-child).
     *
     * Values entered from the admin panel are never overwritten.
     */
    public function run(): void
    {
        $defaults = [
            'phone' => '+8801922232794',
            'email' => 'info@tomaderjonno.org',
            'donation_note' => [
                'en' => 'Please write "Donation" and your name in the payment reference.',
                'bn' => 'অনুগ্রহ করে পেমেন্টের রেফারেন্সে "Donation" ও আপনার নাম লিখুন।',
            ],
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $address = Setting::query()->firstOrNew(['key' => 'address']);

        if (blank(array_filter((array) $address->value)) || str_contains($address->value['en'] ?? '', '(sample address)')) {
            $address->fill(['value' => ['en' => 'Master Para, Gabtali, Bogura', 'bn' => 'মাস্টার পাড়া, গাবতলী, বগুড়া']])->save();
        }

        $donationDetails = [
            'donation_bank_name' => 'Rupali Bank',
            'donation_account_name' => null,
            'donation_account_number' => '4036010011175',
            'donation_branch' => 'Gabtoli Branch, Bogra',
            'donation_routing_number' => null,
            'donation_bkash' => '01922232794',
            'donation_nagad' => null,
        ];

        foreach ($donationDetails as $key => $value) {
            $setting = Setting::query()->firstOrNew(['key' => $key]);

            if (blank($setting->value) || in_array($setting->value, self::DEMO_DONATION_VALUES, true)) {
                $setting->fill(['value' => $value])->save();
            }
        }

        Setting::flushCache();
    }
}
