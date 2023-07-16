<?php


Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Api\Institute'
], function ($router) {
    Route::get('get-course-all', 'CourseController@getAllCourse');
    Route::get('get-course/{course_id}', 'CourseController@getCourseById');
    Route::get('get-course-category', 'CourseCategoryController@getCourseCategory');
   
});

?>