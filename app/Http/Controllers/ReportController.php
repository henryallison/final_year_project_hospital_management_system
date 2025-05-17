<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Medication;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.generate');
    }

    // Other methods like staff(), patients(), etc.
}
