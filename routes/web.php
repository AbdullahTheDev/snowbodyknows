<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WishlistViewerController;
use App\Http\Controllers\GrantedWishController;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/wishlists/{wishlist:invite_code}/join', [WishlistViewerController::class, 'create'])->name('wishlists.viewers.create');
Route::post('/wishlists/{wishlist:invite_code}/join', [WishlistViewerController::class, 'store'])->name('wishlists.viewers.store')->middleware('auth');

Route::middleware('auth')->prefix('/app')->group(function () {
    Route::get('/', AppController::class)->name('app');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wishlists', [WishlistController::class, 'index'])->name('wishlists.index');
    Route::get('/wishlists/create', [WishlistController::class, 'create'])->name('wishlists.create')->can('create', Wishlist::class);
    Route::post('/wishlists', [WishlistController::class, 'store'])->name('wishlists.store')->can('create', Wishlist::class);
    Route::get('/wishlists/{wishlist}', [WishlistController::class, 'show'])->name('wishlists.show')->can('view', 'wishlist');
    Route::get('/wishlists/{wishlist}/edit', [WishlistController::class, 'edit'])->name('wishlists.edit')->can('update', 'wishlist');
    Route::patch('/wishlists/{wishlist}', [WishlistController::class, 'update'])->name('wishlists.update')->can('update', 'wishlist');
    Route::delete('/wishlists/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlists.destroy')->can('delete', 'wishlist');

    Route::delete('/wishlists/{wishlist}/users/{user}', [WishlistViewerController::class, 'destroy'])->name('wishlists.viewers.destroy')->can('kick', ['wishlist', 'user']);

    Route::get('/wishlists/{wishlist}/wish', [WishController::class, 'create'])->name('wishes.create')->can('update', 'wishlist');
    Route::post('/wishlists/{wishlist}/wish', [WishController::class, 'store'])->name('wishes.store')->can('update', 'wishlist');
    Route::get('/wishlists/{wishlist}/wishes/{wish}/edit', [WishController::class, 'edit'])->name('wishes.edit')->can('update', 'wish');
    Route::patch('/wishlists/{wishlist}/wishes/{wish}', [WishController::class, 'update'])->name('wishes.update')->can('update', 'wish');
    Route::delete('/wishlists/{wishlist}/wishes/{wish}', [WishController::class, 'destroy'])->name('wishes.destroy')->can('delete', 'wish');

    Route::post('/wishes/{wish}/grant', [GrantedWishController::class, 'store'])->name('wishes.grants.store')->can('grant', 'wish');
    Route::delete('/wishes/{wish}/grant', [GrantedWishController::class, 'destroy'])->name('wishes.grants.destroy')->can('ungrant', 'wish');
});

require __DIR__.'/auth.php';
