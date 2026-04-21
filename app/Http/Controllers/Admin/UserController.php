<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show create form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'max:15', 'regex:/^[a-zA-Z ]+$/'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'regex:/^\S+$/'],
            'role'     => ['required', 'in:user,admin'],
        ], [
            'name.required'     => 'Name is required.',
            'name.max'          => 'Name must be 15 characters or less.',
            'name.regex'        => 'Name must contain letters only.',
            'email.required'    => 'Email is required.',
            'email.email'       => 'Enter a valid email address.',
            'email.unique'      => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 6 characters.',
            'password.regex'    => 'No spaces allowed in password.',
        ]);

        User::create([
            'name'     => trim($request->name),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phoneno'  => '',
            'address'  => '',
        ]);

        return redirect()->route('admin.dashboard')
               ->with('success', 'User created successfully! Phone, address & photo can be added via Edit.');
    }

    // Show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => ['required', 'max:15', 'regex:/^[a-zA-Z ]+$/'],
            'phoneno' => ['nullable', 'regex:/^[0-9]{10}$/'],
            'address' => ['nullable', 'max:50'],
            'password'=> ['nullable', 'min:6', 'regex:/^\S+$/'],
            'role'    => ['required', 'in:user,admin'],
        ], [
            'name.required' => 'Name is required.',
            'name.max'      => 'Name must be 15 characters or less.',
            'name.regex'    => 'Name must contain letters only.',
            'phoneno.regex' => 'Phone number must be exactly 10 digits.',
            'address.max'   => 'Address must be 50 characters or less.',
            'password.min'  => 'Password must be at least 6 characters.',
            'password.regex'=> 'No spaces allowed in password.',
        ]);

        $user->name    = trim($request->name);
        $user->phoneno = $request->phoneno ?? '';
        $user->address = $request->address ?? '';
        $user->role    = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            $file     = $request->file('profile_pic');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile_pics', $filename, 'public');
            $user->profile_pic = 'profile_pics/' . $filename;
        }

        $user->save();

        return redirect()->route('admin.dashboard')
               ->with('success', 'User updated successfully!');
    }

    // Soft delete
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->is_deleted === true) {
            return redirect()->route('admin.dashboard')
                   ->with('error', 'User is already deactivated.');
        }

        $user->is_deleted = true;
        $user->deleted_at = now();
        $user->save();

        return redirect()->route('admin.dashboard')
               ->with('success', 'User deactivated successfully.');
    }
}