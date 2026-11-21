<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\SettingSeeder;
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
            ->assertDontSee('rupali-bank.svg')
            ->assertDontSee('Routing number');
    }

    public function test_the_published_payment_details_are_seeded(): void
    {
        $this->seed(SettingSeeder::class);

        $this->get('/donate')
            ->assertOk()
            ->assertSee('Rupali Bank')
            ->assertSee('Gabtoli Branch, Bogra')
            ->assertSee('4036010011175')
            ->assertSee('01922232794')
            ->assertSee('images/payment/bkash.svg')
            ->assertSee('images/payment/rupali-bank.svg')
            ->assertDontSee('Nagad')
            ->assertDontSee('Routing number');
    }

    public function test_sample_payment_details_are_replaced_but_admin_values_are_kept(): void
    {
        Setting::store([
            'donation_bank_name' => 'Sample Bank PLC',
            'donation_nagad' => '01800-000000 (sample)',
            'donation_bkash' => '01711111111',
        ]);

        $this->seed(SettingSeeder::class);

        $this->assertSame('Rupali Bank', Setting::get('donation_bank_name'));
        $this->assertNull(Setting::get('donation_nagad'));
        $this->assertSame('01711111111', Setting::get('donation_bkash'));
    }

    public function test_the_nagad_number_is_shown_once_it_is_set(): void
    {
        Setting::store(['donation_nagad' => '01811111111']);

        $this->get('/donate')->assertOk()->assertSee('01811111111')->assertSee('Nagad');
    }

    public function test_a_placeholder_is_shown_when_no_details_exist(): void
    {
        $this->get('/donate')->assertOk()->assertSee('Donation details will be published soon.');
    }
}
