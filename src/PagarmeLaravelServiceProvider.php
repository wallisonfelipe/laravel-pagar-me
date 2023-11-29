<?php

namespace Felipe\LaravelPagarMe;

use Illuminate\Support\ServiceProvider;

class PagarmeLaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations' => $this->app->databasePath() . '/migrations',
            __DIR__ . '/Controllers' => $this->app->basePath() . '/app/Http/Controllers',
        ], 'laravel-pagarme-migrations');
    }
}
