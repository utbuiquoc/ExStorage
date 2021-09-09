@extends('layouts.index')

@section('itemSidebarSelected')
	<input type="hidden" id='itemSidebar-Selected' value="friend-btn">
@endsection

@section('content')
	@include('navbar.home-navbar')

	<link rel="stylesheet" type="text/css" href="css/user-function/friend.css">
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

	<div class="wrapper-on">
		<div class="col col-md-3 card-library">
			<div class="friend-header p-3">
				<h4 class="friend-header__heading">Bạn bè</h4>
				<p class="friend-header__p">Những người bạn có thể biết</p>
				<hr class="m-0" />
			</div>

			<div class="friend-list p-2 d-flex flex-column">
				<div class="friend-item card p-1 d-flex flex-row w-100 mt-2">
					<div class="friend-item__avt">
						<img src="img/avatar/nltt.jpg" alt="avatar" class="avatar">
					</div>

					<div class="friend-item__more w-100 p-1">
						<div class="friend-item__info d-flex justify-content-between">
							<h6 class="friend-item__info--name"><strong>Ngô Lê Tấn Tài</strong></h6>
							<p class="friend-item__info--id">#3</p>
						</div>

						<div class="friend-item__confirm mt-1">
							<form action="confirm-friend" class="friend-confirm__form d-flex">
								<div class="btn-confirm w-50 pe-1 d-flex">
									<button class="btn btn-primary w-100">Xác nhận</button>
								</div>
								<div class="btn-refuse w-50 ps-1 d-flex">
									<button class="btn btn-secondary w-100">Từ chối</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="friend-item card p-1 d-flex flex-row w-100 mt-2">
					<div class="friend-item__avt">
						<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
					</div>

					<div class="friend-item__more w-100 p-1">
						<div class="friend-item__info d-flex justify-content-between">
							<h6 class="friend-item__info--name"><strong>Nguyễn Văn A</strong></h6>
							<p class="friend-item__info--id">#4</p>
						</div>

						<div class="friend-item__confirm mt-1">
							<form action="confirm-friend" class="friend-confirm__form d-flex">
								<div class="btn-confirm w-50 pe-1 d-flex">
									<button class="btn btn-primary w-100">Xác nhận</button>
								</div>
								<div class="btn-refuse w-50 ps-1 d-flex">
									<button class="btn btn-secondary w-100">Từ chối</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col col-md-9 card-library">
			
		</div>
	</div>

	<script type="text/javascript" src=js/user-function/friend.js></script>
@endsection