<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Rules\NoAtSymbolRule;

class UserController extends Controller
{

    public function store() {

        $fields = request()->validate([
            'username' => ['required', 'string', 'min:3', 'max:32', 'unique:App\Models\User,username', new NoAtSymbolRule],
            'email' => ['required', 'email', 'min:6', 'max:96', 'unique:App\Models\User,email'],
            'password' => ['required', 'min:8', 'max:32'],
            'password_confirmation' => ['required', 'min:8', 'max:32', 'same:password']
        ]);

        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        auth()->login($user);

        return redirect('/');
    }


    public function login() {

        $attributes = request()->validate([
            'username' => ['required_without:email', 'nullable', 'string', 'min:3', 'max:32', new NoAtSymbolRule],
            'email' => ['required_without:username', 'nullable', 'email', 'min:6', 'max:96'],
            'password' => ['required', 'string', 'min:8', 'max:32'],
            'remember' => ['required', 'boolean'],
        ]);

        $pass = ['password' => $attributes['password']];
        $attributes = $attributes['username'] ? [...$pass, 'username' => $attributes['username']] : [...$pass, 'email' => $attributes['email']];

        if (! Auth::attempt($attributes, request('remember'))) {
            return back()->withErrors(['authentication' => 'Incorrect email or password'])->withInput();
          }

          session()->regenerate();

          return redirect('/');
    }


    public function logout() {

        auth()->logout();
        return redirect('/');
    }


    public function update() {

        // validate
        $fields = request()->validate([
            'username' => ['required', 'string', 'min:3', 'max:32', Rule::unique('users')->ignore(auth()->user()->id), new NoAtSymbolRule],
            'email' => ['required', 'email', 'min:6', 'max:96', Rule::unique('users')->ignore(auth()->user()->id)],
            'password' => ['required', 'min:8', 'max:32'],
            'new_password' => ['required_with:new_password_confirmation', 'min:8', 'max:32'],
            'new_password_confirmation' => ['required_with:new_password', 'min:8', 'max:32', 'same:new_password']
        ]);

        // check that the password is correct and return back with custom error if not
        if (! $this->authenticate($fields['password'])) {
            return back()->withErrors(['password' => 'Incorrect password'])->withInput();
        }

        // update the username and email, and new password if necessary
        $user = User::find(auth()->user()->id);
        $user->username = $fields['username'];
        $user->email = $fields['email'];
        if (isset($fields['new_password']) && $fields['new_password_confirmation']) {
            $user->password = Hash::make($fields['new_password']);
        }
        $user->save();

        return redirect('/');
    }


    public function destroy() {

        // validate
        $fields = request()->validate([
            'password' => ['required']
        ]);

        // check that the password is correct and return back with custom error if not
        if (! $this->authenticate($fields['password'])) {
            return back()->withErrors(['password' => 'Incorrect password'])->withInput();
        }

        User::destroy(auth()->user()->id);
        auth()->logout();

        return redirect('/');
    }


    private function authenticate($password) {

        $auth = [
            'email' => auth()->user()->email,
            'password' => $password
        ];

        // check that the password is correct and return back with custom error if not
        if (! auth()->attempt($auth)) return false;

        return true;
    }

}
