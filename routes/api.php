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

Route::post('userlogin/{locale}','UserController@userlogin');
Route::post('signup/{locale}','UserController@signup');
Route::post('sociallogin/{locale}','UserController@sociallogin');
Route::post('forgotpassword/{locale}','UserController@forgot_password');
Route::post('forgotpasswordotpcheck/{locale}','UserController@otpcheck');
Route::post('resetpassword/{locale}','UserController@resetpassword');
Route::get('appsettings/{locale}','UserController@appsettings');
Route::get('about/{locale}','UserController@about');
Route::get('faq/{locale}','UserController@faq');
Route::get('termsandconditions/{locale}','UserController@termsandconditions');
Route::post('basicemail/{locale}','UserController@basic_email');

Route::post('getotp/{locale}','UserController@getotp');
Route::post('verifyotp/{locale}','UserController@verifyotp');


Route::post('updatedevicetoken/{locale}','UserController@updatedevicetoken')->middleware('auth:api');
Route::post('addaddress/{locale}','UserController@addaddress')->middleware('auth:api');
Route::get('listaddress/{locale}','UserController@viewaddress')->middleware('auth:api');
Route::post('updateaddress/{locale}','UserController@updateaddress')->middleware('auth:api');
Route::get('homedashboard/{locale}','UserController@dashboard');
Route::post('list_subcategory/{locale}','UserController@list_subcategory');
Route::get('viewprofile/{locale}','UserController@viewprofile')->middleware('auth:api');
Route::post('updateprofile/{locale}','UserController@updateprofile')->middleware('auth:api');
Route::post('changepassword/{locale}','UserController@changepassword')->middleware('auth:api');
Route::post('listprovider/{locale}','UserController@listprovider')->middleware('auth:api');
Route::post('newbooking/{locale}','UserController@newbooking')->middleware('auth:api');
Route::get('view_bookings/{locale}','UserController@view_bookings')->middleware('auth:api');
Route::post('getproviderlocation/{locale}','UserController@getproviderlocation')->middleware('auth:api');
Route::post('paidstatus/{locale}','UserController@paidstatus')->middleware('auth:api');
Route::post('review/{locale}','UserController@review_feedback')->middleware('auth:api');
Route::post('pay/{locale}','UserController@payment_method')->middleware('auth:api');
Route::post('cancelbyuser/{locale}','UserController@cancelbyuser')->middleware('auth:api');
Route::post('deleteaddress/{locale}','UserController@deleteaddress')->middleware('auth:api');
Route::get('list_payment_methods/{locale}','UserController@list_payment_methods')->middleware('auth:api');
Route::get('fcmtest','UserController@fcmtest');
Route::post('stripe/{locale}','UserController@postPaymentWithStripe')->middleware('auth:api');
Route::post('charge/{locale}','UserController@charge')->middleware('auth:api');
Route::post('ephemeral_keys/{locale}','UserController@ephemeral_keys')->middleware('auth:api');
Route::get('logout/{locale}','UserController@logout')->middleware('auth:api');
Route::post('cancel_request/{locale}','UserController@cancel_request')->middleware('auth:api');

Route::post('reportuser/{locale}','UserController@reportuser');

Route::post('listprovidertest/{locale}','UserController@listprovidertest')->middleware('auth:api');


Route::post('couponverify/{locale}','UserController@couponverify');
Route::post('couponremove/{locale}','UserController@couponremove');
Route::post('invoicepdf/{locale}','UserController@pdfgenerator');



Route::post('addmoney','UserController@addmoneywallet')->middleware('auth:api');
Route::post('wallettransaction','UserController@wallettransction')->middleware('auth:api');

Route::post('startjobendjobdetails/{locale}','UserController@startjobendjobdetails');

