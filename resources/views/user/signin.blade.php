@extends('layouts.index')

@section('itemSidebarSelected')
<input type="hidden" id="itemSidebar-Selected" value="login-btn">
@endsection

@section('content')
	@include('layouts.navbar')
	
	<link rel="stylesheet" type="text/css" href="css/form/login.css">

	<div class="col col-md-7 d-flex">
		<div class="description-text">
			<h1 class="description-text__title">Ex Storage</h1>

			<h3>Website hỗ trợ dạy học online cho học sinh phổ thông.</h3>
		</div>
	</div>

	<div class="col col-md-5 d-flex login-place">
		<form action="sign-in" method="POST" class="form-user d-flex card p-3">
			{{csrf_field()}}
			<div class="p-2">
				<h3 class="text-center">Đăng nhập</h3>

				<div class="login-form">
					<input type="email" name="email" class="form-control mt-3" placeholder="Nhập email tài khoản">

					<input type="password" name="password" class="form-control mt-3" placeholder="Mật khẩu">
				</div>

				@if (count($errors) > 0)
                    <div class="alert alert-danger mt-2">
                        @foreach ($errors->all() as $err)
                            {{$err}}<br/>
                        @endforeach
                    </div>
                @endif

                @if (session('thongbao'))
                    <div class="alert alert-danger mt-2">
                        {{session('thongbao')}}
                    </div>
                @endif
                @if (session('thongbaodk'))
                    <div class="alert alert-success mt-2">
                        {{session('thongbaodk')}}
                    </div>
                @endif

				<input type="submit" class="btn btn-outline-primary form-control mt-4" value="Đăng nhập">
			</div>

			<hr>

			<div class="redirect-register d-flex justify-content-center">
				<a href="sign-up" class="btn btn-outline-success">Tạo tài khoản mới</a>
			</div>

		</form>
	</div>
@endsection