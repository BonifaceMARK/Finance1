<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Mail\mailotp;
use Closure;

class AuthController extends Controller
{
    //

    public function loadRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard'); // Redirect authenticated users directly to the dashboard
        }

        return view('register');
    }



public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|min:2',
        'department' => 'required|string',
        'email' => ['required', 'email', 'max:100', 'unique:users', function ($attribute, $value, $fail) {
            if (!Str::endsWith($value, '@gmail.com')) {
                $fail('The email must be a Gmail address.');
            }
        }],
        'password' => 'required|string|min:6'
    ]);

    // Create a new user instance
    $user = new User;
    $user->name = $request->name;
    $user->department = $request->department;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    // Redirect to the login page after successful registration
    return Redirect::route('loadlogin')->with('success', 'Your registration has been successful. You can now log in.');
}


public function loadLogin()
{


    return view('login');
}


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'string|required|email',
            'password' => 'string|required',
        ]);

        $userCredential = $request->only('email', 'password');

        if (Auth::attempt($userCredential)) {
            return redirect()->route('dashboard'); // Redirect directly to the dashboard route
        } else {
            return redirect()->back()->withInput()->withErrors(['email' => 'Invalid credentials.']);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
   /* public function changePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Verify the current password
        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        // Update the password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Password changed successfully.');
    }
    */
}
