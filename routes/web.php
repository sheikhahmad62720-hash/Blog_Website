<?php

use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/feed', [SeoController::class, 'feed']);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
