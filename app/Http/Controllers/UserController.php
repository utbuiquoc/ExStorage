<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;

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
            'passwordAgain' => 'required|same:password'
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
            'passwordAgain.same'     => 'Mật khẩu nhập lại chưa khớp'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = md5($request->password);
        $user->save();

        return redirect('sign-in')->with('thongbao', 'Đăng kí thành công');
    }

    public function postSignin(Request $request) {
        // $this->validate($request, [
        //     'email'         => 'required|email',
        //     'password'      => 'required|min:6|max:32'
        // ], [
        //     'email.required'         => 'Bạn chưa nhập email',
        //     'email.email'            => 'Bạn chưa nhập đúng định dạng email',
        //     'password.required'      => 'Bạn chưa nhập mật khẩu',
        //     'password.min'           => 'Mật khẩu phải có ít nhất 6 ký tự',
        //     'password.max'           => 'Mật khẩu phải có tối đa nhất 32 ký tự'
        // ]);

        $email = $request->email;
        $password = md5($request->password);

        // $data = User::where('email', $email)->where('password', $password)->get();
        
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        $data = array(
            'email' => 'nguyenvanb@gmail.com',
            'password' => '123456'
        );

        echo '<pre>';
        print_r($data);
        echo '</pre>';

        var_dump(Auth::attempt($data));

        // if (Auth::attempt($data)) {
        //     echo 'Thanh cong';
        // } else {
        //     return redirect('sign-in')->with('thongbao', 'Đăng nhập thất bại');
        // }
    }
}
