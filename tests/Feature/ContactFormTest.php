<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_send_a_message(): void
    {
        $this->post('/contact', [
            'name' => 'Karim',
            'contact' => 'karim@example.com',
            'message' => 'I would like to volunteer on weekends.',
        ])->assertRedirect('/contact')->assertSessionHas('status');

        $this->assertDatabaseHas(ContactMessage::class, [
            'name' => 'Karim',
            'contact' => 'karim@example.com',
            'locale' => 'en',
            'read_at' => null,
        ]);
    }

    public function test_bangla_form_redirects_back_to_bangla_page_with_bangla_message(): void
    {
        $this->post('/bn/contact', [
            'name' => 'করিম',
            'contact' => '01700000000',
            'message' => 'আমি স্বেচ্ছাসেবক হিসেবে কাজ করতে চাই।',
        ])->assertRedirect('/bn/contact');

        $this->assertDatabaseHas(ContactMessage::class, ['name' => 'করিম', 'locale' => 'bn']);

        $this->get('/bn/contact')->assertSee('ধন্যবাদ! আপনার বার্তা পাঠানো হয়েছে।');
    }

    public function test_required_fields_are_validated(): void
    {
        $this->from('/contact')
            ->post('/contact', ['name' => '', 'contact' => '', 'message' => 'short'])
            ->assertRedirect('/contact')
            ->assertSessionHasErrors(['name', 'contact', 'message']);

        $this->assertDatabaseCount(ContactMessage::class, 0);
    }

    public function test_honeypot_submissions_are_silently_discarded(): void
    {
        $this->post('/contact', [
            'name' => 'Bot',
            'contact' => 'bot@example.com',
            'message' => 'Buy cheap things now!!!',
            'website' => 'https://spam.example',
        ])->assertRedirect('/contact')->assertSessionHas('status');

        $this->assertDatabaseCount(ContactMessage::class, 0);
    }

    public function test_submissions_are_rate_limited(): void
    {
        $payload = ['name' => 'Karim', 'contact' => 'karim@example.com', 'message' => 'Hello there, just checking in.'];

        foreach (range(1, 5) as $attempt) {
            $this->post('/contact', $payload)->assertRedirect();
        }

        $this->post('/contact', $payload)->assertTooManyRequests();
    }
}
