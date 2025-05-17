<?php

namespace App\Providers;

use App\Providers\Services\EncryptionService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class EncryptionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton('encryption.service', function ($app) {
            return new EncryptionService();
        });

        $this->app->alias('encryption.service', EncryptionService::class);
    }

    public function provides()
    {
        return [
            'encryption.service',
            EncryptionService::class
        ];
    }
}
