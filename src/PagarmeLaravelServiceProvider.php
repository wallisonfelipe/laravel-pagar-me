<?php

namespace Felipe\LaravelPagarMe;

use Illuminate\Support\ServiceProvider;

class PagarmeLaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations' => dirname(__FILE__) . "../../database/migrations",
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/Controllers/' => dirname(__FILE__) . "../../database/migrations",
        ], 'controllers');
    }
}
