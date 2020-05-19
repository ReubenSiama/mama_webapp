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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/searchuser','CustomerController@userfull');
Route::get('/yadav','CustomerController@yadav');
Route::post('/getcustome','CustomerController@getdetails');
Route::get('messages','TokenController@index');
Route::post('message','TokenController@store');
Route::get('privatemessage','TokenController@pms');
Route::get('/banner','TokenController@bannerdata');
// invoices
Route::get('ManagementMessages','TokenController@ManagementMessages');
Route::post('ManagementMessage','TokenController@ManagementMessage');
// it
Route::get('itMessages','TokenController@itMessages');
Route::post('itMessage','TokenController@itMessage');
// tl
Route::get('tlMessages','TokenController@tlMessages');
Route::post('tlMessage','TokenController@tlMessage');
Route::get('buyerlogin','TokenController@buyerLogin');

Route::POST('/login','TokenController@getLogin');
Route::get('/tracklogin','TokenController@tracklogin');
Route::get('/tracklogout','TokenController@tracklogout');



Route::get('logout','TokenController@logout');
Route::get('saveLocation/{userid}/{latitude}/{longitude}','TokenController@saveLocation');
//Route::post('getregister',['middleware'=>'auth:api','uses'=> 'TokenController@getregister']); 
Route::post('getregister', 'TokenController@getregister');
//user register
Route::post('/register','mamaController@postRegistration');
//byer login
Route::post('/blogin','BuyerController@postBuyerLogin');
//login users
Route::get('/authlogin','HomeController@authlogin');
//add project
Route::post('/addProject','mamaController@addProject');
Route::post('/addProject','TokenController@addProject');
Route::post('/addenquiry','TokenController@enquiry');
Route::get('/getproject','TokenController@getproject');
Route::get('/getsingleproject','TokenController@getsingleProject');
Route::get('/getenq','TokenController@getenq');
Route::get('/brand','TokenController@getbrands');
// Route::get('/updateProject','TokenController@getUpdateProject');
Route::post('/updateProject','TokenController@postUpdateProject');
Route::post('/updateEnquiry','TokenController@updateEnquiry');

Route::post('/addLocation','TokenController@addLocation');
Route::post('/updateLocation','TokenController@updateLocation');

Route::get('pending','TokenController@pending');
Route::get('/confirmorders','TokenController@confirm');
Route::post('/recordtime','TokenController@recordtime');
Route::post('/flogout','TokenController@fieldlogout');
Route::get('/gettime','TokenController@gettime');
Route::get('/req','TokenController@getreq');
Route::get('/req1','TokenController@getreq1');

Route::post('/react','TokenController@data');
Route::get('/fakegps','TokenController@fakegps');

//listing engineers
Route::post('/addleProject','TokenController@addleProject');
Route::post('/saveManufacturer','TokenController@postSaveManufacturer');
Route::get('/getward','TokenController@getwards');
Route::get('/getsubwards','TokenController@getsubwards');
Route::post('/testhead','CustomerController@testhead');
Route::post('/subtesthead','CustomerController@subtesthead');

// new api
Route::post('/addEmployee11/{id}','TokenController@updateemp');
Route::post('/addEmployee1','TokenController@addemp');
Route::get('/addEmployee1','TokenController@getemp');
Route::post('/addEmployee12/{id}','TokenController@deletemp');

//---------------------------------HR MOdule-------------------------------------
Route::get('hrmanage','HrController@indexhr');
Route::get('sendinvite','HrController@sendinvite');
Route::post('/invitation','HrController@send');
Route::get('interview','HrController@student');
Route::post('stregister','HrController@reg');
Route::post('/attend','HrController@attend');
Route::get('/hrround','HrController@hrtake');
Route::get('/getround','HrController@getround');
Route::POST('/firstmarks','HrController@firstmarks');
