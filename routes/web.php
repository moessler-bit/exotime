<?php

use App\Http\Controllers\BanController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\ServiceController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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

  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
    #
    'categorie_count' => Category::count(),
    'user_count' => User::count(),
    'user_online' => DB::table('sessions') ->whereNotNull('user_id') ->distinct() ->count('user_id'),
  ]);
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
  'banned.redirect',
  'ban.ban',
])->group(function () {

  Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
  })->name('dashboard');

  Route::resource('services', ServiceController::class);
  Route::resource('demands', DemandController::class);

  Route::get('banned', BanController::class);
});

Route::post('test/{test}', function($test){
    return $test;
})->name('test');
