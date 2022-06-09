<?php

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
Route::group(['middleware' =>['web', 'auth-checker']], function() {
    Route::prefix('notification')->group(function() {
        Route::get('/', 'NotificationController@index')->name('notifications');
        Route::get('/view-all', 'NotificationController@viewAllNotifications')->name('notifications.view.all');
        Route::get('/nearExpiry', 'NotificationController@nearExpiry')->name('notification.nearExpiry');
        Route::get('/nearExpirySixtyDays', 'NotificationController@nearExpirySixtyDays')->name('notification.nearExpirySixtyDays');
        Route::get('/nearExpiryNintyDays', 'NotificationController@nearExpiryNintyDays')->name('notification.nearExpiryNintyDays');
        Route::get('/nearExpiryOneEightyDays', 'NotificationController@nearExpiryOneEightyDays')->name('notification.nearExpiryOneEightyDays');
        Route::get('/expired-items', 'NotificationController@ExpiredItems')->name('notification.ExpiredItems');
        Route::get('/dosing', 'NotificationController@dosing')->name('notification.dosing');
        Route::get('/reports', 'NotificationController@reports')->name('notification.reports');
        Route::get('/pending-lab', 'NotificationController@PendingLabRadio')->name('notification.PendingLabRadio');
        Route::get('/mark-as-read/{id}', 'NotificationController@MarkRead')->name('notification.mark.read');
        Route::get('/mark-all-read', 'NotificationController@markAllRead')->name('notification.mark.all.read');


        //These are the routes for Firebase Notification
        Route::get('/save-token', 'NotificationController@saveToken')->name('notification.save.token');
        Route::get('/send-notification', 'NotificationController@sendNotifications')->name('notification.send.notification');
    });
});
