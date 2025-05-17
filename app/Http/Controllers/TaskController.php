<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->authorizeResource(Task::class, 'task');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $tasks = Task::with(['patient', 'doctor', 'nurse'])->latest()->get();
        } elseif ($user->isDoctor()) {
            $tasks = Task::with(['patient', 'nurse'])
                ->forDoctor($user->id)
                ->latest()
                ->get();
        } else {
            $tasks = Task::with(['patient', 'doctor'])
                ->forNurse($user->id)
                ->latest()
                ->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create()
{
    $user = auth()->user();
    $patients = [];
    $doctors = [];
    $nurses = User::where('role', 'nurse')->get(['id', 'first_name', 'last_name']);

    if ($user->isAdmin()) {
        $patients = Patient::all(['id', 'name']);
        $doctors = User::where('role', 'doctor')->get(['id', 'first_name', 'last_name']);
    } elseif ($user->isDoctor()) {
        $patients = $user->doctorPatients()->get(['id', 'name']);
        $doctors = User::where('id', $user->id)->get(['id', 'first_name', 'last_name']);
    }

    return view('tasks.create', compact('patients', 'doctors', 'nurses'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'nurse_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:now',
        ]);

        // For doctors, ensure they can only assign to themselves
        if (Auth::user()->isDoctor()) {
            $validated['doctor_id'] = Auth::id();
        }

        // Verify doctor-patient relationship for admin
        if (Auth::user()->isAdmin()) {
            $patient = Patient::find($validated['patient_id']);
            if ($patient->doctor_id != $validated['doctor_id']) {
                return back()->withErrors(['doctor_id' => 'The selected doctor is not assigned to this patient']);
            }
        }

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
{
    $user = auth()->user();
    $patients = [];
    $doctors = [];
    $nurses = User::where('role', 'nurse')->get(['id', 'first_name', 'last_name']);

    if ($user->isAdmin()) {
        $patients = Patient::all(['id', 'name']);
        $doctors = User::where('role', 'doctor')->get(['id', 'first_name', 'last_name']);
    } elseif ($user->isDoctor()) {
        $patients = $user->doctorPatients()->get(['id', 'name']);
        $doctors = User::where('id', $user->id)->get(['id', 'first_name', 'last_name']);
    }

    return view('tasks.edit', compact('task', 'patients', 'doctors', 'nurses'));
}

public function update(Request $request, Task $task)
{
    $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'doctor_id' => 'required|exists:users,id',
        'nurse_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'due_date' => 'required|date',
        'status' => 'required|in:pending,in_progress,completed,failed',
        'notes' => 'nullable|string',
    ]);

    // For doctors, ensure they can only assign to themselves
    if (Auth::user()->isDoctor()) {
        $validated['doctor_id'] = Auth::id();
    }

    // Verify doctor-patient relationship for admin
    if (Auth::user()->isAdmin()) {
        $patient = Patient::find($validated['patient_id']);
        if ($patient->doctor_id != $validated['doctor_id']) {
            return back()->withErrors(['doctor_id' => 'The selected doctor is not assigned to this patient']);
        }
    }

    // Nurses can only update status to in_progress
    if (Auth::user()->isNurse()) {
        if ($request->status != 'in_progress' && $task->status != 'in_progress') {
            return back()->withErrors(['status' => 'You can only set status to in progress']);
        }
        $validated['status'] = $task->status == 'in_progress' ? $request->status : 'in_progress';
    }

    // Handle completed_at logic
    if (in_array($validated['status'], ['completed', 'failed'])) {
        // Set completed_at if status is being changed to completed or failed
        $validated['completed_at'] = now();
    } elseif ($task->status === 'completed' && $validated['status'] !== 'completed') {
        // Clear completed_at if changing from completed to another status
        $validated['completed_at'] = null;
    }

    $task->update($validated);

    return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
}
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }
}
