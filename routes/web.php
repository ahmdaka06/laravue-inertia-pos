<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;

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

// Route::get('/', function () {
//     return view('welcome');
// });


// route home
Route::get('/', function () {
    return \Inertia\Inertia::render('Auth/Login');
})->middleware('guest');

// prefix "apps"
Route::prefix('apps')->group(function() {

    // middleware "auth"
    Route::group(['middleware' => ['auth']], function () {

        // route dashboard
        Route::get('dashboard', DashboardController::class)
            ->name('apps.dashboard');

        // route permissions
        Route::get('/permissions', PermissionController::class)
            ->name('apps.permissions.index')
            ->middleware('permission:permissions.index');

        //route resource roles
        Route::resource('/roles', RoleController::class, ['as' => 'apps'])
            ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

    });
});
