<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/delete_account', [
            'password' => 'password'
        ]);

        // $new_user = User::find($user->id);
        $this->assertNull(User::find($user->id));
        $this->assertGuest();
    }


    public function test_user_cannot_delete_their_account_with_the_wrong_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/delete_account', ['password' => 'wrongpassword']);

        $response->assertSessionHasErrors(['password' => 'Incorrect password']);
        $this->assertAuthenticated();
    }

    public function test_user_cannot_delete_their_account_with_incorrect_data(): void
    {
        $user = User::factory()->create();

       // blank fields
        $response = $this->actingAs($user)->put('/delete_account', ['password' => '']);
        $response->assertSessionHasErrors(['password' => 'The password field is required.']);
        $response->assertStatus(302);

        // fields too short
        $response = $this->actingAs($user)->put('/delete_account', ['password' => 'asdfasd']);
        $response->assertSessionHasErrors(['password' => 'The password field must be at least 8 characters.']);

        // fields too long
        $response = $this->actingAs($user)->put('/delete_account', ['password' => 'abcdefghijklmnopqrstuvwxyzqwertyz']);
        $response->assertSessionHasErrors(['password' => 'The password field must not be greater than 32 characters.',]);

        // dd(session('errors'));

        $this->assertAuthenticated();
    }

}