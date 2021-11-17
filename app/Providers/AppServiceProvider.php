<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data, $message = 'success', $code = 200, $pagination = []) {
            return Response::json([
                'meta' => [
                    'code' => $code,
                    'status' => $message
                ],
                'data' => $data,
                'pagination' => $pagination,
            ]);
        });
        Response::macro('error', function ($code, $message = 'error', $data = []) {
            return Response::json([
                'meta' => [
                    'code' => $code,
                    'status' => $message
                ],
                'data' => $data
            ], $code);
        });
    }
}
