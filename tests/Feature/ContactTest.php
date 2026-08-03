<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function test_contact_page_exposes_success_flash_for_inertia(): void
    {
        $response = $this->withSession([
            '_flash' => [
                'new' => [
                    'success' => 'Your message has been sent.',
                ],
                'old' => [],
            ],
            'success' => 'Your message has been sent.',
        ])->get('/contact');

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('flash.success', 'Your message has been sent.'));
    }

    public function test_contact_form_can_be_submitted(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'Hello there, I would like to discuss a project.',
        ]);

        $response->assertRedirect();
        Mail::assertSent(\App\Mail\ContactFormMail::class);
    }

    public function test_contact_form_returns_a_user_friendly_error_when_smtp_auth_fails(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new TransportException('Failed to authenticate on SMTP server.'));

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'Hello there, I would like to discuss a project.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('error', 'Unable to send your message right now. Please try again later.');
    }
}
