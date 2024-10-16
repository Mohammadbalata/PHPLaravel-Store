<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProductsController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth','auth.type:admin,super-admin'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard',
    // 'namespace' => '../../',
], function () {

    Route::get('/profile',[ProfileController::class,'edit'])
    ->name('profile.edit');
    

    Route::patch('/profile',[ProfileController::class,'update'])
    ->name('profile.update');


    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/categories/trash',[CategoriesController::class,'trash'])
    ->name('categories.trash');

    Route::put('/categories/{category}/restore',[CategoriesController::class,'restore'])
    ->name('categories.restore');

    Route::delete('/categories/{category}/force-delete',[CategoriesController::class,'forceDelete'])
    ->name('categories.force-delete');

    


    Route::resource('/categories', CategoriesController::class);

    Route::resource('/products', ProductsController::class);
});


