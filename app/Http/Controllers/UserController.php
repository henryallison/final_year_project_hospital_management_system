<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

use Illuminate\Support\Facades\Http;


class UserController extends Controller
{
    /**
     * Display a listing of users (admin only).
     */
    public function index()
    {
        $users = User::whereNull('deleted_at')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
{
    $messages = [
        'profile_image.image' => 'The profile image must be a valid image file.',
        'profile_image.max' => 'The profile image must not exceed 2MB.',
    ];

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'role' => 'required|in:doctor,nurse,admin,pharmacist',
        'license_number' => [
            'required', 'string', 'max:255',
            Rule::unique('users', 'license_number')->ignore($user->id)
        ],
        'phone' => [
            'required', 'string', 'max:20',
            Rule::unique('users', 'phone')->ignore($user->id),
            function ($attribute, $value, $fail) {
                if (!preg_match('/^\+[0-9]{12,15}$/', $value)) {
                    $fail('The contact number must start with + followed by 12 to 15 digits (e.g., +123456789012).');
                }
            }
        ],
        'address' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
        'gender' => 'required|in:male,female,other',
        'password' => 'nullable|string|min:8|confirmed',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], $messages);

    try {
        $data = $request->only([
            'first_name', 'last_name', 'email', 'role',
            'license_number', 'phone', 'address',
            'date_of_birth', 'gender'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filePath = $file->getRealPath();

            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $apiKey = env('CLOUDINARY_API_KEY');
            $apiSecret = env('CLOUDINARY_API_SECRET');
            $timestamp = time();

            $paramsToSign = ['timestamp' => $timestamp];
            ksort($paramsToSign);
            $signatureString = http_build_query($paramsToSign) . $apiSecret;
            $signature = sha1($signatureString);

            $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                ['name' => 'file', 'contents' => fopen($filePath, 'r')],
                ['name' => 'api_key', 'contents' => $apiKey],
                ['name' => 'timestamp', 'contents' => $timestamp],
                ['name' => 'signature', 'contents' => $signature],
            ]);

            if ($response->failed()) {
                throw new \Exception('Cloudinary upload failed.');
            }

            $data['profile_image'] = $response['secure_url'];
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Error updating user: ' . $e->getMessage());
    }
}


    /**
     * Soft delete the specified user.
     */
    public function softDelete(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully (soft delete).');
    }

    /**
     * Permanently delete a user.
     */
    public function destroy(User $user)
    {
        $user->forceDelete();
        return redirect()->route('users.index')->with('success', 'User deleted permanently.');
    }
}
