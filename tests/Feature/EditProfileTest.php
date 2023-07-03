<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class EditProfileTest extends TestCase
{

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

    public function test_user_can_edit_their_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/update_profile', [
            'name' => 'Test Test',
            'email' => 'test@test.test',
            'password' => 'password',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertValid();

        $user->destroy($user->id);

    }

    public function test_user_can_change_their_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/update_profile', [
            'name' => 'Test Test',
            'email' => 'test@test.test',
            'password' => 'password'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertValid();
        
        $user = User::find($user->id);

        $this->assertEquals($user->name, 'Test Test');
        $this->assertEquals($user->email, 'test@test.test');

        $user->destroy($user->id);

    }

    public function test_user_cannot_edit_their_profile_with_incorrect_data(): void
    {
        $user = User::factory()->create();

        // blank fields
        $response = $this->actingAs($user)->put('/update_profile', [
            'name' => '',
            'email' => '',
            'password' => ''
        ]);
        $response->assertInvalid([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'password' => 'The password field is required.'
        ]);
        $response->assertStatus(302);

        // fields too short
        $response = $this->actingAs($user)->put('/update_profile', [
            'name' => 'a',
            'email' => 'a@a.a',
            'password' => 'password'
        ]);
        $response->assertInvalid([
            'name' => 'The name field must be at least 2 characters.',
            'email' => 'The email field must be at least 8 characters.'
        ]);

        // change email to someone else's
        $another_user = User::factory()->create();
        $response = $this->actingAs($user)->put('/update_profile', [
            'name' => 'Test Test',
            'email' => $another_user->email,
            'password' => 'password'
        ]);
        $response->assertInvalid([
            'email' => 'The email has already been taken.'
        ]);
        $another_user->destroy($another_user->id);

        // change password with non-matching new passwords
        $response = $this->actingAs($user)->put('/update_profile', [
            'name' => 'Test Test',
            'email' => $user->email,
            'password' => 'password',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'different',
        ]);
        $response->assertInvalid([
            'new_password_confirmation' => 'The new password confirmation field must match new password.'
        ]);

        // update data with incorrect password
        $response = $this->actingAs($user)->put('/update_profile', [
            'name' => 'Test Test',
            'email' => $user->email,
            'password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);
        $response->assertInvalid([
            'password' => 'Incorrect password'
        ]);

        $user->destroy($user->id);
    }






}
