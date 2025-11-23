<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to User Profile API',
        'version' => '1.0',
        'documentation' => url('/api/documentation'),
        'endpoints' => [
            'register' => 'POST /api/register',
            'login' => 'POST /api/login',
            'user' => 'GET /api/user (protected)',
            'update_profile' => 'POST /api/profile/update (protected)',
            'logout' => 'POST /api/logout (protected)',
        ]
    ]);
});
