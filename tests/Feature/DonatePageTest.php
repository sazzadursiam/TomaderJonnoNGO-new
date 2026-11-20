<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonatePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_details_from_settings_are_shown(): void
    {
        Setting::store([
            'donation_bkash' => '01922232794',
            'donation_bank_name' => 'Dutch-Bangla Bank',
            'donation_account_number' => '123.456.789',
        ]);

        $this->get('/donate')
            ->assertOk()
            ->assertSee('01922232794')
            ->assertSee('Dutch-Bangla Bank')
            ->assertSee('123.456.789')
            ->assertDontSee('Routing number');
    }

    public function test_a_placeholder_is_shown_when_no_details_exist(): void
    {
        $this->get('/donate')->assertOk()->assertSee('Donation details will be published soon.');
    }
}
