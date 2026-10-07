<?php

declare(strict_types=1);

use App\Http\Controllers\Brand\ShowBrandsController;
use App\Http\Controllers\Contact\ShowContactMailPreviewController;
use App\Http\Controllers\Home\ShowHomeController;
use App\Http\Controllers\Partner\ShowTrustedCircleController;
use App\Http\Controllers\Seo\ShowRobotsController;
use App\Http\Controllers\Seo\ShowSitemapController;
use App\Http\Controllers\Treatment\ShowTreatmentMenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHomeController::class)->name('home');
Route::get('/nos-soins', ShowTreatmentMenuController::class)->name('treatments');
Route::get('/nos-marques-partenaires', ShowBrandsController::class)->name('brands');
Route::view('/notre-histoire', 'web.story.index')->name('story');
Route::get('/cercle-de-confiance', ShowTrustedCircleController::class)->name('trusted-circle');
Route::view('/contact', 'web.contact.index')->name('contact');
Route::get('/apercu-email-contact', ShowContactMailPreviewController::class)->name('contact.mail-preview');
Route::view('/mentions-legales', 'web.legal.notice.index')->name('legal.notice');
Route::view('/politique-de-confidentialite', 'web.legal.privacy.index')->name('legal.privacy');

Route::get('/robots.txt', ShowRobotsController::class)->name('robots');
Route::get('/sitemap.xml', ShowSitemapController::class)->name('sitemap');
