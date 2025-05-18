<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index()
{
    $user = auth()->user();

    if ($user->isDoctor()) {
        // For doctors, only show their assigned patients
        $patients = Patient::with(['doctor', 'nurse'])
                    ->where('doctor_id', $user->id)
                    ->get();
    } elseif ($user->isAdmin()) {
        // For admins, show all patients
        $patients = Patient::with(['doctor', 'nurse'])->get();
    } else {
        // For other roles (like nurses), show patients assigned to them
        $patients = Patient::with(['doctor', 'nurse'])
                    ->where('nurse_id', $user->id)
                    ->get();
    }

    return view('patients.index', compact('patients'));
}
    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();
        $nurses = User::where('role', 'nurse')->get();
        return view('patients.create', compact('doctors', 'nurses'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\+[0-9]{12,15}$/', $value)) {
                        $fail('The contact number must start with + followed by 12 to 15 digits (e.g., +123456789012).');
                    }
                },
                Rule::unique('patients', 'contact_number')
            ],
            'address' => 'required|string|max:255',
            'medical_history' => 'required|string',
            'allergies' => 'required|string',
            'current_medications' => 'required|string',
            'admission_date' => 'required|date',
            'discharge_date' => 'nullable|date|after_or_equal:admission_date',
            'status' => 'required|in:active,discharged,transferred',
            'doctor_id' => 'required|exists:users,id',
            'nurse_id' => 'nullable|exists:users,id',
            'blood_type' => 'required|string|max:10',
            'height' => 'required|numeric|min:50|max:250',
            'weight' => 'required|numeric|min:2|max:300',
            'chronic_conditions' => 'required|string',
            'family_medical_history' => 'required|string'
        ]);

        if (in_array($request->status, ['discharged', 'transferred'])) {
            if (!$request->discharge_date) {
                return back()->withErrors(['discharge_date' => 'Discharge date is required when status is discharged or transferred.'])->withInput();
            }

            if ($request->discharge_date < $request->admission_date) {
                return back()->withErrors(['discharge_date' => 'Discharge date must be after or equal to admission date.'])->withInput();
            }
        }

        $dischargeDate = $request->status === 'active' ? null : $request->discharge_date;

        $sensitiveData = [
            'blood_type' => $request->blood_type,
            'height' => $request->height,
            'weight' => $request->weight,
            'chronic_conditions' => $request->chronic_conditions,
            'family_medical_history' => $request->family_medical_history,
            'contact_number' => $request->contact_number,
            'address' => $request->address
        ];

        $encryptedData = Crypt::encryptString(json_encode($sensitiveData));

        Patient::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'medical_history' => $request->medical_history,
            'allergies' => $request->allergies,
            'current_medications' => $request->current_medications,
            'admission_date' => $request->admission_date,
            'discharge_date' => $dischargeDate,
            'status' => $request->status,
            'encrypted_data' => $encryptedData,
            'doctor_id' => $request->doctor_id,
            'nurse_id' => $request->nurse_id,
        ]);

        return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

