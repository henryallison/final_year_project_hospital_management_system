<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::whereNull('deleted_at')->get();
        return view('admin.manage-users', compact('users'));
    }

    public function create()
    {
        return redirect()->route('register');
    }

    public function edit(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'role' => 'required|in:doctor,nurse,admin,pharmacist',
        ]);

        $user->update($request->only(['first_name', 'last_name', 'role', 'phone', 'address']));
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
