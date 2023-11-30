<?php

namespace Felipe\LaravelPagarMe;

use Illuminate\Support\ServiceProvider;

class PagarmeLaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations' => $this->app->databasePath() . '/migrations',
        ], 'laravel-pagarme-migrations');

        $this->publishes([
            __DIR__ . '/Controllers' => $this->app->basePath() . '/app/Http/Controllers',
        ], 'laravel-pagarme-controllers');

        $this->publishes([
            __DIR__ . '/Models' => $this->app->basePath() . '/app/Models',
        ], 'laravel-pagarme-models');
    }
}
