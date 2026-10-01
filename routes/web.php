<?php

declare(strict_types=1);

use App\Http\Controllers\Seo\ShowRobotsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'web.home.index')->name('home');

Route::get('/robots.txt', ShowRobotsController::class)->name('robots');
