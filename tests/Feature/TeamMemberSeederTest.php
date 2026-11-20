<?php

namespace Tests\Feature;

use App\Models\TeamMember;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\TeamMemberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamMemberSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_management_team_is_seeded_with_photos(): void
    {
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);

        $members = TeamMember::query()->published()->get();

        $this->assertSame(
            ['Md. Forid Ahmed', 'Md. Hamidul Haque', 'Barrister H M Sanzid Siddiqi', 'Khandoker Fuyad Oman', 'Alve Shabab Bin Sharif'],
            $members->map(fn (TeamMember $member) => $member->translate('name', 'en'))->all(),
        );
        $this->assertSame('photos/management-team/md-forid-ahmed.jpg', $members->first()->photo);
        $members->each(fn (TeamMember $member) => Storage::disk('public')->assertExists($member->photo));

        $this->get('/team')->assertOk()->assertSee('Md. Forid Ahmed')->assertSee('President');
        $this->get('/bn/team')->assertOk()->assertSee('মো. ফরিদ আহমেদ')->assertSee('সভাপতি');
    }

    public function test_placeholders_are_removed_but_admin_edits_are_kept(): void
    {
        Storage::fake('public');
        $placeholder = TeamMember::factory()->create(['name' => ['en' => 'Ayesha Rahman (sample)', 'bn' => null]]);
        $unnamed = TeamMember::factory()->create(['name' => ['en' => null, 'bn' => null]]);
        $other = TeamMember::factory()->create(['name' => ['en' => 'Real Advisor', 'bn' => null]]);

        $this->seed(TeamMemberSeeder::class);

        TeamMember::query()->where('name->en', 'Md. Forid Ahmed')->firstOrFail()
            ->update(['role' => ['en' => 'Chairman', 'bn' => 'চেয়ারম্যান'], 'photo' => 'team/admin-upload.jpg']);

        $this->seed(TeamMemberSeeder::class);

        $this->assertModelMissing($placeholder);
        $this->assertModelMissing($unnamed);
        $this->assertModelExists($other);
        $this->assertSame(6, TeamMember::query()->count());

        $president = TeamMember::query()->where('name->en', 'Md. Forid Ahmed')->firstOrFail();
        $this->assertSame('Chairman', $president->translate('role', 'en'));
        $this->assertSame('team/admin-upload.jpg', $president->photo);
    }
}
