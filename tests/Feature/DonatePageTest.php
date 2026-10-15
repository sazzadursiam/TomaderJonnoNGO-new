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

    public function test_the_chosen_quick_give_amount_is_confirmed(): void
    {
        $this->get('/donate?amount=2500&frequency=once')->assertSee('You chose a one-time gift of ৳2,500.');
        $this->get('/bn/donate?amount=1000&frequency=monthly')->assertSee('আপনি মাসিক ৳১,০০০ অনুদান বেছে নিয়েছেন।');
    }

    public function test_invalid_amounts_are_ignored(): void
    {
        $this->get('/donate?amount=10')->assertDontSee('You chose');
        $this->get('/donate?amount=abc')->assertDontSee('You chose');
    }
}
