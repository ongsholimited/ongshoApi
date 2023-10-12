<?php
    

Route::group([
    'middleware'=>['apiCheck'],
],function(){


    Route::get('test',[ App\Http\Controllers\Api\TestController::class,'test'])->middleware('api');
    Route::group([
        'middleware' => ['api'],
        'namespace' => 'App\Http\Controllers\Api\News',
        'prefix' => 'news'
        
    ], function ($router) {
        Route::get('category', 'CategoryController@getCategory');
        // Route::get('get-post-by-category/{category_id}', 'NewsController@getPostByCat');
        Route::get('get-menu', 'MenuController@getMenu');
        Route::resource('folder', 'FolderController')->middleware('auth:api');
        Route::resource('image', 'ImageController')->middleware('auth:api');
        Route::post('get-images', 'ImageController@getImage')->middleware('auth:api');
        Route::get('get-image-by-folder/{folder_id}', 'ImageController@getImageByFolderId')->middleware('auth:api');
        Route::post('get-posts', 'NewsController@getPost');
    
        Route::get('get-posts/section', 'NewsController@getSection');
        Route::post('get-posts/user/', 'NewsController@getPostByUser')->middleware('auth:api');
        Route::Post('author/{username}', 'NewsController@getAuthorProfile');
        Route::post('get-posts/{category_slug}', 'NewsController@getPostByCat');
        Route::post('get-pin-posts', 'NewsController@getPinPost');
        Route::resource('post', 'NewsController');
        Route::get('get-post-types', 'CategoryController@getPostType');
        // Route::get('get-post/{limit}/{offset}', 'NewsController@getPost');
        Route::get('get-section', 'SectionController@getSection');
        Route::get('/dashboard', 'DashboardController@index');
        Route::get('/check-slug/{slug}/{post_id?}', 'SlugController@checkSlug');
        Route::get('post-preview/{post_slug}', 'NewsController@getPostPreview')->middleware('auth:api');
        Route::get('post-preview-edit/{id}', 'NewsController@getPostPreviewEdit')->middleware('auth:api');
        Route::get('/view-count/{post_id}', 'ViewPostController@viewCount');
        Route::get('meta/{slug}', 'MetaKeywordController@getMeta');
        Route::get('/{slug}', 'NewsController@getPostBySlug');
        
    });

});