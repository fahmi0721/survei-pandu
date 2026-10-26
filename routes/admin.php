<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SurveyRespondentController;
use App\Http\Controllers\Admin\SurveyResultController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\SurveyQuestionController;
use App\Http\Controllers\Admin\SurveySectionController;
use App\Http\Controllers\Admin\WorkAreaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;

// Dashboard — Admin dan HRD
Route::get('/dashboard', [
    DashboardController::class,
    'index',
])->middleware('can:access-dashboard')->name('dashboard');
Route::post('/logout', [LoginController::class, 'logout'])
            ->name('logout');

// Kelola Responden — Admin dan HRD
Route::middleware('can:view-respondents')
    ->prefix('respondents')
    ->name('respondents.')
    ->group(function () {
        Route::get('/', [SurveyRespondentController::class, 'index'])
            ->name('index');

        Route::get('/data', [SurveyRespondentController::class, 'data'])
            ->name('data');

        Route::get('/{response}', [SurveyRespondentController::class, 'show'])
            ->name('show');
    });

// Hasil Survei — Admin dan HRD
Route::middleware('can:view-survey-results')
    ->prefix('survey-results')
    ->name('survey-results.')
    ->group(function () {
        Route::get('/', [SurveyResultController::class, 'index'])
            ->name('index');

        Route::get('/data', [SurveyResultController::class, 'data'])
            ->name('data');
    });

// KHUSUS ADMIN — pertahankan seluruh route master
Route::middleware('can:manage-surveys')
    ->group(function () {
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
    });

Route::middleware('can:manage-work-areas')
    ->group(function () {
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
    });

// Manajemen User — khusus Admin
Route::middleware('can:manage-users')
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'data'])->name('data');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/reset-password', [
            UserController::class,
            'resetPassword'
        ])->name('reset-password');
    });