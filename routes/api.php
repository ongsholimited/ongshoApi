<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// Route::group([

//     'middleware' => 'api',
//     'namespace' => 'App\Http\Controllers',
//     'prefix' => 'auth'

// ], function ($router) {
//     Route::post('signup', 'Profile\UserController@store');
//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::get('me', 'AuthController@me');
//     Route::get('test', 'TestController@test');
// });
Route::group([
    'middleware' => ['api'],
    'namespace' => 'App\Http\Controllers\Api'
], function ($router) {
    Route::get('check-username/{username}', 'AuthController@checkUsername');
    Route::post('signin', 'AuthController@login');
    Route::post('signup', 'AuthController@register');
    Route::post('logout', 'AuthController@logout')->middleware('auth:api');
    Route::get('auth-check', 'AuthController@AuthCheck')->middleware('auth:api');
});

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api'
], function ($router) {
    Route::post('testapi', 'CustomApiTableController@createApi');
    Route::get('testapi/url-list', 'CustomApiTableController@getUrlList');
    Route::post('testapi/edit/{url}', 'CustomApiTableController@updateApi');
    Route::get('testapi/{url}', 'CustomApiTableController@getApi');
});

// otp module
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api'
], function ($router) {
    Route::post('send_otp', 'EmailVerificationController@setOtp')->middleware('auth:api');
    Route::post('send_otp_email/{otp_type}', 'EmailVerificationController@sendEmailOtp');
    Route::post('varify_email', 'EmailVerificationController@varifyEmail')->middleware('auth:api');
    Route::post('check_otp/{type}', 'OtpController@checkOtp');
    Route::post('change-password', 'ForgotPasswordController@changePassword');
});

// user profile 
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api'
], function ($router) {
    Route::post('profile-update', 'ProfileController@profileUpdate');
    Route::get('get-profile', 'ProfileController@getProfile');
    Route::resource('social-account','SocialsController')->middleware('auth:api');
});

include('newsapi.php');
include('institute.php');





Route::group([
    'namespace' => 'App\Http\Controllers\Profile',
    'prefix' => 'account'
], function ($router) {
    Route::post('change_name', 'UserController@changeName')->middleware('auth:api');
    Route::post('change_photo', 'UserController@changePhoto')->middleware('auth:api');
    Route::post('change_birthday', 'UserController@changeBirthday')->middleware('auth:api');
    Route::post('send_otp', 'OtpController@setOtp')->middleware('auth:api');
    Route::post('send_otp_email', 'OtpController@emailOtp')->middleware('auth:api');
    Route::post('varify_email', 'OtpController@varifyEmail')->middleware('auth:api');
});

Route::group([
    'namespace' => 'App\Http\Controllers\Institute',
    'prefix' => 'institute'
], function ($router) {
    Route::post('user/edit', 'UserController@updateOrCreate')->middleware('auth:api');
    Route::get('user', 'UserController@getUser')->middleware('auth:api');
    Route::get('get-category', 'CategoryController@getCategory');
});