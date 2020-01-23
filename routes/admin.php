<?php

Route::post('adminlogin','AdminController@Login');

Route::get('listusers','AdminController@listusers')->middleware('auth:admin');

// Route::get('listuser','AdminController@listusers');
Route::get('aboutus','AdminController@about_us');
Route::Post('update_percentage','AdminController@update_percentage');
Route::get('faq','AdminController@faq');
Route::get('terms','AdminController@terms');
Route::post('getuser','AdminController@getuserdetails')->middleware('auth:admin');
Route::get('listproviders','AdminController@listproviders')->middleware('auth:admin');
Route::post('getproviderdetails','AdminController@getproviderdetails')->middleware('auth:admin');
Route::get('allbookings','AdminController@allbookings')->middleware('auth:admin');
Route::get('listcategory','AdminController@listcategory')->middleware('auth:admin');
Route::get('listsubcategory','AdminController@listsubcategory')->middleware('auth:admin');
Route::get('list_tax','AdminController@list_tax')->middleware('auth:admin');
Route::post('getbookingsearchdetails','AdminController@getbookingsearchdetails');
Route::post('getearningreportsearchdetails','AdminController@getearningreportsearchdetails');
Route::Post('update_tax_status','AdminController@update_tax_status');
Route::Post('delete_tax','AdminController@delete_tax')->middleware('auth:admin');
Route::post('add_tax','AdminController@add_tax')->middleware('auth:admin');
Route::post('addcategory','AdminController@addcategory')->middleware('auth:admin');
Route::post('addsubcategory','AdminController@addsubcategory')->middleware('auth:admin');
Route::get('viewreviews','AdminController@viewreviews')->middleware('auth:admin');
Route::get('viewslots','AdminController@viewslots')->middleware('auth:admin');
Route::Post('update_slots','AdminController@update_slots')->middleware('auth:admin');
Route::post('addtimeslots','AdminController@add_timeslots')->middleware('auth:admin');
Route::get('dashboard','AdminController@dashboard')->middleware('auth:admin');
Route::get('allproviderlocation','AdminController@all_provider_location')->middleware('auth:admin');
Route::post('getbookingdetails','AdminController@getbookingdetails')->middleware('auth:admin');
Route::post('updatecategory','AdminController@updatecategory')->middleware('auth:admin');
Route::post('updatesubcategory','AdminController@updatesubcategory')->middleware('auth:admin');
Route::post('imageupload','AdminController@imageupload');
Route::post('update_user_status','AdminController@update_user_status');
Route::post('update_provider_status','AdminController@update_provider_status');
Route::get('get_radius','AdminController@get_radius');
Route::Post('update_radius','AdminController@update_radius');
Route::Post('update_tax_percentage','AdminController@update_tax_percentage');
Route::Post('update_category_status','AdminController@update_category_status');
Route::Post('update_subcategory_status','AdminController@update_subcategory_status');
Route::GET('get_payments','AdminController@get_payments');
Route::Post('update_payment_status','AdminController@update_payment_status');
Route::Post('update_baseamount_status','AdminController@update_baseamount_status');
Route::Post('data','AdminController@data');
Route::Post('datainsert','AdminController@datainsert');


Route::POST('showPag','AdminController@showPag');

Route::POST('editpage','AdminController@editpage');

Route::POST('listbannerimages','AdminController@listbannerimages');
Route::POST('editbanner','AdminController@editbanner');

Route::POST('add_coupons','AdminController@add_coupons');
Route::POST('list_coupons','AdminController@list_coupons');
Route::POST('edit_coupons','AdminController@edit_coupons');
Route::POST('delete_coupons','AdminController@delete_coupons');
