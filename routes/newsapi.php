<?php

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api\News'
], function ($router) {
    Route::resource('folder', 'FolderController')->middleware('auth:api');
    Route::resource('image', 'ImageController')->middleware('auth:api');
    Route::get('get-image-by-folder/{folder_id}', 'ImageController@getImageByFolderId')->middleware('auth:api');
   
});