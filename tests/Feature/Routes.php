<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class GetRoutesTest extends TestCase
{

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
}
