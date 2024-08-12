<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
    // Partager les erreurs de validation
    Inertia::share('errors', function () {
        return Session::get('errors')
            ? Session::get('errors')->getBag('default')->getMessages()
            : (object)[];
    });

    // Partager les messages flash (succès, erreur, etc.)
    Inertia::share('flash', function () {
        return [
            'success' => Session::get('success'),
            'error' => Session::get('error'),
            'failed' => Session::get('failed'), // Assurez-vous que `failed` est également dans les messages flash
        ];
    });

    // Partager les données utilisateur authentifié
    Inertia::share('user', function () {
        return auth()->check() ? auth()->user()->only(['id', 'name', 'email','IsAdmin','url']) : null;
    });
}

}
