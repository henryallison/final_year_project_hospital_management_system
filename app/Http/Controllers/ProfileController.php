<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

public function show()
{
    $user = Auth::user();

    // For Cloudinary, we don't need to check file existence as it's handled by CDN
    // Just pass the user object to the view
    return view('profile.show', compact('user'));
}
    public function update(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user->id)
        ],
        'date_of_birth' => 'nullable|date',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'gender' => 'nullable|in:male,female,other',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'current_password' => 'nullable|required_with:password|current_password',
        'password' => 'nullable|string|min:8|confirmed|different:current_password',
    ], [
        'current_password.current_password' => 'The current password is incorrect.',
        'password.different' => 'New password must be different from current password.'
    ]);

    try {
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image from Cloudinary if exists
            if ($user->profile_image) {
                Storage::disk('cloudinary')->delete($user->profile_image);
            }

            // Store new image in Cloudinary
            $path = $request->file('profile_image')->store(
                'profile_images',
                'cloudinary'
            );
            $validated['profile_image'] = $path;
        }

        // Update user data
        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'profile_image' => $validated['profile_image'] ?? $user->profile_image,
        ];

        $user->update($updateData);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
            Auth::logoutOtherDevices($validated['password']);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');

    } catch (\Exception $e) {
        // Delete the uploaded image from Cloudinary if something went wrong
        if (isset($path)) {
            try {
                Storage::disk('cloudinary')->delete($path);
            } catch (\Exception $deleteException) {
                \Log::error("Failed to delete Cloudinary image: ".$deleteException->getMessage());
            }
        }

        return back()->withInput()
            ->with('error', 'An error occurred while updating your profile: '.$e->getMessage());
    }
}
