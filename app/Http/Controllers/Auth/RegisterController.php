<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show the register form
    public function showForm()
    {
        // If already logged in, redirect away
        if (session('user_id')) {
            return session('user_role') === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }
        return view('auth.register');
    }

    // Handle register form submission
    public function register(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'name'     => [
                'required',
                'max:15',
                'regex:/^[a-zA-Z ]+$/'
            ],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'min:8',
                'confirmed',        // checks password_confirmation field
                'regex:/^\S+$/'     // no spaces
            ],
        ], [
            'name.required'     => 'Name is required.',
            'name.max'          => 'Name must be 15 characters or less.',
            'name.regex'        => 'Name must contain letters only.',
            'email.required'    => 'Email is required.',
            'email.email'       => 'Please enter a valid email address.',
            'email.unique'      => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.confirmed'=> 'Passwords do not match.',
            'password.regex'    => 'No spaces allowed in password.',
        ]);

        // ✅ Create user
        User::create([
            'name'     => trim($request->name),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        return redirect()->route('login')
               ->with('success', 'Registration successful! Please log in.');
    }
}