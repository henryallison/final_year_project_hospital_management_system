<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait AuditLogger
{
    protected static function bootAuditLogger()
    {
        static::created(function($model) {
            $user = auth()->user() ? auth()->user()->email : 'System';
            Log::info("{$user} created ".get_class($model)." ID: {$model->id}");
        });

        static::updated(function($model) {
            $user = auth()->user() ? auth()->user()->email : 'System';
            Log::info("{$user} updated ".get_class($model)." ID: {$model->id}");
        });

        static::deleted(function($model) {
            $user = auth()->user() ? auth()->user()->email : 'System';
            Log::info("{$user} deleted ".get_class($model)." ID: {$model->id}");
        });
    }
}
