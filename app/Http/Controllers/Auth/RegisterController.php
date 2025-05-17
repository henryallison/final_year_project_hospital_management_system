<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('showRegistrationForm', 'register');
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        Log::info('Registration form accessed');
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
{
    $this->validator($request->all())->validate();

    try {
        $user = $this->create($request);
        Log::info("New user registered: {$user->email} (ID: {$user->id})", [
            'role' => $user->role,
            'ip' => $request->ip()
        ]);

        return $this->registered($request, $user);
    } catch (\Exception $e) {
        Log::error("Registration failed: " . $e->getMessage(), [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);
        return back()->with('error', 'Registration failed. Please try again.');
    }
}

protected function validator(array $data)
{
    return Validator::make($data, [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:doctor,nurse,admin,pharmacist'],
        'license_number' => ['required', 'string', 'max:255', 'unique:users,license_number'],
        'phone' => [
            'required', 'string', 'max:20',
            function ($attribute, $value, $fail) {
                if (!preg_match('/^\+[0-9]{12,15}$/', $value)) {
                    $fail('The contact number must start with + followed by 12 to 15 digits (e.g., +123456789012).');
                }
            },
            Rule::unique('users', 'phone')
        ],
        'address' => ['required', 'string', 'max:255'],
        'date_of_birth' => ['required', 'date'],
        'gender' => ['required', 'in:male,female,other'],
        'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ]);
}

protected function create(Request $request)
{
    $data = $request->all();
    $profileImageUrl = null;

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

        if ($response->successful() && isset($response['secure_url'])) {
            $profileImageUrl = $response['secure_url'];
        } else {
            throw new \Exception('Profile image upload failed.');
        }
    }

    $user = User::create([
        'first_name'     => $data['first_name'],
        'last_name'      => $data['last_name'],
        'email'          => $data['email'],
        'password'       => Hash::make($data['password']),
        'role'           => $data['role'],
        'license_number' => $data['license_number'],
        'phone'          => $data['phone'],
        'address'        => $data['address'],
        'date_of_birth'  => $data['date_of_birth'],
        'gender'         => $data['gender'],
        'profile_image'  => $profileImageUrl,
        'is_active'      => 0,
    ]);

    return $user;
}

    /**
     * Redirect after successful registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    protected function registered(Request $request, $user)
    {
        return redirect()->route('home')->with('status', 'User registered successfully.');
    }
}
