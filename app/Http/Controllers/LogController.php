<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        $logFile = storage_path('logs/laravel.log');

        $logs = [];
        if (File::exists($logFile)) {
            $logs = array_reverse(explode("\n", File::get($logFile)));
            $logs = array_filter($logs);
        }

        return view('admin.logs.index', compact('logs'));
    }

    public function clear()
    {
        $logFile = storage_path('logs/laravel.log');
        File::put($logFile, '');

        return back()->with('success', 'Logs cleared successfully');
    }
}
