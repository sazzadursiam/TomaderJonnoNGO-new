<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    /**
     * Seed the impact figures shown on the home page (from the demo design; verify before launch).
     */
    public function run(): void
    {
        $stats = [
            [12, 'Active Projects', 'চলমান প্রকল্প'],
            [4500, 'Children Supported', 'সহায়তাপ্রাপ্ত শিশু'],
            [2800, 'Women & Youth Empowered', 'ক্ষমতায়িত নারী ও তরুণ'],
            [15, 'Communities Reached', 'যেসব সমাজে পৌঁছেছি'],
        ];

        foreach ($stats as $index => [$value, $labelEn, $labelBn]) {
            Stat::query()->updateOrCreate(
                ['sort_order' => $index + 1],
                ['value' => $value, 'suffix' => '+', 'label' => ['en' => $labelEn, 'bn' => $labelBn]],
            );
        }
    }
}
