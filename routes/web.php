<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Admin\CrudMaker\CrudMakerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
    return view('welcome');
});

Route::get('/test',[TestController::class,'test']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/crud_maker/{type}',[CrudMakerController::class,'call'])->name('crud_maker');

Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');
Route::post('/login',[LoginController::class,'login'])->name('login');
Route::post('/search-user',[App\Http\Controllers\Admin\UserController::class,'searchUser']);
// institute

Route::group([
    "prefix"=>"institute",
    'namespace' => 'App\Http\Controllers\Admin\Institute',
],function () {
    Route::get('/dashboard','InstituteDashboardController@homePage');
    Route::resource('/category','CategoryController');
    Route::resource('/course','CourseController');
    Route::resource('/chapter','CourseChapterController');
    Route::resource('/content','ContentController');
    Route::post('/get-category','CategoryController@getCategory');
    Route::post('/get-user-data','UserController@getUserData');
    Route::post('/get-course','CourseController@getCourse');
    Route::post('/get-chapter','CourseChapterController@getChapter');
});

Route::group([
    "prefix"=>"institute",
    'namespace' => 'App\Http\Controllers\Institute',
],function () {
    Route::post('/get-user-data','UserController@getUserData');
});

Route::group([
    "prefix"=>"news",
    "as"=>"news.",
    'namespace' => 'App\Http\Controllers\Admin\News',
],function () {
    Route::get('/dashboard','NewsDashboardController@homePage');
    Route::resource('/category','CategoryController');
    
    Route::resource('/badge','BadgeController');
    Route::post('/get-category','CategoryController@getCategory');
    Route::resource('/post','PostController');
    Route::get('/post-list','PostController@postList');
    Route::resource('/folder','FolderController');
    Route::get('/get-folder','FolderController@getFolder');
    Route::resource('/images','ImagesController');
    Route::get('/get-images/{folder}','ImagesController@getImages');
    Route::resource('/menu','MenuController');
    Route::resource('/section','SectionController');
    Route::get('/meta_keyword','MetaKeywordController@index')->name('meta_keyword');
    Route::get('/news_setting','NewsSettingController@index')->name('news_setting');
});

Route::group([
    "prefix"=>"setting",
    'namespace' => 'App\Http\Controllers\Admin',
],function () {
    Route::resource('/role','RoleController');
});

Route::group([
    "prefix"=>"sms",
    'namespace' => 'App\Http\Controllers\Admin\Sms',
],function () {
    Route::get('/dashbaord','SmsDashboardController@index');
    Route::get('/sms_api','SmsApiSelectionController@index')->name('sms.smsapi');
});