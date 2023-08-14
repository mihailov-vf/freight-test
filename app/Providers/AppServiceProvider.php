<?php

namespace App\Providers;

use FreteRapido\Credentials;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Credentials::class, fn (Application $app) => new Credentials(
            env('CREDENCIAIS_CNPJ'),
            env('CREDENCIAIS_TOKEN'),
            env('CREDENCIAIS_CODIGO_PLATAFORMA'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
