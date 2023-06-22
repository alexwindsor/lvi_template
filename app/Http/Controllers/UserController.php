<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{

    public function store() {

        $fields = request()->validate([
            'name' => ['required', 'min:2', 'max:64'],
            'email' => ['required', 'min:8', 'max:96', 'unique:App\Models\User,email'],
            'password' => ['required', 'min:8', 'max:32'],
            'password_confirmation' => ['required', 'min:8', 'max:32', 'same:password']
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        auth()->login($user);

        return redirect('/');
    }


    public function login() {

        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
          ]);
      
          if (! auth()->attempt($attributes)) {
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

        $fields = request()->validate([
            'name' => ['required', 'min:2', 'max:64'],
            'email' => ['required', 'min:8', 'max:96', Rule::unique('users')->ignore(auth()->user()->id)],
            'password' => ['required', 'min:8', 'max:32'],
            'new_password' => ['nullable', 'min:8', 'max:32'],
            'new_password_confirmation' => ['nullable', 'min:8', 'max:32', 'same:new_password']
        ]);

        $auth['email'] = auth()->user()->email;
        $auth['password'] = $fields['password'];

        if (! auth()->attempt($auth)) {
            return back()->withErrors(['password' => 'Incorrect password'])->withInput();
        }

        $user = User::find(auth()->user()->id);
        $user->name = $fields['name'];
        if ($fields['new_password'] && $fields['new_password_confirmation']) {
            $user->password = Hash::make($fields['new_password']);
        }
        $user->save();

        return redirect('/');
    }


    public function destroy() {
        
        $fields = request()->validate([
            'password' => ['required']
        ]);

        $fields['email'] = auth()->user()->email;

        if (! auth()->attempt($fields)) {
            return back()->withErrors(['password' => 'Incorrect password'])->withInput();
        }

        User::destroy(auth()->user()->id);
        auth()->logout();
        
        return redirect('/');
    }




}