public function showDetails(Patient $patient)
{
    try {
        $decryptedData = json_decode(Crypt::decryptString($patient->encrypted_data), true);
    } catch (\Exception $e) {
        $decryptedData = [
            'blood_type' => 'N/A',
            'height' => 'N/A',
            'weight' => 'N/A',
            'chronic_conditions' => 'N/A',
            'family_medical_history' => 'N/A',
            'contact_number' => 'N/A',
            'address' => 'N/A'
        ];
    }

    return view('patients.details', [
        'patient' => $patient,
        'decryptedData' => $decryptedData
    ]);
}

    /**
     * Show the form for editing a patient.
     */
    public function edit(Patient $patient)
{
    $user = auth()->user();

    // Get doctors list based on user role
    if ($user->isDoctor() && !$user->isAdmin()) {
        // For non-admin doctors, only include themselves in the doctors list
        $doctors = User::where('id', $user->id)->get();

        // Verify the doctor has permission to edit this patient
        if ($patient->doctor_id !== $user->id) {
            abort(403, 'You are not authorized to edit this patient.');
        }
    } else {
        // For admins or other roles, get all doctors
        $doctors = User::where('role', 'doctor')->get();
    }

    // Get nurses list
    $nurses = User::where('role', 'nurse')->get();

    try {
        $decryptedData = json_decode(Crypt::decryptString($patient->encrypted_data), true);
    } catch (\Exception $e) {
        $decryptedData = [
            'blood_type' => '',
            'height' => '',
            'weight' => '',
            'chronic_conditions' => '',
            'family_medical_history' => '',
            'contact_number' => '',
            'address' => ''
        ];
    }

    return view('patients.edit', compact('patient', 'doctors', 'nurses', 'decryptedData'));
}

    public function update(Request $request, Patient $patient)
{
    $user = auth()->user();

    // For non-admin doctors, ensure they can't change the doctor assignment
    if ($user->isDoctor() && !$user->isAdmin()) {
        // Verify the doctor has permission to edit this patient
        if ($patient->doctor_id !== $user->id) {
            abort(403, 'You are not authorized to update this patient.');
        }

        // Force the doctor_id to be their own ID
        $request->merge(['doctor_id' => $user->id]);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'date_of_birth' => 'required|date',
        'gender' => 'required|in:male,female,other',
        'contact_number' => [
            'required',
            'string',
            'max:20',
            function ($attribute, $value, $fail) {
                if (!preg_match('/^\+[0-9]{12,15}$/', $value)) {
                    $fail('The contact number must start with + followed by 12 to 15 digits (e.g., +123456789012).');
                }
            },
            Rule::unique('patients', 'contact_number')->ignore($patient->id)
        ],
        'address' => 'required|string|max:255',
        'medical_history' => 'required|string',
        'allergies' => 'required|string',
        'current_medications' => 'required|string',
        'admission_date' => 'required|date',
        'discharge_date' => 'nullable|date|after_or_equal:admission_date',
        'status' => 'required|in:active,discharged,transferred',
        'doctor_id' => 'required_if:status,active|exists:users,id',
        'nurse_id' => 'nullable|exists:users,id',
        'blood_type' => 'required|string|max:10',
        'height' => 'required|numeric|min:50|max:250',
        'weight' => 'required|numeric|min:2|max:300',
        'chronic_conditions' => 'required|string',
        'family_medical_history' => 'required|string'
    ]);

    if (in_array($request->status, ['discharged', 'transferred'])) {
        if (!$request->discharge_date) {
            return back()->withErrors(['discharge_date' => 'Discharge date is required when status is discharged or transferred.'])->withInput();
        }

        if ($request->discharge_date < $request->admission_date) {
            return back()->withErrors(['discharge_date' => 'Discharge date must be after or equal to admission date.'])->withInput();
        }

        // Unassign doctor and nurse if status is discharged/transferred
        $request->merge(['doctor_id' => null, 'nurse_id' => null]);
    }

    $dischargeDate = $request->status === 'active' ? null : $request->discharge_date;

    $sensitiveData = [
        'blood_type' => $request->blood_type,
        'height' => $request->height,
        'weight' => $request->weight,
        'chronic_conditions' => $request->chronic_conditions,
        'family_medical_history' => $request->family_medical_history,
        'contact_number' => $request->contact_number,
        'address' => $request->address
    ];

    $encryptedData = Crypt::encryptString(json_encode($sensitiveData));

    $patient->update([
        'name' => $request->name,
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender,
        'contact_number' => $request->contact_number,
        'address' => $request->address,
        'medical_history' => $request->medical_history,
        'allergies' => $request->allergies,
        'current_medications' => $request->current_medications,
        'admission_date' => $request->admission_date,
        'discharge_date' => $dischargeDate,
        'status' => $request->status,
        'encrypted_data' => $encryptedData,
        'doctor_id' => $request->doctor_id,
        'nurse_id' => $request->nurse_id,
    ]);

    return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
}

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }

    /**
     * Fetch the recent patients.
     */
    public function recentPatients()
    {
        $recentPatients = Patient::with(['doctor', 'nurse'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        return view('patients.recent', compact('recentPatients'));
    }
}
