<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all medications
        $medications = Medication::all();

        // Return the view with the medications data
        return view('medications.index', compact('medications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view for creating a new medication
        return view('medications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dosage' => 'required|string|max:255',
        ]);

        // Create a new medication
        Medication::create([
            'name' => $request->name,
            'description' => $request->description,
            'dosage' => $request->dosage,
        ]);

        // Redirect to the medications index page with success message
        return redirect()->route('medications.index')->with('success', 'Medication created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the medication by ID
        $medication = Medication::findOrFail($id);

        // Return the view with the medication data
        return view('medications.show', compact('medication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Find the medication by ID
        $medication = Medication::findOrFail($id);

        // Return the view for editing the medication
        return view('medications.edit', compact('medication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dosage' => 'required|string|max:255',
        ]);

        // Find the medication by ID and update its details
        $medication = Medication::findOrFail($id);
        $medication->update([
            'name' => $request->name,
            'description' => $request->description,
            'dosage' => $request->dosage,
        ]);

        // Redirect to the medications index page with success message
        return redirect()->route('medications.index')->with('success', 'Medication updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the medication by ID and delete it
        $medication = Medication::findOrFail($id);
        $medication->delete();

        // Redirect to the medications index page with success message
        return redirect()->route('medications.index')->with('success', 'Medication deleted successfully');
    }
}
