<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_when_not_logged_in(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_home_page_when_logged_in(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);

        $user->destroy($user->id);
    }

    public function test_edit_profile_page_cannot_be_reached_when_not_logged_in(): void
    {
        $response = $this->get('/edit_profile');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_edit_profile_page_can_be_reached_when_logged_in(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/edit_profile');
        $response->assertStatus(200);

        $user->destroy($user->id);
    }

    public function test_login_page_can_be_reached_when_not_logged_in(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_page_cant_be_reached_when_logged_in(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $user->destroy($user->id);
    }

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


    public function test_register_page_can_be_reached_when_not_logged_in(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_register_page_cannot_be_reached_when_logged_in(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $user->destroy($user->id);
    }
}
