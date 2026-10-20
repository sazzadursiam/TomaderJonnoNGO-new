<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Stat;
use App\Models\Story;
use App\Models\TeamMember;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function staticPages(): array
    {
        return [
            'home' => ['/'],
            'about' => ['/about'],
            'team' => ['/team'],
            'programs' => ['/programs'],
            'projects' => ['/projects'],
            'donate' => ['/donate'],
            'contact' => ['/contact'],
        ];
    }

    #[DataProvider('staticPages')]
    public function test_every_menu_page_renders_in_english_and_bangla(string $path): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get($path)->assertOk()->assertSee('<html lang="en">', false);
        $this->get('/bn'.rtrim($path, '/'))->assertOk()->assertSee('<html lang="bn">', false);
    }

    public function test_home_page_shows_programs_stats_and_latest_stories(): void
    {
        $program = Program::factory()->create(['title' => ['en' => 'Clean Water', 'bn' => 'বিশুদ্ধ পানি']]);
        Stat::factory()->create(['label' => ['en' => 'Wells Built', 'bn' => 'নলকূপ'], 'value' => 4500]);
        $story = Story::factory()->create(['title' => ['en' => 'A New Well', 'bn' => null]]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Clean Water')
            ->assertSee('Wells Built')
            ->assertSee('data-count="4500"', false)
            ->assertSee('A New Well')
            ->assertSee(route('programs.show', $program), false);

        $this->get('/bn')
            ->assertOk()
            ->assertSee('বিশুদ্ধ পানি')
            ->assertSee('৪,৫০০')
            ->assertSee('A New Well')
            ->assertSee(route('bn.stories.show', $story), false);
    }

    public function test_home_page_shows_donation_steps_and_rotating_taglines_in_both_languages(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('How Your Donation Helps')
            ->assertSeeInOrder(['You Give', 'We Act', 'Lives Change'])
            ->assertSee('data-typewriter', false)
            ->assertSee('livelihoods', false)
            ->assertSee('a greener tomorrow', false);

        $this->get('/bn')
            ->assertOk()
            ->assertSee('আপনার অনুদান কীভাবে কাজ করে')
            ->assertSeeInOrder(['আপনি দেন', 'আমরা কাজ করি', 'জীবন বদলায়'])
            ->assertSee('শিশুদের শিক্ষার জন্য');
    }

    public function test_program_page_shows_focus_areas_and_wings(): void
    {
        $program = Program::factory()->create([
            'slug' => 'womens-empowerment',
            'focus_areas' => [['title' => ['en' => 'Tailoring', 'bn' => 'সেলাই'], 'text' => ['en' => 'Sewing skills', 'bn' => null]]],
            'wings' => [['label' => 'Wing A', 'title' => ['en' => 'Nakshi Kantha', 'bn' => 'নকশিকাঁথা'], 'text' => ['en' => 'Needlework', 'bn' => null], 'images' => []]],
        ]);

        $this->get(route('programs.show', $program))
            ->assertOk()
            ->assertSee('Tailoring')
            ->assertSee('Nakshi Kantha')
            ->assertSee('Wing A');

        $this->get(route('bn.programs.show', $program))
            ->assertOk()
            ->assertSee('সেলাই')
            ->assertSee('নকশিকাঁথা')
            ->assertSee('Sewing skills');
    }

    public function test_unpublished_content_is_hidden(): void
    {
        $hiddenProgram = Program::factory()->create(['is_published' => false]);
        $hiddenStory = Story::factory()->create(['is_published' => false]);
        $futureStory = Story::factory()->create(['published_at' => now()->addWeek()]);
        $hiddenMember = TeamMember::factory()->create(['is_published' => false, 'name' => ['en' => 'Hidden Person', 'bn' => null]]);

        $this->get(route('programs.show', $hiddenProgram))->assertNotFound();
        $this->get(route('stories.show', $hiddenStory))->assertNotFound();
        $this->get(route('stories.show', $futureStory))->assertNotFound();
        $this->get('/projects')->assertDontSee($futureStory->translate('title'));
        $this->get('/team')->assertDontSee($hiddenMember->translate('name'));
    }

    public function test_story_page_renders_paragraphs_and_more_stories(): void
    {
        $story = Story::factory()->create(['body' => ['en' => "First paragraph.\n\nSecond paragraph.", 'bn' => null]]);
        $other = Story::factory()->create();

        $this->get(route('stories.show', $story))
            ->assertOk()
            ->assertSee('<p>First paragraph.</p>', false)
            ->assertSee('<p>Second paragraph.</p>', false)
            ->assertSee($other->translate('title'));
    }

    public function test_team_page_lists_published_members(): void
    {
        TeamMember::factory()->create(['name' => ['en' => 'Ayesha Rahman', 'bn' => 'আয়েশা রহমান'], 'role' => ['en' => 'Executive Director', 'bn' => 'নির্বাহী পরিচালক']]);

        $this->get('/team')->assertOk()->assertSee('Ayesha Rahman')->assertSee('Executive Director');
        $this->get('/bn/team')->assertOk()->assertSee('আয়েশা রহমান')->assertSee('নির্বাহী পরিচালক');
    }

    public function test_team_members_without_a_name_show_their_role_as_heading(): void
    {
        TeamMember::factory()->create(['name' => ['en' => null, 'bn' => null], 'role' => ['en' => 'Chairperson', 'bn' => 'চেয়ারপারসন']]);

        $this->get('/team')->assertOk()->assertSee('<h3 class="mt-4 font-bold text-accent">Chairperson</h3>', false);
        $this->get('/bn/team')->assertOk()->assertSee('চেয়ারপারসন');
    }

    public function test_program_page_uses_its_page_heading_and_falls_back_to_title(): void
    {
        $withHeading = Program::factory()->create([
            'title' => ['en' => 'Education', 'bn' => 'শিক্ষা'],
            'page_title' => ['en' => 'Education Program', 'bn' => 'শিক্ষা কার্যক্রম'],
        ]);
        $withoutHeading = Program::factory()->create(['title' => ['en' => 'Health', 'bn' => null], 'page_title' => null]);

        $this->get(route('programs.show', $withHeading))->assertSee('>Education Program</h1>', false);
        $this->get(route('bn.programs.show', $withHeading))->assertSee('শিক্ষা কার্যক্রম');
        $this->get(route('programs.show', $withoutHeading))->assertSee('>Health</h1>', false);
    }

    public function test_unknown_program_returns_404(): void
    {
        $this->get('/programs/does-not-exist')->assertNotFound();
    }
}
