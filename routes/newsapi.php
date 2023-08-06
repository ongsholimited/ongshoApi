<?php

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api\News',
    'prefix' => 'news'
], function ($router) {
    Route::resource('folder', 'FolderController')->middleware('auth:api');
    Route::resource('image', 'ImageController')->middleware('auth:api');
    Route::get('get-image-by-folder/{folder_id}', 'ImageController@getImageByFolderId')->middleware('auth:api');
    Route::get('get-posts', 'NewsController@getPost');
    Route::resource('post', 'NewsController');
    Route::get('category', 'CategoryController@getCategory');
    Route::get('get-post-by-category/{category_id}', 'NewsController@getPostByCat');
});

  