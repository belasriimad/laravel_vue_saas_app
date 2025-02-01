<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\NegativeController;
use App\Http\Controllers\Admin\PositiveController;
use App\Http\Controllers\Admin\SubscriptionController;

//admin guest routes
Route::get('/', [AdminController::class,'login'])->name('admin.login');
Route::post('admin/auth', [AdminController::class,'auth'])->name('admin.auth');


Route::prefix('admin')->middleware('admin')->group(function() {
    Route::get('dashboard', [AdminController::class,'index'])->name('admin.index');
    Route::post('logout', [AdminController::class,'logout'])->name('admin.logout');
    //products routes
    Route::resource('products', ProductController::class, [
        'names' => [
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]
    ]);
    //positives routes
    Route::resource('positives', PositiveController::class, [
        'names' => [
            'index' => 'admin.positives.index',
            'create' => 'admin.positives.create',
            'store' => 'admin.positives.store',
            'edit' => 'admin.positives.edit',
            'update' => 'admin.positives.update',
            'destroy' => 'admin.positives.destroy',
        ]
    ]);
    //negatives routes
    Route::resource('negatives', NegativeController::class, [
        'names' => [
            'index' => 'admin.negatives.index',
            'create' => 'admin.negatives.create',
            'store' => 'admin.negatives.store',
            'edit' => 'admin.negatives.edit',
            'update' => 'admin.negatives.update',
            'destroy' => 'admin.negatives.destroy',
        ]
    ]);
    //plans routes
    Route::resource('plans', PlanController::class, [
        'names' => [
            'index' => 'admin.plans.index',
            'create' => 'admin.plans.create',
            'store' => 'admin.plans.store',
            'edit' => 'admin.plans.edit',
            'update' => 'admin.plans.update',
            'destroy' => 'admin.plans.destroy',
        ]
    ]);
    //subscriptions routes
    Route::get('subscriptions', [SubscriptionController::class,'index'])->name('admin.subscriptions.index');
    //histories routes
    Route::get('histories', [HistoryController::class,'index'])->name('admin.histories.index');
    Route::delete('history/delete/{history}', [HistoryController::class,'destroy'])->name('admin.histories.destroy');
    //users routes
    Route::get('users', [UserController::class,'index'])->name('admin.users.index');
    Route::delete('user/delete/{user}', [UserController::class,'destroy'])->name('admin.users.destroy');
});