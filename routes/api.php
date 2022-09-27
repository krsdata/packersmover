<?php

use Illuminate\Http\Request;

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

Route::post('store', 'API\UserAPIController@store');
Route::post('login', 'API\UserAPIController@login');
Route::post('social_login', 'API\UserAPIController@social_login');
Route::post('/password/email', 'API\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'Api\ResetPasswordController@reset');
Route::get('countries', 'API\HomeAPIController@countries');
Route::post('states', 'API\HomeAPIController@states');
Route::post('city', 'API\HomeAPIController@city');

Route::post('faq', 'API\HomeAPIController@faq');
Route::post('contact', 'API\HomeAPIController@contact');
Route::post('terms_conditions', 'API\HomeAPIController@terms_conditions');
Route::post('privacy_policy', 'API\HomeAPIController@privacy_policy');
Route::get('home', 'API\HomeAPIController@home');
//Route::post('membership', 'API\UserAPIController@get_membership');
//Route::post('forgot/password', 'Auth\ForgotPasswordController');
/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});*/

//  script for insert monthly tickets
Route::get('monthly_tickets', 'API\HomeAPIController@monthly_tickets');
Route::post('get_playData', 'API\HomeAPIController@get_playData');

Route::group(['middleware' => 'auth:api'], function() {
     //---------------- User --------------------------

     Route::post('send_notification', 'API\NotificationAPIController@send_notification');
     Route::post('fcm_update', 'API\NotificationAPIController@fcm_update');
     Route::post('my_plays', 'API\HomeAPIController@my_plays');
     Route::post('Month_Draw', 'API\HomeAPIController@Month_Draw');
     Route::post('Set_Settings', 'API\HomeAPIController@Set_Settings');
     Route::get('my_wallet', 'API\HomeAPIController@my_wallet');
     Route::get('withdraw_history', 'API\HomeAPIController@withdraw_history');
     Route::get('get_profile', 'API\UserAPIController@get_profile');
     Route::post('notifyUser', 'API\UserAPIController@notifyUser');
     Route::post('feedback', 'API\HomeAPIController@feedback');
     Route::post('send_otp', 'API\UserAPIController@send_otp');     
     Route::post('booking_draw', 'API\HomeAPIController@booking_draw');    
     Route::post('booking_Monthdraw', 'API\HomeAPIController@booking_Monthdraw');    
     Route::post('upload_image', 'API\UserAPIController@upload_image');
     Route::post('update_users', 'API\UserAPIController@update_users');
     Route::post('request_money', 'API\HomeAPIController@request_money');
     Route::post('change_password', 'API\UserAPIController@change_password');
     Route::get('get_data', 'API\UserAPIController@get_data');
     
     
});
Route::get('winners_script', 'API\HomeAPIController@winners_script');