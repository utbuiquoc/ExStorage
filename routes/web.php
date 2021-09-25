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
    Route::post('/', 'FileController@upload');

    Route::get('library', 'FileController@library');
    Route::get('library/{type?}', 'FileController@typeData');
        Route::post('createType', 'FileController@createType');
        Route::post('uploadFile', 'FileController@uploadFile');
        Route::post('createFolder', 'FileController@createFolder');
        Route::post('removeType', 'FileController@removeType');
        Route::post('removeItem', 'FileController@removeItem');
        Route::post('renameItem', 'FileController@renameItem');
        Route::post('allcanview', 'FileController@allcanview');

    Route::get('share/file/{owner}/{itemName}', 'UserController@shareFile');
    Route::get('share/folder/{owner?}/{itemDir?}', 'UserController@shareFolder')->where('itemDir', '.*');;
    Route::get('viewer', function() {
        return view('viewer');
    });

    Route::get('friend', 'FriendsController@friend');
        Route::post('find-friend', 'FriendsController@findFriend');
        Route::post('send-friend-request', 'FriendsController@sendFriendRequest');
        Route::post('cancel-friend-request', 'FriendsController@cancelFriendRequest');
        // Route::post('accept-friend-request', 'FriendsController@acceptFriendRequest');
        Route::post('cancel-friend-requested', 'FriendsController@cancelFriendRequested');
        Route::post('unfriend-accepted', 'FriendsController@unfriendAccepted');
});


// Route Đăng nhập, đăng kí, đăng xuất
Route::get('sign-in', function() {
    if (Auth::check()) {
        return redirect('/');
    } else {
        return view('user.signin', ['title' => 'Đăng nhập']);
    }
});
Route::get('sign-up', function() {
    return view('user.signup', ['title' => 'Đăng ký']);
});
Route::post('sign-up', 'UserController@postSignup');
Route::post('sign-in', 'UserController@postSignin');

Route::get('logout', 'UserController@logout');

Route::get('testGet', 'UserController@testGet');
Route::post('testPost', 'UserController@testPost');

Route::post('findItem', 'UserController@findItem');