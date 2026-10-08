<?php


// Route::prefix('calendar')->group(function () {
//     Route::get('/', 'CalendarController@index')->name('calendar_show')->middleware('RoutePermissionCheck:calendar_show');
//     Route::post('/', 'CalendarController@store')->name('calendar_store');
//     Route::post('/update', 'CalendarController@update')->name('calendar_update');
//     Route::get('/delete/{id}', 'CalendarController@destroy')->name('calendar_delete');
// });

Route::group(['prefix' => 'communicate', 'middleware' => ['auth']], function () {
    //Event

    Route::get('event', 'EventController@index')->name('event')->middleware('RoutePermissionCheck:event');
    Route::get('add-event', 'EventController@create')->name('event_create')->middleware('RoutePermissionCheck:event_create');
    Route::post('event', 'EventController@store')->name('event-store')->middleware('RoutePermissionCheck:event_create');
    Route::get('event/{id}', 'EventController@edit')->name('event-edit')->middleware('RoutePermissionCheck:event-edit');
    Route::put('event/{id}', 'EventController@update')->name('event-update')->middleware('RoutePermissionCheck:event-edit');
    Route::get('delete-event-view/{id}', 'EventController@deleteEventView')->name('delete-event-view');
    Route::get('delete-event/{id}', 'EventController@deleteEvent')->name('delete-event')->middleware('RoutePermissionCheck:delete-event');;
//    Route::get('download-event-document/{file_name}', 'EventController@download')->name('delete-event')->middleware('RoutePermissionCheck:event');


});
