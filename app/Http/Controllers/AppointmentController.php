<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
{
    if (Auth::user()->isDoctor()) {
        $appointments = Appointment::with(['patient', 'doctor', 'creator'])
            ->where('doctor_id', Auth::id())
            ->latest()
            ->paginate(10);
    } else {
        $appointments = Appointment::with(['patient', 'doctor', 'creator'])
            ->latest()
            ->paginate(10);
    }

    return view('appointments.index', compact('appointments'));
}
    public function create()
{
    if (Auth::user()->isDoctor()) {
        $patients = Patient::where('doctor_id', Auth::id())->get();
        $doctors = User::where('id', Auth::id())->get();
    } else {
        $patients = Patient::all();
        $doctors = User::where('role', 'doctor')->get();
    }

    return view('appointments.create', compact('patients', 'doctors'));
}

    public function store(Request $request)
{
    // Force doctor_id to be the authenticated doctor's ID if user is a doctor
    if (Auth::user()->isDoctor()) {
        $request->merge(['doctor_id' => Auth::id()]);
    }

    $validated = $request->validate([
        'patient_id' => [
            'required',
            'exists:patients,id',
            function ($attribute, $value, $fail) {
                // For doctors, ensure they can only create appointments for their own patients
                if (Auth::user()->isDoctor()) {
                    $isMyPatient = Patient::where('id', $value)
                        ->where('doctor_id', Auth::id())
                        ->exists();

                    if (!$isMyPatient) {
                        $fail('You can only create appointments for your assigned patients.');
                    }
                }
            }
        ],
        'doctor_id' => [
            'required',
            'exists:users,id',
            function ($attribute, $value, $fail) {
                $doctor = User::find($value);

                // Ensure selected user is a doctor
                if ($doctor && $doctor->role !== 'doctor') {
                    $fail('The selected user is not a doctor.');
                }

                // For doctors, ensure they can't select other doctors
                if (Auth::user()->isDoctor() && $value != Auth::id()) {
                    $fail('You can only create appointments for yourself.');
                }
            }
        ],
        'appointment_date' => [
            'required',
            'date',
            'after:now',
            function ($attribute, $value, $fail) use ($request) {
                $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
                    ->where('appointment_date', $value)
                    ->where('status', '!=', 'cancelled')
                    ->exists();

                if ($existingAppointment) {
                    $fail('The doctor already has an appointment at this time.');
                }
            }
        ],
        'purpose' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
    ], [
        'appointment_date.after' => 'The appointment date must be in the future.',
        'purpose.max' => 'The purpose may not be greater than 255 characters.',
        'description.max' => 'The description may not be greater than 1000 characters.'
    ]);

    // Create the appointment
    $appointment = Appointment::create([
        ...$validated,
        'status' => 'scheduled',
        'created_by' => Auth::id()
    ]);

    return redirect()->route('appointments.index')
        ->with('success', 'Appointment created successfully.');
}

    public function show($id)
{
    $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
    return view('appointments.show', compact('appointment'));
}

    public function edit(Appointment $appointment)
    {
        $patients = Patient::all(); // Removed ->active()
        $doctors = User::where('role', 'doctor')->get(); // Removed ->active()

        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
{
    // For doctors, ensure they can only edit their own appointments
    if (Auth::user()->isDoctor() && $appointment->doctor_id != Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Base validation rules
    $rules = [
        'patient_id' => [
            'required',
            'exists:patients,id',
            function ($attribute, $value, $fail) {
                if (Auth::user()->isDoctor() && !Patient::where('id', $value)->where('doctor_id', Auth::id())->exists()) {
                    $fail('You can only assign your own patients to appointments.');
                }
            }
        ],
        'appointment_date' => [
            'required',
            'date',
            function ($attribute, $value, $fail) use ($appointment, $request) {
                $appointmentDateTime = Carbon::parse($value);
                $now = Carbon::now();

                // Check if date is in the past for scheduled/rescheduled statuses
                if (in_array($request->status, ['scheduled', 'rescheduled']) && $appointmentDateTime->lt($now)) {
                    $fail('Appointment date must be in the future for scheduled/rescheduled appointments.');
                }

                // Check for existing appointments
                $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
                    ->where('appointment_date', $value)
                    ->where('status', '!=', 'cancelled')
                    ->where('id', '!=', $appointment->id)
                    ->exists();

                if ($existingAppointment) {
                    $fail('The doctor already has an appointment at this time.');
                }
            }
        ],
        'purpose' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'status' => [
            'required',
            'in:scheduled,completed,cancelled,rescheduled',
            function ($attribute, $value, $fail) use ($request) {
                // Validate future dates for scheduled/rescheduled
                if (in_array($value, ['scheduled', 'rescheduled']) && Carbon::parse($request->appointment_date)->isPast()) {
                    $fail('Cannot schedule/reschedule an appointment in the past.');
                }
            }
        ]
    ];

    // Doctor ID validation rules
    if (Auth::user()->isDoctor()) {
        $rules['doctor_id'] = [
            'required',
            'exists:users,id',
            function ($attribute, $value, $fail) {
                if ($value != Auth::id()) {
                    $fail('You can only assign appointments to yourself.');
                }
            }
        ];
    } else {
        $rules['doctor_id'] = [
            'required',
            'exists:users,id',
            function ($attribute, $value, $fail) {
                $doctor = User::find($value);
                if ($doctor && $doctor->role !== 'doctor') {
                    $fail('The selected user is not a doctor.');
                }
            }
        ];
    }

    // Custom validation messages
    $messages = [
        'purpose.max' => 'The purpose may not be greater than 255 characters.',
        'description.max' => 'The description may not be greater than 1000 characters.',
        'status.in' => 'The selected status is invalid.',
        'appointment_date.required' => 'The appointment date field is required.',
        'appointment_date.date' => 'The appointment date must be a valid date.'
    ];

    $validated = $request->validate($rules, $messages);

    try {
        // For doctors, ensure they can't change the doctor assignment
        if (Auth::user()->isDoctor()) {
            $validated['doctor_id'] = Auth::id();
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');

    } catch (\Exception $e) {
        Log::error("Appointment update failed: " . $e->getMessage());
        return back()->withErrors(['error' => 'Failed to update appointment. Please try again.'])->withInput();
    }
}

    public function destroy(Appointment $appointment)
{
    // Prevent doctors from deleting any appointments
    if (auth()->user()->isDoctor()) {
        return redirect()->route('appointments.index')
            ->with('error', 'You are not authorized to delete appointments.');
    }

    try {
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');

    } catch (\Exception $e) {
        Log::error("Appointment deletion failed: " . $e->getMessage());
        return redirect()->route('appointments.index')
            ->with('error', 'Failed to delete appointment. Please try again.');
    }
}
    public function cancel(Appointment $appointment)
    {
        if ($appointment->status === 'completed') {
            return back()->with('error', 'Cannot cancel a completed appointment.');
        }

        $appointment->cancel();

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function showProfile()
{
    $user = Auth::user();
    return view('profile.show', compact('user'));
}

public function editProfile()
{
    $user = Auth::user();
    return view('profile.edit', compact('user'));
}


public function updateProfile(Request $request)
{
    $user = Auth::user();

    $messages = [
        'profile_image.dimensions' => 'The profile image must be at least ' . User::PROFILE_IMAGE_WIDTH . 'x' . User::PROFILE_IMAGE_HEIGHT . ' pixels.',
        'profile_image.max' => 'The profile image may not be larger than ' . (User::PROFILE_IMAGE_MAX_SIZE / 1024) . 'MB.',
    ];

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => [
            'required', 'string', 'email', 'max:255',
            Rule::unique('users')->ignore($user->id)
        ],
        'date_of_birth' => 'nullable|date',
        'phone' => [
            'nullable', 'string', 'max:20',
            Rule::unique('users')->ignore($user->id)
        ],
        'address' => 'nullable|string|max:500',
        'profile_image' => User::validateProfileImage(),
        'current_password' => 'nullable|required_with:password|current_password',
        'password' => 'nullable|string|min:8|confirmed|different:current_password',
    ], $messages);

    try {
        // Handle profile image upload to Cloudinary
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filePath = $file->getRealPath();

            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $apiKey = env('CLOUDINARY_API_KEY');
            $apiSecret = env('CLOUDINARY_API_SECRET');
            $timestamp = time();

            // Create signature
            $paramsToSign = ['timestamp' => $timestamp];
            ksort($paramsToSign);
            $signatureString = http_build_query($paramsToSign) . $apiSecret;
            $signature = sha1($signatureString);

            // Upload to Cloudinary
            $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                ['name' => 'file', 'contents' => fopen($filePath, 'r')],
                ['name' => 'api_key', 'contents' => $apiKey],
                ['name' => 'timestamp', 'contents' => $timestamp],
                ['name' => 'signature', 'contents' => $signature],
            ]);

            if ($response->failed()) {
                throw new \Exception('Cloudinary upload failed.');
            }

            $validated['profile_image'] = $response['secure_url'];
        }

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if (isset($validated['profile_image'])) {
            $updateData['profile_image'] = $validated['profile_image'];
        }

        $user->update($updateData);

        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
            Auth::logoutOtherDevices($validated['password']);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'An error occurred while updating your profile: ' . $e->getMessage());
    }
}
    public function complete(Appointment $appointment)
    {
        if ($appointment->status === 'cancelled') {
            return back()->with('error', 'Cannot complete a cancelled appointment.');
        }

        $appointment->complete();

        return back()->with('success', 'Appointment marked as completed.');
    }


    public function reschedule(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'appointment_date' => [
                'required',
                'date',
                'after:now',
                function ($attribute, $value, $fail) use ($appointment) {
                    $existingAppointment = Appointment::where('doctor_id', $appointment->doctor_id)
                        ->where('appointment_date', $value)
                        ->where('status', '!=', 'cancelled')
                        ->where('id', '!=', $appointment->id)
                        ->exists();

                    if ($existingAppointment) {
                        $fail('The doctor already has an appointment at this time.');
                    }
                }
            ]
        ], [
            'appointment_date.after' => 'The new appointment date must be in the future.'
        ]);

        $appointment->reschedule($validated['appointment_date']);

        return back()->with('success', 'Appointment rescheduled successfully.');
    }
}
