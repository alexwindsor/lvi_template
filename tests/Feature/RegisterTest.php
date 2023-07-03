<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{

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

    public function test_user_can_register_with_correct_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Test',
            'email' => 'test@test.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertValid();
        $this->assertAuthenticated();

        User::where('email', 'test@test.test')->delete();
    }


    public function test_user_cannot_register_with_incorrect_data(): void
    {
        // blank fields
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertInvalid([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
            'password_confirmation' => 'The password confirmation field is required.'
        ]);
        $response->assertStatus(302);

        // fields too short
        $response = $this->post('/register', [
            'name' => 'a',
            'email' => 'a@a.a',
            'password' => 'a',
            'password_confirmation' => 'a',
        ]);
        $response->assertInvalid([
            'name' => 'The name field must be at least 2 characters.',
            'email' => 'The email field must be at least 8 characters.',
            'password' => 'The password field must be at least 8 characters.',
            'password_confirmation' => 'The password confirmation field must be at least 8 characters.'
        ]);
        $response->assertStatus(302);

        // password confirmation doesn't match
        $response = $this->post('/register', [
            'name' => 'Test Test',
            'email' => 'test@test.test',
            'password' => 'password',
            'password_confirmation' => 'different',
        ]);
        $response->assertInvalid([
            'password_confirmation' => 'The password confirmation field must match password.'
        ]);
        $response->assertStatus(302);

        $this->assertGuest();


    }

}
