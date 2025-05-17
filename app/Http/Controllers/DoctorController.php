<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     */
    public function index()
    {
        $doctors = Doctor::all(); // Fetch all doctors
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new doctor.
     */
    public function create()
    {
        return view('doctors.create');  // This view contains the form to add a new doctor
    }

    /**
     * Store a newly created doctor in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255', // Ensure 'specialization' is used
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        // Create and save the doctor record
        Doctor::create($request->only([
            'name', 'specialization', 'contact_number', 'address' // Match actual DB columns
        ]));

        // Redirect to the doctors list with a success message
        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');
    }
}
