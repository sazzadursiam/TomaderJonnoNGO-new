<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StoryController;
use App\Http\Middleware\SetLocale;
use App\Support\Localization;
use Illuminate\Support\Facades\Route;

$publicPages = function (): void {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/team', [PageController::class, 'team'])->name('team');
    Route::get('/donate', [PageController::class, 'donate'])->name('donate');

    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');

    Route::get('/projects', [StoryController::class, 'index'])->name('stories.index');
    Route::get('/projects/{story:slug}', [StoryController::class, 'show'])->name('stories.show');

    Route::get('/contact', [ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('contact.store');
};

Route::middleware(SetLocale::class.':'.Localization::DEFAULT_LOCALE)->group($publicPages);

foreach (array_keys(Localization::LOCALES) as $locale) {
    if ($locale === Localization::DEFAULT_LOCALE) {
        continue;
    }

    Route::prefix($locale)
        ->name("{$locale}.")
        ->middleware(SetLocale::class.':'.$locale)
        ->group($publicPages);
}
