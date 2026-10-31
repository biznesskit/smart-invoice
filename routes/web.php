<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/run-scheduler', function () {
//     // if( $request->input('token') !== env('SCHEDULER_TOKEN') )
//     //     return response()->json(['message' => 'Invalid token'], 401);
//     // Artisan::call('schedule:run --no-interaction');
//     return response()->json(['message' => 'Scheduler executed']);
// });

// Route::get('health-check',function(){
//     return "Healthy";
// });

