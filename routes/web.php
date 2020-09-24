<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheck;

Route::get('/new', 'TestController@controllerMethod');


Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
      //Route Members
      Route::post('/create_member', 'AdminController@addMember');
      Route::get('/get_members', 'AdminController@getMember');
      Route::post('/edit_member', 'AdminController@editMember');
      Route::post('/delete_member', 'AdminController@deleteMember');
      
      //Route Tarif(fare)
      Route::post('/create_fare', 'AdminController@addFare');
      Route::get('/get_fares', 'AdminController@getFare');
      Route::post('/edit_fare', 'AdminController@editFare');
      Route::post('/delete_fare', 'AdminController@deleteFare');
      
      //Route Upload
      Route::post('/create_upload', 'AdminController@addUpload');
      Route::get('/get_uploads', 'AdminController@getUpload');
      Route::post('/edit_upload', 'AdminController@editUpload');
      Route::post('/delete_upload', 'AdminController@deleteUpload');
      Route::post('/upload', 'AdminController@upload');
      Route::post('/delete_image', 'AdminController@deleteImage');
      Route::post('/delete_image_edit', 'AdminController@deleteEditImage');
      
      //Route untuk Admin & User
      Route::post('/create_user', 'AdminController@createUser');
      Route::get('/get_users', 'AdminController@getUsers');
      Route::post('/edit_user', 'AdminController@editAdmin');
      Route::post('/delete_user', 'AdminController@deleteAdmin');
      Route::post('/admin_login', 'AdminController@adminlogin');
});
      
      //Route untuk login
      Route::get('/logout', 'AdminController@logout');
      Route::get('/', 'AdminController@index');
      Route::any('{slug}', 'AdminController@index');
      
      //Route::get('/', function () {
            //    return view('welcome');
            //});
            //Route::any('{slug}', function () {
                  //    return view('welcome');
                  //});