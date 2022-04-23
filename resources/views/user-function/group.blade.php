@extends('layouts.index')

@section('itemSidebarSelected')
	<input type="hidden" id='itemSidebar-Selected' value="group-btn">
@endsection

@section('content')
	@include('navbar.group-navbar')

	<link rel="stylesheet" type="text/css" href="css/user-function/group.css">
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>

	<div class="wrapper-on">
		<div class="col col-md-3 card-library">
			<div class="heading d-flex flex-column p-2">
				<div class="heading-info d-flex justify-content-center">
					<div class="friend-header">
						<div class="btn-group">
							<button class="btn btn-primary joined-group-btn">Nhóm đã tham gia</button>
							<button class="btn btn-primary ownership-group-btn">Nhóm đã tạo</button>
						</div>
					</div>
				</div>

				<hr class="m-0 mt-2" />
			</div>

			<div class="joined-group ps-2 pe-2 overflow-auto d-flex">

			</div>

			<div class="ownership-group ps-2 pe-2 overflow-auto d-none">
						
			</div>
		</div>

		<div class="col col-md-9 d-flex justify-content-center flex-row p-3">
			<div class="friend-finded w-75 card p-3">
				<div class="friend__heading">
					<h4>Tạo nhóm mới</h4>
					<hr/>
				</div>

				<div class="friend__list p-1">
                    <div class="mb-3">
                        <label class="form-label">Tên nhóm</label>
                        <input type="text" class="form-control group-name" placeholder="Ví dụ: Nhóm học tập,...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thành viên</label>
                        <input type="text" class="form-control  list-user" readonly='' placeholder="@Nguyễn Văn A">
                    </div>

                    <form action="find-friend" method="POST" class="d-flex input-group search-form" onsubmit="return searchFriend()">
                        <div class="input-group-text"><i class="bi bi-search"></i></div>
                        <input class="form-control find-input" type="search" placeholder="Tìm kiếm (tên tài khoản, email hoặc id)" aria-label="Search">
                    </form>
                    <div class="card list-friend p-1">
											
                    </div>
					<button class="btn btn-primary mt-2 create-new-group-btn">Tạo mới</button>
				</div>
			</div>
		</div>
	</div>

	<div id='unfriend-modal' class="modal fade" tabindex="-1" style="z-index: 99999;">
		<input type="hidden" id="friend-info--id">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header p-3">
					<h5 class="modal-title">Nhóm #<span class='id-group'>2</span></h5>
					<h6 class="modal-title">Nhóm trưởng: <span class='owner-name'>utbuiquoc</span></h6>
				</div>
				<div class="modal-body">
					<div class="name-group">
						<label for="group-name__input" class="form-label">Tên nhóm</label>
						<input type="text" class="form-control" id='group-name__input' readonly=''>
					</div>

					<div class="mem-group mt-2">
						<label for="group-name__members" class="form-label">Thành viên (<span class='number_of_members'>2</span>)</label>
						<span class='d-flex'>	
							<input type="text" class="form-control" id='group-name__members' readonly=''>
							<button class="btn btn-outline-success ms-1 show-members-list--btn" data-bs-toggle="modal" data-bs-target="#members-list">Chỉnh sửa</button>
						</span>
					</div>
					
					<div class="link-group mt-2">
						<label for="group-name__join-link" class="form-label">Liên kết tham gia nhóm</label>
						<span class='d-flex'>	
							<input type="text" class="form-control" id='group-name__join-link' readonly=''>
							<button class="btn btn-outline-success ms-1" data-clipboard-target="#group-name__join-link" id="copy-btn">Sao chép</button>
						</span>
						<div class="allow-join form-switch mt-1">
							<input id='allow-join-via-link' type="checkbox" role="switch" class="form-check-input" disabled>
							<label for='allow-join-via-link'>Mở liên kết tham gia</label>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close-yn-modal" data-bs-dismiss="modal">Đóng</button>
					<button type="button" class="btn btn-danger exit-group-btn">Rời nhóm</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal tùy chỉnh danh sách thành viên -->
	<div class="modal fade" id="members-list" data-bs-backdrop="static" data-bs-keyboard="false"  aria-hidden="true" tabindex="-1" style="z-index: 99999">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Danh sách thành viên</h5>
					<button type="button" class="btn-close" data-bs-target="#unfriend-modal" data-bs-toggle="modal"></button>
				</div>

				<div class="modal-body list-members-modal">
					
				</div>

				<div class="modal-footer">
					<button class="btn btn-primary" data-bs-target="#unfriend-modal" data-bs-toggle="modal">Trở lại</button>
				</div>
			</div>
		</div>
	</div>


	{{-- Toast --}}
	<div class="toast-container position-absolute p-3 end-0" id="toastPlacement" style='z-index: 9999999'>
		{{-- Success Toast --}}
		<div class="toast sucessToast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header bg-uploadSucess text-white">
				<i class="bi bi-check-circle-fill me-2 toast-icon"></i>
				<p class="me-auto toast-notifi"></p>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>

		{{-- Error Toast --}}
		<div class="toast errorToast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header bg-danger text-white">
				<i class="bi bi-exclamation-circle me-2 toast-icon"></i>
				<p class="me-auto toast-notifi"></p>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>

		{{-- Warning Toast --}}
		<div class="toast warningToast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header bg-warning text-white">
				<i class="bi bi-exclamation-circle me-2 toast-icon"></i>
				<p class="me-auto toast-notifi"></p>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script type="text/javascript"  type="module" src=js/user-function/group.js></script>
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
@endsection