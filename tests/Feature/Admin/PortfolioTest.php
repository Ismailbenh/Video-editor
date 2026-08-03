<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_portfolio_piece(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->post('/admin/portfolio', [
            'title' => 'New Campaign',
            'category' => 'youtube',
            'thumbnail' => '',
            'featured' => false,
            'client' => 'Acme',
            'date' => '2026-08',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('portfolio_items', [
            'title' => 'New Campaign',
            'category' => 'youtube',
            'client' => 'Acme',
        ]);
    }
}
