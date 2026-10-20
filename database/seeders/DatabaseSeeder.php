<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            ProgramSeeder::class,
            StatSeeder::class,
            StorySeeder::class,
            TeamMemberSeeder::class,
            SitePhotoSeeder::class,
        ]);

        if (app()->isLocal()) {
            $this->call(DemoContentSeeder::class);

            User::query()->firstOrCreate(
                ['email' => 'admin@tomaderjonno.org'],
                ['name' => 'Admin', 'password' => 'password'],
            );
        }
    }
}
