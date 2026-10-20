<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Story;
use App\Models\TeamMember;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fills_every_empty_field_with_demo_content(): void
    {
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);
        $this->seed(DemoContentSeeder::class);

        $this->assertNotEmpty(Setting::get('address')['en']);
        $this->assertNotEmpty(Setting::get('donation_bkash'));
        $this->assertNotEmpty(Setting::get('facebook_url'));
        Storage::disk('public')->assertExists(Setting::get('about_image'));

        Program::query()->each(function (Program $program): void {
            Storage::disk('public')->assertExists($program->image);

            foreach ($program->wings ?? [] as $wing) {
                $this->assertNotEmpty($wing['images']);
            }
        });

        Story::query()->each(function (Story $story): void {
            $this->assertNotEmpty($story->translate('body', 'en'));
            $this->assertNotEmpty($story->translate('body', 'bn'));
            Storage::disk('public')->assertExists($story->image);
        });

        TeamMember::query()->each(function (TeamMember $member): void {
            $this->assertNotEmpty($member->translate('name', 'en'));
            Storage::disk('public')->assertExists($member->photo);
        });

        $this->assertSame(3, ContactMessage::query()->count());
    }

    public function test_it_never_overwrites_content_entered_by_an_admin(): void
    {
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);

        Setting::store(['donation_bkash' => '01999-999999']);
        $member = TeamMember::query()->orderBy('sort_order')->firstOrFail();
        $member->update(['name' => ['en' => 'Real Chairperson', 'bn' => null]]);
        $story = Story::query()->firstOrFail();
        $story->update(['image' => 'stories/real-photo.jpg']);

        $this->seed(DemoContentSeeder::class);

        $this->assertSame('01999-999999', Setting::get('donation_bkash'));
        $this->assertSame('Real Chairperson', $member->fresh()->translate('name', 'en'));
        $this->assertSame('stories/real-photo.jpg', $story->fresh()->image);
    }
}
