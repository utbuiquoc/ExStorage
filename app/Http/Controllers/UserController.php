<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\File;
use App\Dir;
use App\Friends;

class UserController extends Controller
{
    //
    public function getdata() {
        $list = User::all();
        var_dump($list);
    }

    public function postSignup(Request $request) {
        $this->validate($request, [
            'name'          => 'required|min:3|',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|max:32',
            'passwordAgain' => 'required|same:password',
            'provinces'     => 'required',
            'city'          => 'required'
        ], [
            'name.required'          => 'Bạn chưa nhập tên người dùng',
            'name.min'               => 'Tên người dùng phải có ít nhất 3 ký tự',
            'email.required'         => 'Bạn chưa nhập email',
            'email.email'            => 'Bạn chưa nhập đúng định dạng email',
            'email.unique'           => 'Email đã tồn tại',
            'password.required'      => 'Bạn chưa nhập mật khẩu',
            'password.min'           => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max'           => 'Mật khẩu phải có tối đa nhất 32 ký tự',
            'passwordAgain.required' => 'Bạn chưa nhập lại mật khẩu',
            'passwordAgain.same'     => 'Mật khẩu nhập lại chưa khớp',
            'provinces.required'     => 'Vui lòng chọn Tỉnh/Thành phố',
            'city.required'          => 'Vui lòng chọn thành phố/quận huyện'
        ]);

        $location = $request->provinces . ', ' . $request->city;

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->location = $location;
        $user->save();

        $dir = new dir;
        $dir->parent = 'root';
        $dir->owner = $request->name;
        $dir->viewer = $request->name;
        $dir->save();

        $friendsDB = new Friends;
        $friendsDB->user = $request->name;
        $friendsDB->save();

        return redirect('sign-in')->with('thongbaodk', 'Đăng kí thành công');
    }

    public function postSignin(Request $request) {
        $this->validate($request, [
            'email'         => 'required|email',
            'password'      => 'required|min:6|max:32'
        ], [
            'email.required'         => 'Bạn chưa nhập email',
            'email.email'            => 'Bạn chưa nhập đúng định dạng email',
            'password.required'      => 'Bạn chưa nhập mật khẩu',
            'password.min'           => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max'           => 'Mật khẩu phải có tối đa nhất 32 ký tự'
        ]);

        $email = $request->email;
        $password = $request->password;
        $data = array(
            'email' => $email,
            'password' => $password
        );
        if (Auth::attempt($data)) {
            return redirect()->back();
        } else {
            return redirect('sign-in')->with('thongbao', 'Đăng nhập thất bại');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('sign-in');
    }

    public function shareFile($owner, $itemName) {
        $title = explode('_', $itemName);
        array_shift($title);
        $title = implode('_', $title);

        return view('user-function.shareFile', ['owner' => $owner, 'itemName' => $itemName, 'title' => $title]);
    }

    public function shareFolder($owner = null, $itemDir = null) {
        if ($itemDir) {
            $itemDirArray = explode('/', $itemDir);
            
            // echo "<pre>";
            // print_r($itemDir);
            // echo "</pre>";

            // // $fileName = explode('_', end($itemDir));
            // // array_shift($fileName);
            // $fileName = implode('', $fileName);
            // echo $itemDir;
            return view('user-function.shareFolder', ['title' => end($itemDirArray), 'folderInfo' => $itemDir, 'owner' => $owner, 'folderParent' => reset($itemDirArray)]);
        } else {
            return view('user-function.shareFolder', ['title' => 'Share']);
        }
    }

   protected function removeIdUser($str, $id) {
        $strArr = explode('|', $str);

        foreach ($strArr as $key => $value) {
            if ($value == $id) {
                unset($strArr[$key]);

                return implode('|', $strArr);
            }
        }

        return '';
    }

    public function testGet() {
        return view("test");
    }

    public function testPost(Request $request) {
        // $user = new User;
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->password = $request->password;
        // $user->save();

        // // return $request->name;
        // echo 'Thành công';

        $file = $request->file('fileUpload');

        if ($request->docsName != '') {
            $fileName = $request->docsName;
        } else {
            $fileName = $file->getClientOriginalName();
        }

        
        $fileExtension = $file->getClientOriginalExtension();

        // Tạo tên file
        $pages = range(1,20);
        shuffle($pages);
        $prefix = array_shift($pages);
        if ($request->docsName != '') {
            $fileNameToSave = time() . Auth::user()->id . $prefix . '_' . $request->docsName;
        } else {
            $fileNameToSave = time() . Auth::user()->id . $prefix . '_' . $fileName;
        }

        $file->move('fileUploaded', $fileNameToSave);

        echo "Thành công";
    }
}
