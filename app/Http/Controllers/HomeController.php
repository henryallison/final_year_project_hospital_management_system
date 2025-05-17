<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\User;

class HomeController extends Controller
{
    // Decision tree structure (nodes contain decision criteria)
    private $decisionTree = [
    'attribute' => 'role',
    'branches' => [
        'doctor' => [
            'attribute' => 'has_active_cases',
            'branches' => [
                true => ['stats' => ['my_patients', 'active_cases', 'scheduled_appointments', 'completed_appointments', 'cancelled_appointments', 'rescheduled_appointments']],
                false => ['stats' => ['my_patients', 'scheduled_appointments', 'completed_appointments', 'cancelled_appointments', 'rescheduled_appointments']]
            ]
        ],
        'nurse' => [
            'attribute' => 'medications_due_today',
            'branches' => [
                true => ['stats' => ['assigned_patients', 'medications_due', 'scheduled_appointments', 'completed_appointments', 'cancelled_appointments', 'rescheduled_appointments']],
                false => ['stats' => ['assigned_patients', 'scheduled_appointments', 'completed_appointments', 'cancelled_appointments', 'rescheduled_appointments']]
            ]
        ],
        'admin' => [
            'stats' => [
                'total_staff',
                'scheduled_appointments',
                'completed_appointments',
                'cancelled_appointments',
                'rescheduled_appointments',
                'total_appointments'
            ]
        ]
    ]
];

   public function index()
{
    $user = Auth::user();
    $stats = $this->traverseDecisionTree($user);

    // Initialize base query for tasks
    $taskQuery = Task::query();

    // Modify query based on user role
    if ($user->isDoctor()) {
        $taskQuery->where('doctor_id', $user->id);
    } elseif ($user->isNurse()) {
        $taskQuery->where('nurse_id', $user->id);
    }
    // Admin sees all tasks (no additional where clause needed)

    // Add task counts to stats
    $stats['total_tasks'] = $taskQuery->count();
    $stats['pending_tasks'] = $taskQuery->clone()->where('status', 'pending')->count();
    $stats['completed_tasks'] = $taskQuery->clone()->where('status', 'completed')->count();

    // Get recent tasks (reusing the same filtered query)
    $recentTasks = $taskQuery->with('patient')
                            ->latest()
                            ->take(5)
                            ->get();

    return view('home', compact('user', 'stats', 'recentTasks'));
}

public function dashboard()
{
    $user = auth()->user();
    $stats = [
        'total_patients' => Patient::count(),
        'total_staff' => User::whereIn('role', ['doctor', 'nurse', 'admin'])->count(),
        'scheduled_appointments' => Appointment::where('status', 'scheduled')->count(),
        'completed_appointments' => Appointment::where('status', 'completed')->count(),
        'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
        'rescheduled_appointments' => Appointment::where('status', 'rescheduled')->count(),
    ];

    // Add role-specific stats
    if ($user->isAdmin()) {
        $stats['total_tasks'] = Task::count();
        $stats['my_patients'] = null;
        $stats['assigned_patients'] = null;
    } elseif ($user->isDoctor()) {
        $stats['total_tasks'] = Task::where('doctor_id', $user->id)->count();
        $stats['my_patients'] = $user->doctorPatients()->count();
        $stats['assigned_patients'] = null;
    } elseif ($user->isNurse()) {
        $stats['total_tasks'] = Task::where('nurse_id', $user->id)->count();
        $stats['assigned_patients'] = $user->nursePatients()->count();
        $stats['my_patients'] = null;
    }

    return view('dashboard', compact('user', 'stats'));
}

