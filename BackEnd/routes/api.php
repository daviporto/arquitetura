<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobApplicationsController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\UserAcademicRecordsController;
use App\Http\Controllers\UserCompetenceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfessionalExperience;
use Illuminate\Support\Facades\Route;

Route::group(
    ['middleware' => 'api', 'namespace' => 'App\Http\Controllers'],
    function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', [JWTController::class, 'login']);
            Route::post('logout', [JWTController::class, 'logout'])->middleware('auth:api');
            Route::get('me', [JWTController::class, 'me'])->middleware('auth:api');
            Route::post('verify', [JWTController::class, 'verify'])->middleware('auth:api');
            Route::post('register', [JWTController::class, 'register']);
            Route::post('forgot-password/{userEmail}', [JWTController::class, 'forgotPassword']);
        });
    }
);

Route::group(
    [
        'namespace' => 'App\Http\Controllers',
        'prefix' => 'user'
    ],
    function () {
        Route::group(['prefix' => 'competence'], function () {
            Route::post('/', [UserCompetenceController::class, 'addCompetences']);
            Route::delete('/', [UserCompetenceController::class, 'deleteCompetences']);
        });

        Route::group(['prefix' => 'academic-records'], function () {
            Route::post('/', [UserAcademicRecordsController::class, 'addAcademicRecords']);
            Route::delete('/', [UserAcademicRecordsController::class, 'deleteAcademicRecords']);
        });

        Route::group(['prefix' => 'professional-experiences'], function () {
            Route::post('/', [UserProfessionalExperience::class, 'addProfessionalExperiences']);
            Route::delete('/', [UserProfessionalExperience::class, 'deleteProfessionalExperiences']);
        });

        Route::resource('/resume', ResumeController::class)
            ->only(['store', 'index', 'show', 'destroy'])
            ->middleware('auth:api');

        Route::group(['prefix' => '/resume'], function () {
            Route::group(['prefix' => '/{resume}'], function () {
                Route::patch('/alias', [ResumeController::class, 'patchAlias']);
                Route::patch('/file', [ResumeController::class, 'patchFile']);
                Route::get('/download', [ResumeController::class, 'download']);
            });
        });

        Route::get('', [UserController::class, 'index']);
        Route::get('/show/{user_id}', [UserController::class, 'show']);
        Route::put('', [UserController::class, 'update']);

        Route::group(['prefix' => 'profile-picture'], function () {
            Route::patch('', [UserController::class, 'updateProfilePicture']);
            Route::delete('', [UserController::class, 'deleteProfilePicture']);
        });
    }
)->middleware('api');


Route::group(
    [
        'namespace' => 'App\Http\Controllers',
    ],
    function () {
        Route::resource('/job', JobController::class)
            ->only(['store', 'destroy', 'update'])
            ->middleware('recruiter');

        Route::resource('/job', JobController::class)
            ->only(['index', 'show'])
            ->middleware('auth:api');

        Route::group(['prefix' => 'job/{job}'], function () {
            Route::resource('/application', JobApplicationsController::class)
                ->only(['store', 'destroy']);
        })->middleware('auth:api');
    }
);

Route::group([
    'namespace' => 'App\Http\Controllers',
    'prefix' => '/job-application'
], function () {
    Route::get('', [JobApplicationsController::class, 'index']);
    Route::patch('/{application}/status', [JobApplicationsController::class, 'updateStatus']);
})->middleware('auth:api');


Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => 'auth:api'
], function () {
    Route::resource('/company', CompanyController::class)
        ->only(['store', 'show', 'index', 'update']);
});
