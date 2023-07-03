<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{

    public function test_logout_page_cannot_be_reached_when_not_logged_in(): void
    {
        $response = $this->get('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_logout_page_can_be_reached_when_logged_in(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/logout');
        $response->assertStatus(200);

        $user->destroy($user->id);
    }

    public function test_user_can_log_out(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $response->assertStatus(302);
        $this->assertGuest();
        
        $user->destroy($user->id);
    }

}
