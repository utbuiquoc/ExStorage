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

    Route::get('friend-table-info', 'HomeController@friendTableInfo');

    Route::get('library', 'FileController@library');
    Route::get('library/{type?}', 'FileController@typeData');
        Route::post('createType', 'FileController@createType');
        Route::post('uploadFile', 'FileController@uploadFile');
        Route::post('createFolder', 'FileController@createFolder');
        Route::post('removeType', 'FileController@removeType');
        Route::post('removeItem', 'FileController@removeItem');
        Route::post('removeFolder', 'FileController@removeFolder');
        Route::post('renameItem', 'FileController@renameItem');
        Route::post('renamFolder', 'FileController@renameFolder');
        Route::post('set-private', 'FileController@setPrivate');
        Route::post('set-public', 'FileController@setPublic');
        Route::get('get-exercise-status', 'FileController@getExerciseStatus');
        Route::get('get-exercise-status-folder', 'FileController@getExerciseStatusFolder');
        Route::post('change-ex-file-status', 'FileController@changeExFileStatus');
        Route::post('change-ex-folder-status', 'FileController@changeExFolderStatus');

        // Route phần share file bị hạn chế
        Route::post('set-file-limited', 'FileController@setLimited');
        Route::get('get-list-friends', 'FileController@getListFriends');
        Route::post('get-list-friends-not-allowed', 'FileController@getListFriendsNotAllow');
        Route::post('add-friend-can-view-file', 'FileController@addFriendCanViewFile');
        Route::post('add-group-can-view-file', 'GroupsController@addGroupCanViewFile');
        Route::post('get-friends-allowed-view', 'FileController@getFriendsAllowedView');
        Route::post('get-groups-allowed-view', 'GroupsController@getGroupsAllowedView');
        Route::post('remove-friend-added', 'FileController@ ');
        Route::post('remove-group-added', 'GroupsController@removeGroupAdded');

    Route::get('exercise', 'ExerciseController@exercise');

    
    Route::get('friend', 'FriendsController@friend');
        Route::post('find-friend', 'FriendsController@findFriend');
        Route::post('send-friend-request', 'FriendsController@sendFriendRequest');
        Route::post('cancel-friend-request', 'FriendsController@cancelFriendRequest');
        // Route::post('accept-friend-request', 'FriendsController@acceptFriendRequest');
        Route::post('cancel-friend-requested', 'FriendsController@cancelFriendRequested');
        Route::post('unfriend-accepted', 'FriendsController@unfriendAccepted');
        Route::get('get-friend-list', 'FriendsController@getFriendList');

    
    Route::get('group', 'GroupsController@group');
        Route::get('get-list-group-joined', 'GroupsController@getListGroupJoined');
        Route::get('get-list-ownership-group', 'GroupsController@getListOwnershipGroup');
        Route::get('get-group-detail', 'GroupsController@getGroupDetail');
        Route::post('create-new-group', 'GroupsController@createNewGroup');
        Route::post('disable-open-link-share', 'GroupsController@diasbleOpenLinkShare');
        Route::post('enable-open-link-share', 'GroupsController@enableOpenLinkShare');
        Route::get('get-user-info', 'GroupsController@getUserInfo');
        Route::post('remove-member', 'GroupsController@removeMember');
        Route::post('exit-group', 'GroupsController@exitGroup');
        Route::post('remove-group', 'GroupsController@removeGroup');
        Route::post('get-list-groups-not-allowed', 'GroupsController@getListGroupsNotAllowed');
        Route::get('get-list-viewer-of-group', 'GroupsController@getListViewerOfGroup');
    Route::get('invite/{inviteLink}', 'GroupsController@joinGroupViaLink');
        Route::get('get-group-detail-via-link', 'GroupsController@getGroupDetailViaLink');
        Route::post('accept-invition', 'GroupsController@accpetInvitation');
});


Route::get('share/file/{owner}/{itemName}', 'UserController@shareFile');
Route::get('share/folder/{owner?}/{itemDir?}', 'UserController@shareFolder')->where('itemDir', '.*');;
Route::get('viewer', function() {
    return view('viewer');
});

// Upload Ans
Route::get('confirm-ex-file', 'FileController@confirmExFile');
Route::get('confirm-ex-folder', 'FileController@confirmExFolder');
Route::post('upload-answer', 'ExerciseController@uploadAnswer');
Route::post('upload-answer-folder', 'ExerciseController@uploadAnswerFolder');
Route::get('get-list-file-uploaded', 'ExerciseController@getListFileUploaded');
Route::get('get-list-folder-uploaded', 'ExerciseController@getListFolderUploaded');
Route::post('remove-ans-file', 'ExerciseController@removeAnsFile');
Route::post('remove-ans-folder', 'ExerciseController@removeAnsFolder');

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

// Test
Route::post('test-function', 'FileController@renameChildFile');

Route::get('/token', function () {
    return csrf_token(); 
});