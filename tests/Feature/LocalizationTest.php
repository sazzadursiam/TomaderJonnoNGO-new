<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Support\Localization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_english_is_served_without_prefix_and_bangla_under_bn(): void
    {
        $this->get('/about')->assertOk()->assertSee('About Us')->assertDontSee('আমাদের সম্পর্কে');
        $this->get('/bn/about')->assertOk()->assertSee('আমাদের সম্পর্কে');
    }

    public function test_language_switch_links_to_the_same_page_in_the_other_language(): void
    {
        $program = Program::factory()->create(['slug' => 'education']);

        $this->get('/programs/education')
            ->assertSee('href="'.url('/bn/programs/education').'" hreflang="bn"', false)
            ->assertSee('<link rel="alternate" hreflang="bn" href="'.url('/bn/programs/education').'">', false);

        $this->get('/bn/programs/education')
            ->assertSee('href="'.url('/programs/education').'" hreflang="en"', false);

        $this->assertSame(url('/bn/programs/education'), route('bn.programs.show', $program));
    }

    public function test_bangla_pages_link_to_bangla_urls(): void
    {
        $this->get('/bn/contact')
            ->assertSee('href="'.url('/bn/about').'"', false)
            ->assertSee('action="'.url('/bn/contact').'"', false);
    }

    public function test_translatable_values_fall_back_to_english(): void
    {
        $this->assertSame('Hello', Localization::pick(['en' => 'Hello', 'bn' => null], 'bn'));
        $this->assertSame('হ্যালো', Localization::pick(['en' => 'Hello', 'bn' => 'হ্যালো'], 'bn'));
        $this->assertSame('', Localization::pick(null, 'bn'));
    }

    public function test_numbers_use_bangla_digits_on_the_bangla_site(): void
    {
        $this->assertSame('4,500', Localization::number(4500, 'en'));
        $this->assertSame('৪,৫০০', Localization::number(4500, 'bn'));
        $this->assertSame('২০২৬', Localization::digits('2026', 'bn'));
    }
}
