<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show login form
    public function showForm()
    {
        if (session('user_id')) {
            return session('user_role') === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email is required.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        $email    = strtolower(trim($request->email));
        $password = $request->password;

        // ✅ Find user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        // ✅ Block soft-deleted users
        if ($user->is_deleted === true) {
            return back()->with('error', 'This account has been deactivated. Please contact admin.');
        }

        // ✅ Check password
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        // ✅ Store user info in session
        session([
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'user_email'=> $user->email,
        ]);

        // ✅ Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}