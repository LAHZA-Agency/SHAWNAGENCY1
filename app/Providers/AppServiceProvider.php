<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Demande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        Gate::define('is-admin', function ($user) {
        return in_array($user->role, ['admin', 'bookeuse']) && $user->status === 'active';
        });

        Gate::define('is-active', function ($user) {
            return $user->status === 'active';
        });

        Gate::define('is-accueillant', function ($user) {
            return $user->role === 'accueillant' && $user->status === 'active';
        });

        Gate::define('is-photographe', function ($user) {
            return $user->role === 'photographe' && $user->status === 'active';
        });

        Gate::define('is-mensurations', function ($user) {
            return $user->role === 'styliste' && $user->status === 'active';
        });


        Gate::define('is-jury', function ($user) {
            return $user->role === 'jury' && $user->status === 'active';
        });

  View::composer('*', function ($view) {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin voit uniquement les non vus par lui
            $unseenDemandesCount = Demande::where('seen_by_admin', 0)->count();
        } else {
            // bookeuse voit status = 0
            $unseenDemandesCount = Demande::where('status', 0)->count();
        }

        $view->with('unseenDemandesCount', $unseenDemandesCount);
    }
});
    }


}
