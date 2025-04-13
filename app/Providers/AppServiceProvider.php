<?php

namespace App\Providers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $email = session()->get('u_email');

            if ($email) {  // ✅ Check if email exists in session before caching
                // Cache the dashboard data for 10 minutes
                $dashboard = Cache::remember("dashboard_{$email}", 600, function () use ($email) {
                    return User::where('u_email', $email)->get();
                });

                $view->with('dashboard', $dashboard);

                // Share the user ID globally, caching it as well
                $id = Cache::remember("user_id_{$email}", 600, function () {
                    return Auth::check() ? Auth::id() : null;
                });

                View::share('id', $id);
            } else {
                // If session email is null, send empty data
                $view->with('dashboard', collect([]));  // Empty collection
                View::share('id', null);
            }
        });
        DB::statement("SET time_zone = '+05:30'"); // For Asia/Kolkata
    }
}
