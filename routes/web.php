<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

$pageResponse = fn (string $view) => response(File::get(resource_path("views/{$view}.blade.php")), 200)
    ->header('Content-Type', 'text/html; charset=UTF-8');

Route::get('/', fn () => $pageResponse('home'));
Route::get('/login', fn () => $pageResponse('login'))->name('login');
Route::get('/register', fn () => $pageResponse('register'));
Route::get('/dashboard', fn () => $pageResponse('dashboard'));
Route::get('/collector/dashboard', fn () => $pageResponse('collector-dashboard'));
Route::get('/scan-waste', fn () => $pageResponse('scan-waste'));
Route::get('/tracking', fn () => $pageResponse('tracking'));
Route::get('/pickups', fn () => $pageResponse('pickups'));
Route::get('/rewards', fn () => $pageResponse('rewards'));
Route::get('/profile', fn () => $pageResponse('profile'));
Route::get('/migrate', function () {
    \Artisan::call('migrate --force');
    return "Migrated!";
});