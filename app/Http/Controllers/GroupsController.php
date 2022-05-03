<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Files;
use App\Dir;
use App\Friends;
use App\Groups;

class GroupsController extends Controller
{
    //
    public function group() {
        return view('user-function.group', ['title' => 'Nhóm']);
    }

    public function findFriend(Request $request) {
        $this->validate($request, [
            'friendInfo' => 'min:1'
        ]);

        $friendInfo = $request->friendInfo;

        $userDB = new User;
        $friend = $userDB->where('name', 'like', "%$friendInfo%")->orWhere('email', $friendInfo)->orWhere('id', $friendInfo)->get();


        foreach ($friend as $key => $value) {
            $friendRequest = Friends::find($value->id)->request;

            $value->request = $friendRequest;
        }

        return $friend;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createNewGroup(Request $request) {
        $this->validate($request, [
            'name'          => 'required|min:3|',
            'numberMembers'         => 'required|min:1',
            'members'      => 'required|min:1',
        ], [
            'name.required'          => 'Bạn chưa nhập tên nhóm!',
            'numberMembers.required' => 'Nhóm cần phải có ít nhất một thành viên',
            'members.required'       => 'Nhóm cần phải có ít nhất một thành viên',
        ]);

        $nameGroup = $request->name;
        $numberMembers = $request->numberMembers;
        $members = $request->members;
        $membersId = $request->membersId;

        $inviteLink = $this->generateRandomString();

        $group = new Groups;
        $group->name = $nameGroup;
        $group->owner = Auth::user()->name;
        $group->number_mem = $numberMembers;
        $group->members = $members;
        $group->invite_link = $inviteLink;
        $group->save();

        $groupCreated = $group->where('owner',Auth::user()->name)->where('name', $nameGroup)->where('invite_link', $inviteLink)->get();
        $groupCreatedId = $groupCreated[0]->id;

        $membersIdList = explode(',', $membersId);
        foreach ($membersIdList as $key => $memberId) {
            $member = User::find($memberId);
            if ($member->group_joined == null) {
                $member->group_joined = $groupCreatedId;
            } else {
                $member->group_joined .= ' ' . $groupCreatedId;
            }
            $member->save();
        }

        return "Thành công!";
    }

    public function getListGroupsNotAllowed(Request $request) {
        $friendsName;

        $userName = Auth::user()->name;

        // Lấy tên của những người đã kết bạn.
        $groups = Groups::where('owner', $userName)->get();

        // Xóa những friend đã có thể xem file
        if (isset($request->friendAllowed)) {
            $friendAllowed = $request->friendAllowed;
            $friendAllowed = explode('|', $friendAllowed);
            foreach ($friendAllowed as $key => $value) {
                $friendsName = $this->removeGroupSelected($groups, $value);
            }
        }

        return $groups;
    }

    protected function removeGroupSelected($groups, $groupSelected) {
        foreach ($groups as $key => $group) {
            if ($group->name == $groupSelected) {
                unset($groups[$key]);
            }
        }
        return $groups;
    }

    public function addItemViewer($fileId, $name, $type) {
        if ($type === 'folder') {
            $item = Dir::find($fileId);
        } else if ($type == 'file') {
            $item = Files::find($fileId);
        }

        $dataToSave = $item->group_viewer . '|' . $name;
        if ($dataToSave[0] === '|') { $dataToSave = str_replace('|', '', $dataToSave); }

        $item->group_viewer = $dataToSave;
        $item->save();
    }

    private function addGroup($dir, $viewerName) {
        $fileDb = new Files;
        $dir = str_replace('/', '\/', $dir);
        $filesNeedChange = $fileDb->where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        // Đổi dir từng file
        foreach ($filesNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->addItemViewer($fileId, $viewerName, 'file');
        }

        $folderNeedChange = Dir::where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();


        foreach ($folderNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->addItemViewer($fileId, $viewerName, 'folder');
        }
    }

    public function addGroupCanViewFile(Request $request) {
        $this->validate($request, [
            'groupName'    => 'required',
            'fileDir'       => 'required'
        ]);

        $groupName = $request->groupName;
        $fileDir = $request->fileDir;

        if ($fileDir[strlen($fileDir)-1] === '/') {
            $fileDir = substr($fileDir,0,-1);

            $this->addGroup($fileDir, $groupName);

            return $groupName;
        }

        $fileTarget = Files::where('name', $fileDir)->get();

        $dataToSave = $fileTarget[0]->group_viewer . '|' . $groupName;
        if ($dataToSave[0] === '|') { $dataToSave = str_replace('|', '', $dataToSave); }
        $fileTarget[0]->group_viewer = $dataToSave;
        $fileTarget[0]->save();

        return $groupName;
    }

    public function getGroupsAllowedView(Request $request) {
        $fileDir = $request->fileDir;

        if ($fileDir[strlen($fileDir)-1] === '/') {
            $fileDir = substr($fileDir,0,-1);
            $friendAllowed = Dir::where('dir', $fileDir)->select('group_viewer')->get()[0];

            return $friendAllowed->group_viewer;
        }

        $friendAllowed = Files::where('name', $fileDir)->select('group_viewer')->get()[0];

        return $friendAllowed->group_viewer;
    }

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

    private function removeItemGroupViewer($fileId, $name, $type) {
        if ($type === 'folder') {
            $item = Dir::find($fileId);
        } else if ($type == 'file') {
            $item = Files::find($fileId);
        }

        $groupViewer = $this->removeIdUser($item->group_viewer, $name);

        $item->group_viewer = $groupViewer;
        $item->save();
    }

    private function removeGroupViewer($dir, $viewerName) {
        $fileDb = new Files;
        $dir = str_replace('/', '\/', $dir);
        $filesNeedChange = $fileDb->where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        // Đổi dir từng file
        foreach ($filesNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->removeItemGroupViewer($fileId, $viewerName, 'file');
        }

        $folderNeedChange = Dir::where('owner', Auth::user()->name)->where('dir', 'regexp', "$dir([a-zA-Z0-9!\p{L}@#$%^&*\s\/_-]*|)$")->get();

        foreach ($folderNeedChange as $key => $value) {
            $fileId = $value->id;
            $this->removeItemGroupViewer($fileId, $viewerName, 'folder');
        }
    }

    public function removeGroupAdded(Request $request) {
        $this->validate($request, [
            'friendName'    => 'required',
            'fileDir'       => 'required'
        ]);

        $friendName = $request->friendName;
        $fileDir = $request->fileDir;

        if ($fileDir[strlen($fileDir)-1] === '/') {
            $fileDir = substr($fileDir,0,-1);

            $this->removeGroupViewer($fileDir, $friendName);

            return 'Thành công';
        }

        $fileTarget = Files::where('name', $fileDir)->get()[0];

        $viewer = $this->removeIdUser($fileTarget->group_viewer, $friendName);

        $fileTarget->group_viewer = $viewer;
        $fileTarget->save();

        return 'Thành công';
    }

    private function getGroupJoined($groupId) {
        return Groups::find($groupId);
    }

    public function getListGroupJoined() {
        $user = User::find(Auth::user()->id);;
        $userGroupId = explode(' ',$user->group_joined);

        $groups = [];

        foreach ($userGroupId as $key => $groupId) {
            $groups[] = $this->getGroupJoined($groupId);
        }

        return $groups;
    }

    public function getListViewerOfGroup(Request $request) {
        $groupArr = $request->groupArr;
        $groupMemArr = [];
        foreach ($groupArr as $key => $groupName) {
            $group = Groups::where('name', $groupName)->get()[0]->members;
            $groupMemArr = array_merge($groupMemArr, explode(',', $group));
        }
        
        return array_unique($groupMemArr);
    }

    public function getListOwnershipGroup() {
        return Groups::where('owner', Auth::user()->name)->get();
    }

    public function getGroupDetail(Request $request) {
        $this->validate($request, [
            'groupId'   => 'required',
        ]);

        $groupId = $request->groupId;
        return Groups::find($groupId);
    }

    public function diasbleOpenLinkShare(Request $request) {
        $groupId = $request->groupId;

        $group = Groups::find($groupId);
        $group->is_open = false;
        $group->save();

        return "Thành công!";
    }

    public function enableOpenLinkShare(Request $request) {
        $groupId = $request->groupId;

        $group = Groups::find($groupId);
        $group->is_open = true;
        $group->save();

        return "Thành công!";
    }

    public function getUserInfo(Request $request) {
        $members = $request->members;
        $membersList = [];

        foreach ($members as $key => $member) {
            $membersList[] = User::where('name', $member)->get()[0];
        }
        
        return $membersList;
    }

    public function removeMember(Request $request) {
        $memberId = $request->memberId;
        $groupId = $request->groupId;

        $group = Groups::find($groupId);
        if ($group->owner === Auth::user()->name) {
            $member = User::find($memberId);
            $memberName = $member->name;
            $member->group_joined = trim(preg_replace('/\s+/', ' ', str_replace($groupId, '', $member->group_joined)));
            $member->save();

            $groupMembersArr = explode(',', $group->members);
            unset($groupMembersArr[array_search($memberName, $groupMembersArr)]);
            $group->members = implode(',', $groupMembersArr); 
            $group->number_mem = $group->number_mem - 1;
            $group->save();
        }        

        return 'Thành công!';
    }

    public function exitGroup(Request $request) {
        $groupId = $request->groupId;

        $group = $this->getGroupJoined($groupId);
        $groupMembersArr = explode(',', $group->members);
        unset($groupMembersArr[array_search(Auth::user()->name, $groupMembersArr)]);
        $group->members = implode(',', $groupMembersArr); 
        $group->number_mem = $group->number_mem - 1;
        $group->save();

        $user = User::find(Auth::user()->id);  
        $user->group_joined = trim(preg_replace('/\s+/', ' ', str_replace($groupId, '', $user->group_joined)));
        $user->save();
        
        return 'Thành công!';
    }


    public function removeGroup(Request $request) {
        $groupId = $request->groupId;

        $group = Groups::find($groupId);

        $groupMembersArr = explode(',', $group->members);
        foreach ($groupMembersArr as $key => $member) {    
            $userId = User::where('name', $member)->get()[0]->id;
            $user = User::find($userId);
            $user->group_joined = trim(preg_replace('/\s+/', ' ', str_replace($groupId, '', $user->group_joined)));
            $user->save();
        }

        $group->delete();

        return 'Thành công!';
    }

    public function joinGroupViaLink($inviteLink) {
        $group = Groups::where('invite_link', $inviteLink)->get()[0];
        if ($group->is_open && array_search(Auth::user()->name, explode(',', $group->members)) == false) {
            return view('user-function.inviteLink', ['title' => 'Lời mời']);
        } else {
            return redirect('group');
        }
    }

    public function getGroupDetailViaLink(Request $request) {
        $inviteLink = $request->inviteLink;

        return Groups::where('invite_link', $inviteLink)->get()[0];
    }

    public function accpetInvitation(Request $request) {
        $groupId = $request->groupId;

        $user = User::find(Auth::user()->id);

        $groupJoinedArr = explode(' ', $user->group_joined);
        $groupJoinedArr[] = $groupId;
         
        $user->group_joined = implode(' ', $groupJoinedArr);
        $user->save();

        $group = Groups::find($groupId);
        $groupMembersArr = explode(',', $group->members);
        $groupMembersArr[] = Auth::user()->name;
        $group->members = implode(',', $groupMembersArr); 
        $group->number_mem = $group->number_mem + 1;
        $group->save();

        return "Thành công!";
    }
}
