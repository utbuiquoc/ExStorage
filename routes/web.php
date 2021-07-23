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

Route::get('/', function () {
    return view('home.home-page', ['title' => 'Trang chủ']);
});

Route::get('login', function() {
    return view('user.login', ['title' => 'Đăng nhập']);
});

Route::get('sign-up', function() {
    return view('user.signup', ['title' => 'Đăng ký']);
});

Route::get('library', function() {
    return view('user-function.library', ['title' => 'Thư viện']);
});