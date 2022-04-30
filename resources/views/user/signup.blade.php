@extends('layouts.index')

@section('itemSidebarSelected')
<input type="hidden" id="itemSidebar-Selected" value="login-btn">
@endsection

@section('content')
	@include('layouts.navbar')
	
	<link rel="stylesheet" type="text/css" href="css/form/login.css">

	<div class="col col-md-12 d-flex login-place">
		<form action="sign-up" method="POST" class="form-user-signUp card p-3">
		{{csrf_field()}}
			<div class="p-2">
				<h3 class="text-center">Đăng ký</h3>

				<div class="login-form">
					<div class="login-form--info d-flex">
						<div class="username w-50 me-2">
							<input type="text" name="name" class="form-control mt-3 w-100" placeholder="Nhập tên đăng nhập">
						</div>
						<div class="email w-50 ms-2">
							<input type="text" name="email" class="form-control mt-3 w-100" placeholder="Nhập email">
						</div>
					</div>

					<select name="provinces" class="provinces form-select mt-3">
						<option value="">--- Chọn Tỉnh/Thành Phố ---</option>
					</select>

					<select name="city" class="city form-select mt-3" disabled="">
						<option value="">--- Chọn Thành Phố/Quận Huyện ---</option>
					</select>

					<input type="password" name="password" class="form-control mt-3" placeholder="Nhập mật khẩu">
					<input type="password" name="passwordAgain" class="form-control mt-2" placeholder="Nhập lại mật khẩu">
				</div>

				@if (count($errors) > 0)
                    <div class="alert alert-danger mt-2">
                        @foreach ($errors->all() as $err)
                            {{$err}}<br/>
                        @endforeach
                    </div>
                @endif

                @if (session('thongbao'))
                    <div class="alert alert-success mt-2">
                        {{session('thongbao')}}
                    </div>
                @endif

				<input type="submit" class="btn btn-outline-primary form-control mt-4" value="Đăng ký">
			</div>

			<hr>

			<div class="redirect-register d-flex justify-content-center flex-column">
				<h3 class="text-center">Đã có tài khoản ?</h3>
				<a href="sign-in" class="btn btn-outline-success">Đăng nhập</a>
			</div>

		</form>
	</div>

	<script type="text/javascript" src="js/form/sign-up.js"></script>
@endsection