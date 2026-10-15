<?php

namespace Tests\Feature;

use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Models\ContactMessage;
use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    /**
     * @return array<string, array{string}>
     */
    public static function adminPages(): array
    {
        return [
            'dashboard' => ['/admin'],
            'programs' => ['/admin/programs'],
            'program create' => ['/admin/programs/create'],
            'stories' => ['/admin/stories'],
            'story create' => ['/admin/stories/create'],
            'team' => ['/admin/team-members'],
            'stats' => ['/admin/stats'],
            'inbox' => ['/admin/contact-messages'],
            'settings' => ['/admin/site-settings'],
        ];
    }

    #[DataProvider('adminPages')]
    public function test_admin_pages_render(string $path): void
    {
        Program::factory()->create();
        ContactMessage::factory()->create();

        $this->actingAs(User::factory()->create())->get($path)->assertOk();
    }

    public function test_admin_can_create_a_bilingual_program(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateProgram::class)
            ->fillForm([
                'slug' => 'health-camps',
                'icon' => 'rehabilitation',
                'title' => ['en' => 'Health Camps', 'bn' => 'স্বাস্থ্য ক্যাম্প'],
                'summary' => ['en' => 'Free check-ups', 'bn' => ''],
                'description' => ['en' => 'Free medical check-ups in villages.', 'bn' => ''],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $program = Program::query()->where('slug', 'health-camps')->firstOrFail();

        $this->assertSame('স্বাস্থ্য ক্যাম্প', $program->translate('title', 'bn'));
        $this->assertSame('Free check-ups', $program->translate('summary', 'bn'));
        $this->get('/bn/programs/health-camps')->assertOk()->assertSee('স্বাস্থ্য ক্যাম্প');
    }

    public function test_admin_can_update_site_settings(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(SiteSettings::class)
            ->fillForm([
                'phone' => '+8801711111111',
                'donation_bkash' => '01711111111',
                'address' => ['en' => 'Dhaka, Bangladesh', 'bn' => 'ঢাকা, বাংলাদেশ'],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('+8801711111111', Setting::get('phone'));
        $this->get('/contact')->assertSee('+8801711111111')->assertSee('Dhaka, Bangladesh');
        $this->get('/bn/contact')->assertSee('ঢাকা, বাংলাদেশ');
        $this->get('/donate')->assertSee('01711111111');
    }
}
