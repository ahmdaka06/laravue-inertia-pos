<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

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

        //route resource users
        Route::resource('/users', UserController::class, ['as' => 'apps'])
            ->middleware('permission:users.index|users.create|users.edit|users.delete');

        //route resource categories
        Route::resource('/categories', CategoryController::class, ['as' => 'apps'])
            ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');

        //route resource products
        Route::resource('/products', ProductContoller::class, ['as' => 'apps'])
            ->middleware('permission:products.index|products.create|products.edit|products.delete');

        //route resource customers
        Route::resource('/customers', CustomerController::class, ['as' => 'apps'])
            ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');

        Route::group(['prefix' => 'transactions'], function() {
            //route transaction
            Route::get('/', [TransactionController::class, 'index'])->name('apps.transactions.index');

            //route transaction searchProduct
            Route::post('/searchProduct', [TransactionController::class, 'searchProduct'])->name('apps.transactions.searchProduct');

            //route transaction addToCart
            Route::post('/addToCart', [TransactionController::class, 'addToCart'])->name('apps.transactions.addToCart');

            //route transaction destroyCart
            Route::post('/destroyCart', [TransactionController::class, 'destroyCart'])->name('apps.transactions.destroyCart');

            //route transaction store
            Route::post('/store', [TransactionController::class, 'store'])->name('apps.transactions.store');

            //route transaction print
            Route::get('/print', [TransactionController::class, 'print'])->name('apps.transactions.print');
        });
    });
});
