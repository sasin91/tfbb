<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('recordings', 'API\RecordingController');
    Route::name('recordings.videos.store')->post('recordings/{recording}/videos', 'API\Recordings\RecordingVideoController@store');
    
    Route::apiResource('offers', 'API\OfferController');

    Route::apiResource('profiles', 'API\ProfileController');
    Route::name('profiles.lock')->post('profiles/{profile}/lock', 'API\Profile\LockProfileController@store');
    Route::name('profiles.unlock')->post('profiles/{profile}/unlock', 'API\Profile\UnlockProfileController@store');
    Route::name('profiles.publish')->post('profiles/{profile}/publish', 'API\Profile\PublishProfileController@store');
    Route::name('profiles.unpublish')->post('profiles/{profile}/unpublish', 'API\Profile\UnpublishProfileController@store');
    Route::name('profiles.photos.store')->post('profiles/{profile}/photos', 'API\Profile\ProfilePhotosController@store');
    Route::name('profiles.videos.store')->post('profiles/{profile}/videos', 'API\Profile\ProfileVideosController@store');

    Route::get('boards', 'API\BoardController@index')->name('boards.index');
    Route::post('boards', 'API\BoardController@store')->name('boards.store');
    Route::delete('boards/{board}', 'API\BoardController@destroy')->name('boards.destroy');
    Route::match(['PUT', 'PATCH'], 'boards/{board}', 'API\BoardController@update')->name('boards.update');

    Route::post('boards/{board}/draft', 'API\DraftBoardController@__invoke')->name('boards.draft');
    Route::post('boards/{board}/publish', 'API\PublishBoardController@__invoke')->name('boards.publish');

    Route::post('boards/{board}/threads', 'API\Board\BoardThreadController@store')->name('boards.threads.store');
    Route::get('boards/{board}/threads', 'API\Board\BoardThreadController@index')->name('boards.threads.index');

    Route::match(['PUT', 'PATCH'], 'threads/{thread}', 'API\ThreadController@update')->name('threads.update');
    Route::delete('threads/{thread}', 'API\ThreadController@destroy')->name('threads.destroy');

    Route::post('threads/{thread}/lock', 'API\Thread\LockThreadController@store')->name('threads.lock');
    Route::post('threads/{thread}/unlock', 'API\Thread\UnlockThreadController@store')->name('threads.unlock');
    
    Route::post('threads/{thread}/photos', 'API\Thread\ThreadPhotoController@store')->name('threads.photos.store');

    Route::post('threads/{thread}/replies', 'API\Thread\ThreadRepliesController@store')->name('threads.replies.store');
    Route::get('threads/{thread}/replies', 'API\Thread\ThreadRepliesController@index')->name('threads.replies.index');

    Route::match(['PUT', 'PATCH'], 'replies/{reply}', 'API\ReplyController@update')->name('replies.update');
    Route::delete('replies/{reply}', 'API\ReplyController@destroy')->name('replies.destroy');

    Route::get('workouts', 'API\WorkoutController@index')->name('workouts.index');
   
    Route::post('workouts', 'API\WorkoutController@store')->name('workouts.store');
    Route::get('workouts/{workout}', 'API\WorkoutController@show')->name('workouts.show');
    Route::match(['PUT', 'PATCH'], 'workouts/{workout}', 'API\WorkoutController@update')->name('workouts.update');
    Route::delete('workouts/{workout}', 'API\WorkoutController@destroy')->name('workouts.destroy');
    Route::post('workouts/{workout}/photos', 'API\Workout\WorkoutPhotoController@store')->name('workouts.photos.store');
    Route::post('workouts/{workout}/videos', 'API\Workout\WorkoutVideoController@store')->name('workouts.videos.store');
    Route::post('workouts/{workout}/documents', 'API\Workout\WorkoutDocumentController@store')->name('workouts.documents.store');
    Route::post('workouts/{workout}/select-as-wotm', 'API\Workout\WorkoutOfTheMonthController@store')->name('workouts.wotm.store');
    Route::post('workouts/{workout}/enroll', 'API\Workout\WorkoutEnrollmentController@store')->name('workouts.enrollment.store');
    
    Route::apiResource('meals', 'API\MealController');
    Route::post('meals/{meal}/foods', 'API\Meal\MealFoodsController@store')->name('meals.foods.store');

    Route::apiResource('diets', 'API\DietController');
    Route::get('diets/{diet}/files', 'API\Diet\DietFilesController@index')->name('diets.files.index');
    Route::post('diets/{diet}/files', 'API\Diet\DietFilesController@store')->name('diets.files.store');
    Route::post('diets/{diet}/photos', 'API\Diet\DietPhotoController@store')->name('diets.photos.store');
    Route::post('diets/{diet}/videos', 'API\Diet\DietVideoController@store')->name('diets.videos.store');
    Route::post('diets/{diet}/documents', 'API\Diet\DietDocumentController@store')->name('diets.documents.store');
    Route::post('diets/{diet}/meals', 'API\Diet\AttachMealController@store')->name('diets.meals.store');
    Route::delete('diets/{diet}/meals', 'API\Diet\DetachMealController@destroy')->name('diets.meals.destroy');

    Route::middleware(['dev'])->group(function () {
        Route::get('kiosk/search/workouts', 'API\Kiosk\Search\WorkoutSearchController@show')->name('kiosk::search.workouts.show');

        Route::get('ndb/search', 'API\USDA\NDBSearchController@show')->name('ndb.search.show');
    });
});
