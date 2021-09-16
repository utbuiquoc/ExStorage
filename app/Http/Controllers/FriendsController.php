<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\File;
use App\Dir;
use App\Friends;

class FriendsController extends Controller
{
    //
    public function friend() {
        return view('user-function.friend', ['title' => 'Bạn bè']);
    }

    public function findFriend(Request $request) {
        $this->validate($request, [
            'friendInfo' => 'min:1'
        ]);

        $friendInfo = $request->friendInfo;

        $userDB = new User;
        $friend = $userDB->where('name', $friendInfo)->orWhere('email', $friendInfo)->orWhere('id', $friendInfo)->get();


        foreach ($friend as $key => $value) {
            $friendRequest = Friends::find($value->id)->request;

            $value->request = $friendRequest;
        }

        return $friend;
    }

    /*

        Cơ chế send request, accept request, cancel friend,...

        + Gửi lời mời kết bạn, user được gửi lời mời sẽ nhận được dữ liệu là id của người gửi được nhập ở cột request, ngăn cách bởi dấu "|"
        + Nếu không nhận lời mời kết bạn (hay là hủy lời mời kết bạn), xóa id của người được gửi trong cột request
        + Nếu chấp nhận lời mời kết bạn, xóa id của người gửi trong cột request, thêm id của người gửi và user vào cột friends, ngăn cách bởi dấu "|"

    */

    protected function removeIdUser($str, $id) {
        $strArr = explode('|', $str);

        foreach ($strArr as $key => $value) {
            if ($value == $id) {
                unset($strArr[$key]);

                return implode('|', $strArr);
            }
        }

        return implode('|', $strArr);
    }

    public function sendFriendRequest(Request $request) {
        $this->validate($request, [
            'friendId' => 'required'
        ]);

        $friendId = $request->friendId;
        $friendIdTS = $friendId;
        $friendIdTSS = Auth::user()->id;

        $userSended = Friends::find($friendId);
        $user = Friends::find(Auth::user()->id);

        $userRequest = $userSended->request;
        $currentUserRequest = $user->request;

        if ($currentUserRequest != "") {
            $currentUserRequestArr = explode('|', $currentUserRequest);
            foreach ($currentUserRequestArr as $key => $value) {
                if ($value == $friendId) {

                    if ($user->friends != "") {
                        $friendIdTS = $user->friends . '|' . $friendId;
                    }

                    if ($userSended->friends != "") {
                        $friendIdTSS = $userSended->friends . '|' . Auth::user()->id;
                    }

                    $currentUserRequest = $this->removeIdUser($currentUserRequest, $friendId);

                    $user->friends = $friendIdTS;
                    $user->request = $currentUserRequest;
                    $user->save();

                    $userSended->friends = $friendIdTSS;
                    $userSended->save();

                    return User::find($friendId);
                }
            }
        }

        if ($userRequest != "") {
            $userRequest .= '|' . Auth::user()->id;
        } else {
            $userRequest .= Auth::user()->id;
        }
        
        $userSended->request = $userRequest;
        $userSended->save();
    }

    public function cancelFriendRequest(Request $request) {
        $this->validate($request, [
            'friendId' => 'required'
        ]);

        $friendId = $request->friendId;

        $userSended = Friends::find($friendId);

        $userRequest = $userSended->request;

        if ($userRequest != "") {
            $userRequestArr = explode('|', $userRequest);

            foreach($userRequestArr as $key => $value) {
                if ($value == Auth::user()->id) {
                    unset($userRequestArr[$key]);

                }
            }

            $userRequest = implode('|', $userRequestArr);
        }
        
        $userSended->request = $userRequest;
        $userSended->save();
    }

    public function cancelFriendRequested(Request $request) {
        $this->validate($request, [
            'friendId'  =>'required'
        ]);

        $friendId = $request->friendId;
        $userId = Auth::user()->id;


        $user = Friends::find($userId);
        $userRequest = $user->request;


        $userRequestArr = explode('|', $userRequest);
        foreach ($userRequestArr as $key => $value) {
            if ($value == $friendId) {
                unset($userRequestArr[$key]);

                $userRequest = implode('|', $userRequestArr);
                break;
            }
        }

        $user->request = $userRequest;
        $user->save();

        return 'Đã từ chối lời mời kết bạn';
    }

    public function unfriendAccepted(Request $request) {
        $this->validate($request, [
            'friendId'  =>'required'
        ]);

        $friendId = $request->friendId;
        $userId = Auth::user()->id;

        $friendData = Friends::find($friendId);
        $userData = Friends::find($userId);

        $friendInfo = $friendData->friends;
        $userInfo = $userData->friends;

        $newFriendInfo = $this->removeIdUser($friendInfo, $userId);
        $newUserInfo = $this->removeIdUser($userInfo, $friendId);


        $friendData->friends = $newFriendInfo;
        $friendData->save();

        $userData->friends = $newUserInfo;
        $userData->save();
    }
}
