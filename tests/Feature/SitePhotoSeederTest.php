<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Setting;
use App\Models\Story;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoContentSeeder;
use Database\Seeders\SitePhotoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SitePhotoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_real_photos_are_placed_across_programs_stories_and_about_page(): void
    {
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);

        $education = Program::query()->where('slug', 'education')->firstOrFail();
        $women = Program::query()->where('slug', 'womens-empowerment')->firstOrFail();

        $this->assertSame('photos/education/6.jpg', $education->image);
        $this->assertCount(6, $education->gallery);
        $this->assertSame([8, 8, 2, 3], array_map(fn (array $wing) => count($wing['images']), $women->wings));
        Storage::disk('public')->assertExists($women->wings[2]['images'][0]);

        $this->assertSame('photos/womens-empowerment/16.jpg', Story::query()->where('slug', 'a-salon-of-her-own')->value('image'));
        $this->assertSame('photos/womens-empowerment/22.jpg', Setting::get('about_image'));

        $this->get('/programs/education')->assertOk()->assertSee('/storage/photos/education/2.jpg', false)->assertSee('data-lightbox', false);
        $this->get('/bn/programs/education')->assertOk()->assertSee('ছবির গ্যালারি');
    }

    public function test_demo_placeholders_are_replaced_but_admin_photos_are_kept(): void
    {
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);

        $education = Program::query()->where('slug', 'education')->firstOrFail();
        $education->update(['image' => 'programs/chosen-by-admin.jpg']);
        $story = Story::query()->where('slug', 'baskets-to-markets')->firstOrFail();
        $story->update(['image' => DemoContentSeeder::DEMO_DIRECTORY.'/stories/placeholder.jpg']);

        $this->seed(SitePhotoSeeder::class);

        $this->assertSame('programs/chosen-by-admin.jpg', $education->fresh()->image);
        $this->assertSame('photos/womens-empowerment/11.jpg', $story->fresh()->image);
    }
}
