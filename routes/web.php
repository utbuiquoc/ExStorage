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

Route::group(['middleware' => 'login'], function() {
    Route::get('/', function () {
        return view('home.home-page', ['title' => 'Trang chủ']);
    })->middleware('login');

    Route::get('library', function() {
        return view('user-function.library', ['title' => 'Thư viện']);
    });

    Route::get('viewer', function() {
        return view('viewer');
    });
});


// Route Đăng nhập, đăng kí, đăng xuất
Route::get('sign-in', function() {
    return view('user.signin', ['title' => 'Đăng nhập']);
});
Route::get('sign-up', function() {
    return view('user.signup', ['title' => 'Đăng ký']);
});
Route::post('sign-up', 'UserController@postSignup');
Route::post('sign-in', 'UserController@postSignin');

Route::get('logout', 'UserController@logout');

Route::get('test', 'UserController@test');