    protected function traverseDecisionTree($user)
{
    $baseStats = [
        'total_patients' => Patient::count(),
        'recent_patients' => Patient::latest()->take(5)->get(),
    ];

    // Initialize task query based on user role
    $taskQuery = Task::query();

    if ($user->isDoctor()) {
        $taskQuery->where('doctor_id', $user->id);
    } elseif ($user->isNurse()) {
        $taskQuery->where('nurse_id', $user->id);
    }
    // Admin sees all tasks (no additional where clause needed)

    // Add task statistics to base stats
    $taskStats = [
        'total_tasks' => $taskQuery->count(),
        'pending_tasks' => $taskQuery->clone()->where('status', 'pending')->count(),
        'in_progress_tasks' => $taskQuery->clone()->where('status', 'in_progress')->count(),
        'completed_tasks' => $taskQuery->clone()->where('status', 'completed')->count(),
        'failed_tasks' => $taskQuery->clone()->where('status', 'failed')->count(),
        'recent_tasks' => $taskQuery->clone()->with('patient')->latest()->take(5)->get(),
    ];

    $roleStats = $this->evaluateNode($user, $this->decisionTree);

    return array_merge($baseStats, $taskStats, $this->fetchStatsData($user, $roleStats));
}

    protected function evaluateNode($user, $node)
    {
        if (isset($node['stats'])) {
            return $node['stats'];
        }

        $attribute = $node['attribute'];
        $value = $this->getAttributeValue($user, $attribute);

        if (isset($node['branches'][$value])) {
            return $this->evaluateNode($user, $node['branches'][$value]);
        }

        return [];
    }

    protected function getAttributeValue($user, $attribute)
    {
        switch ($attribute) {
            case 'role':
                if ($user->isDoctor()) return 'doctor';
                if ($user->isNurse()) return 'nurse';
                if ($user->isAdmin()) return 'admin';
                break;

            case 'has_active_cases':
                return Patient::where('doctor_id', $user->id)
                    ->where('status', 'active')
                    ->exists();

            case 'medications_due_today':
                return Patient::where('nurse_id', $user->id)
                    ->whereHas('medications', fn($q) => $q->whereDate('due_date', today()))
                    ->exists();
        }

        return null;
    }

    protected function fetchStatsData($user, $statKeys)
{
    $stats = [];

    foreach ($statKeys as $key) {
        switch ($key) {
            case 'my_patients':
                $stats['my_patients'] = Patient::where('doctor_id', $user->id)->count();
                break;

            case 'active_cases':
                $stats['active_cases'] = Patient::where('doctor_id', $user->id)
                    ->where('status', 'active')
                    ->count();
                break;

            case 'assigned_patients':
                $stats['assigned_patients'] = Patient::where('nurse_id', $user->id)->count();
                break;

            case 'medications_due':
                $stats['medications_due'] = Patient::where('nurse_id', $user->id)
                    ->whereHas('medications', fn($q) => $q->whereDate('due_date', today()))
                    ->count();
                break;

            case 'total_staff':
                $stats['total_staff'] = User::where('role', '!=', 'patient')->count();
                break;

            case 'scheduled_appointments':
                if ($user->isAdmin()) {
                    $stats['scheduled_appointments'] = Appointment::where('status', 'scheduled')->count();
                } elseif ($user->isDoctor()) {
                    $stats['scheduled_appointments'] = Appointment::where('doctor_id', $user->id)
                        ->where('status', 'scheduled')
                        ->count();
                }
                break;

            case 'completed_appointments':
                if ($user->isAdmin()) {
                    $stats['completed_appointments'] = Appointment::where('status', 'completed')->count();
                } elseif ($user->isDoctor()) {
                    $stats['completed_appointments'] = Appointment::where('doctor_id', $user->id)
                        ->where('status', 'completed')
                        ->count();
                }
                break;

            case 'cancelled_appointments':
                if ($user->isAdmin()) {
                    $stats['cancelled_appointments'] = Appointment::where('status', 'cancelled')->count();
                } elseif ($user->isDoctor()) {
                    $stats['cancelled_appointments'] = Appointment::where('doctor_id', $user->id)
                        ->where('status', 'cancelled')
                        ->count();
                }
                break;

            case 'rescheduled_appointments':
                if ($user->isAdmin()) {
                    $stats['rescheduled_appointments'] = Appointment::where('status', 'rescheduled')->count();
                } elseif ($user->isDoctor()) {
                    $stats['rescheduled_appointments'] = Appointment::where('doctor_id', $user->id)
                        ->where('status', 'rescheduled')
                        ->count();
                }
                break;
        }
    }

    return $stats;
}}
