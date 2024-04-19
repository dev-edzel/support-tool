<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
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
        Response::macro("success", function ($message, $data = null, $statusCode = null) {
            return response()->json([
                'status' => 1,
                'message' => $message,
                'data' => $data ?? [],
            ], $statusCode ?? 200);
        });

        Response::macro("failed", function ($message, $data = null, $statusCode = null) {
            return response()->json([
                'status' => 0,
                'message' => $message,
                'data' => $data ?? [],
            ], $statusCode ?? 400);
        });
    }
}
