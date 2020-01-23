<?php


/** new providersign **/

Route::post('provider_new','ProviderController@providernewlogin');


Route::post('provider_signup/{locale}','ProviderController@provider_signup');
Route::post('providerlogin/{locale}','ProviderController@providerlogin');
Route::post('forgotpassword/{locale}','ProviderController@forgot_password');
Route::post('otpcheck/{locale}','ProviderController@otpcheck');
Route::post('resetpassword/{locale}','ProviderController@resetpassword');
Route::get('appsettings/{locale}','ProviderController@appsettings');

Route::get('listcategory/{locale}','ProviderController@listcategory');
Route::post('listsubcategory/{locale}','ProviderController@listsubcategory');

Route::post('getotp/{locale}','ProviderController@getotp');
Route::post('elapsetime','ProviderController@elapsetime');
Route::post('verifyotp/{locale}','ProviderController@verifyotp');



Route::post('updatedevicetoken/{locale}','ProviderController@updatedevicetoken')->middleware('auth:provider');
Route::get('viewprofile/{locale}','ProviderController@viewprofile')->middleware('auth:provider');
Route::post('updateprofile/{locale}','ProviderController@updateprofile')->middleware('auth:provider');
Route::get('view_schedules/{locale}','ProviderController@view_schedules')->middleware('auth:provider');
Route::post('updateschedules/{locale}','ProviderController@updateschedules')->middleware('auth:provider');
Route::get('view_provider_category/{locale}','ProviderController@view_provider_category')->middleware('auth:provider');
Route::get('homedashboard/{locale}','ProviderController@home')->middleware('auth:provider');
Route::post('changepassword/{locale}','ProviderController@changepassword')->middleware('auth:provider');
Route::post('update_provider_category/{locale}','ProviderController@update_provider_category')->middleware('auth:provider');
Route::post('update_location/{locale}','ProviderController@update_location')->middleware('auth:provider');

Route::post('acceptbooking/{locale}','ProviderController@acceptbooking')->middleware('auth:provider');
Route::post('rejectbooking/{locale}','ProviderController@rejectbooking')->middleware('auth:provider');
Route::post('cancelbyprovider/{locale}','ProviderController@cancelbyprovider')->middleware('auth:provider');
Route::post('starttocustomerplace/{locale}','ProviderController@starttocustomerplace')->middleware('auth:provider');
Route::post('startedjob/{locale}','ProviderController@startedjob')->middleware('auth:provider');
Route::post('completedjob/{locale}','ProviderController@completedjob')->middleware('auth:provider');
Route::post('paymentaccept/{locale}','ProviderController@paymentaccept')->middleware('auth:provider');
Route::post('userreviews/{locale}','ProviderController@user_reviews')->middleware('auth:provider');
Route::post('add_category/{locale}','ProviderController@add_category')->middleware('auth:provider');
Route::post('edit_category/{locale}','ProviderController@edit_category')->middleware('auth:provider');
Route::post('delete_category/{locale}','ProviderController@delete_category')->middleware('auth:provider');
Route::post('update_address/{locale}','ProviderController@update_address')->middleware('auth:provider');
Route::get('logout/{locale}','ProviderController@logout')->middleware('auth:provider');
Route::post('accept_random_request/{locale}','ProviderController@accept_random_request')->middleware('auth:provider');
Route::post('reject_random_request/{locale}','ProviderController@reject_random_request')->middleware('auth:provider');
Route::post('providercal/{locale}','ProviderController@providercal');

/** provider calender **/
Route::post('providercalender/{locale}','ProviderController@providercalender')->middleware('auth:provider');

Route::post('calenderbookingdetails/{locale}','ProviderController@calenderbookingdetails')->middleware('auth:provider');

Route::post('startjobendjobdetails/{locale}','ProviderController@startjobendjobdetails');


