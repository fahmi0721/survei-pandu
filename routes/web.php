<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Survey\PublicSurveyController;

Route::get('/', [
    PublicSurveyController::class,
    'index'
])->name('survey.public.index');
Route::get('/selesai', [
    PublicSurveyController::class,
    'success'
])->name('survey.public.success');
Route::get('/survei/{survey:slug}', [
    PublicSurveyController::class,
    'show'
])->name('survey.public.show');
Route::post('/survei/{survey:slug}/submit', [
    PublicSurveyController::class,
    'submit'
])->middleware('throttle:10,1')->name('survey.public.submit');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth','auth.session', 'admin.role'])
    ->group(base_path('routes/admin.php'));

