<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\SurveySectionController;
use App\Http\Controllers\Admin\SurveyQuestionController;
use App\Http\Controllers\Survey\PublicSurveyController;
use App\Http\Controllers\Admin\WorkAreaController;
use App\Http\Controllers\Admin\SurveyRespondentController;

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
    ->middleware(['auth', 'admin.role'])
    ->group(function () {
        Route::view('/dashboard', 'admin.dashboard')
            ->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('logout');

        Route::prefix('surveys')
        ->name('surveys.')
        ->group(function () {

            Route::get('/', [SurveyController::class, 'index'])
                ->name('index');
            Route::get('/data', [SurveyController::class, 'data'])
                ->name('data');
            Route::post('/', [SurveyController::class, 'store'])
                ->name('store');
            Route::get('/{survey}', [SurveyController::class, 'show'])
                ->name('show');
            Route::put('/{survey}', [SurveyController::class, 'update'])
                ->name('update');
            Route::delete('/{survey}', [SurveyController::class, 'destroy'])
                ->name('destroy');

        });

        Route::prefix('surveys/{survey}/sections')
        ->name('surveys.sections.')
        ->group(function () {

            Route::get('/', [SurveySectionController::class, 'index'])
                ->name('index');

            Route::get('/data', [SurveySectionController::class, 'data'])
                ->name('data');

            Route::post('/', [SurveySectionController::class, 'store'])
                ->name('store');

            Route::put('/reorder', [SurveySectionController::class, 'reorder'])
                ->name('reorder');

            Route::get('/{section}', [SurveySectionController::class, 'show'])
                ->name('show');

            Route::put('/{section}', [SurveySectionController::class, 'update'])
                ->name('update');

            Route::delete('/{section}', [SurveySectionController::class, 'destroy'])
                    ->name('destroy');
        });

        Route::prefix('surveys/{survey}/sections/{section}/questions')
        ->name('surveys.sections.questions.')
        ->group(function () {

            Route::get('/', [SurveyQuestionController::class, 'index'])
                ->name('index');

            Route::get('/data', [SurveyQuestionController::class, 'data'])
                ->name('data');

            Route::post('/', [SurveyQuestionController::class, 'store'])
                ->name('store');

            Route::put('/reorder', [SurveyQuestionController::class, 'reorder'])
                ->name('reorder');

            Route::get('/{question}', [SurveyQuestionController::class, 'show'])
                ->name('show');

            Route::put('/{question}', [SurveyQuestionController::class, 'update'])
                ->name('update');

            Route::delete('/{question}', [SurveyQuestionController::class, 'destroy'])
                ->name('destroy');
        });

        Route::prefix('work-areas')
        ->name('work-areas.')
        ->group(function () {

            Route::get('/', [WorkAreaController::class, 'index'])
                ->name('index');

            Route::get('/data', [WorkAreaController::class, 'data'])
                ->name('data');

            Route::post('/', [WorkAreaController::class, 'store'])
                ->name('store');

            Route::get('/{workArea}', [WorkAreaController::class, 'show'])
                ->name('show');

            Route::put('/{workArea}', [WorkAreaController::class, 'update'])
                ->name('update');

            Route::delete('/{workArea}', [WorkAreaController::class, 'destroy'])
                ->name('destroy');
        });

        Route::prefix('respondents')
        ->name('respondents.')
        ->group(function () {

            Route::get('/', [
                SurveyRespondentController::class,
                'index'
            ])->name('index');

            Route::get('/data', [
                SurveyRespondentController::class,
                'data'
            ])->name('data');

            Route::get('/{response}', [
                SurveyRespondentController::class,
                'show'
            ])->name('show');
        });

});


Route::get('/tes', function () {
    return view('tes');
});
