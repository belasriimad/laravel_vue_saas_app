<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\NegativeController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PositiveController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/auth', [AdminController::class, 'auth'])->name('admin.auth');

Route::prefix('admin')->middleware('admin')->group(function() {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.index');
    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
    //product's routes
    Route::resource('products', ProductController::class, [
        'names' => [
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'update' => 'admin.products.update',
            'edit' => 'admin.products.edit',
            'destroy' => 'admin.products.destroy',
        ]
    ]);
    //positive's routes
    Route::resource('positives', PositiveController::class, [
        'names' => [
            'index' => 'admin.positives.index',
            'create' => 'admin.positives.create',
            'store' => 'admin.positives.store',
            'update' => 'admin.positives.update',
            'edit' => 'admin.positives.edit',
            'destroy' => 'admin.positives.destroy',
        ]
    ]);
    //negative's routes
    Route::resource('negatives', NegativeController::class, [
        'names' => [
            'index' => 'admin.negatives.index',
            'create' => 'admin.negatives.create',
            'store' => 'admin.negatives.store',
            'update' => 'admin.negatives.update',
            'edit' => 'admin.negatives.edit',
            'destroy' => 'admin.negatives.destroy',
        ]
    ]);
    //plan's routes
    Route::resource('plans', PlanController::class, [
        'names' => [
            'index' => 'admin.plans.index',
            'create' => 'admin.plans.create',
            'store' => 'admin.plans.store',
            'update' => 'admin.plans.update',
            'edit' => 'admin.plans.edit',
            'destroy' => 'admin.plans.destroy',
        ]
    ]);
    //user's routes
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('user/{user}/delete', [UserController::class, 'destroy'])->name('admin.users.destroy');
    //history's routes
    Route::get('histories', [HistoryController::class, 'index'])->name('admin.histories.index');
    Route::delete('history/{history}/delete', [HistoryController::class, 'destroy'])->name('admin.histories.destroy');
    //subscription's routes
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('admin.subscriptions.index');
});