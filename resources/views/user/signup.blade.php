@extends('layouts.index')

@section('itemSidebarSelected')
<input type="hidden" id="itemSidebar-Selected" value="login-btn">
@endsection

@section('content')
	@include('layouts.navbar')
	
	<link rel="stylesheet" type="text/css" href="css/form/login.css">

	<div class="col col-md-7 d-flex">
		<div class="description-text">
			<h1 class="description-text__title">Docs Storage</h1>

			<h3>Website giúp bạn lưu trữ, chia sẻ, xem tài liệu trực tuyến, hỗ trợ giáo viên và học sinh chuyển đổi số trong học tập, giảng dạy.</h3>
		</div>
	</div>

	<div class="col col-md-5 d-flex login-place">
		<form action="" method="POST" class="form-user d-flex card p-3">
			<div class="p-2">
				<h3 class="text-center">Đăng ký</h3>

				<div class="login-form">
					<input type="text" name="username" class="form-control mt-3" placeholder="Nhập tên đăng nhập">
					<input type="text" name="email" class="form-control mt-3" placeholder="Nhập email">

					<input type="text" name="username" class="form-control mt-3" placeholder="Nhập mật khẩu">
					<input type="text" name="username" class="form-control mt-2" placeholder="Nhập lại mật khẩu">
				</div>

				<input type="submit" class="btn btn-outline-primary form-control mt-4" value="Đăng nhập">
			</div>

			<hr>

			<div class="redirect-register d-flex justify-content-center flex-column">
				<h3 class="text-center">Đã có tài khoản ?</h3>
				<a href="login" class="btn btn-outline-success">Đăng ký</a>
			</div>

		</form>
	</div>
@endsection