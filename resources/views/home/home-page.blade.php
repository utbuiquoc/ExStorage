@extends('layouts.index')

@section('itemSidebarSelected')
<input type="hidden" id="itemSidebar-Selected" value="home-btn">
@endsection
@section('content')
	@include('navbar.home-navbar')

	<link rel="stylesheet" type="text/css" href="css/home/style.css">
	<link rel="stylesheet" type="text/css" href="css/user-function/group.css">
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

	<div class="container-fluid home-page">
		<div class="upload col col-md-6 d-flex p-3 mt-2 flex-column">
			<div class="d-flex">
				<div class="user-info card d-flex flex-row p-2 m-2" style="flex: 5">
					<div class="user-info__avt p-2">
						<img src="img/avatar/default/default-avatar.png" alt="">
					</div>

					<div class="user-info__info m-auto">
						<h5>Tên tài khoản: <?php echo Auth::user()->name; ?></h5>
						<h6 class='user-info--id'>Id tài khoản: #<?php echo Auth::user()->id; ?></h6>
						<h6 class='user-info--email'>Email: <?php echo Auth::user()->email; ?></h6>
					</div>
				</div>

				<div class="user-info card d-flex flex-coulm justify-content-center p-2 m-2" style="flex: 4">
					<h3>Số lượng bạn bè: <span class="number-of-friend text-primary">0</span></h3>
					<h3>Lời mời kết bạn <span class="friend-request text-primary">0</span></h3>
				</div>
			</div>

			<div class="file-uploaded-recently h-100 m-2">
				<div class="card h-100">
					<div class="mt-auto mb-auto ms-3 me-3">
						<div class="btn-group d-flex mt-2">
							<button type="button" class="btn btn-outline-primary active" href="" class="file-tab text-center m-2">Gần đây</button>
						</div>

						<?php 
							$file = new App\Files;
							$item = $file->orderBy('id', 'desc')->where('owner', Auth::user()->name)->take(5)->get();
							if (count($item) > 0) foreach ($item as $key => $value) {
						?>

						<div class="list-file-recently card p-2 m-2">
							<div class="file-item">
								<div class="file-item__img">
									<img src="img/docs-icon/<?php 
									if ($value->type == 'docx') { echo 'docx-icon.png'; }
									else if ($value->type == 'pdf') { echo 'pdf-icon.png'; }
									else if ($value->type == 'xlsx') { echo 'xlsx-icon.png'; }
									?>" alt="docx-icon">
								</div>

								<div class="file-item__content">
									<h5><?php echo $value->fileName; ?></h5>
									<div class="file-item__content-location">
										<p>Nơi chứa:</p>
										<span class="dir"><?php echo $value->dir ?></span>
									</div>
								</div>

								<div class="file-item__timeuploaded">
									<span><?php echo $value->created_at; ?></span>
								</div>
							</div>
						</div>

						<?php } else { ?>
							<div class="card notificate m-3">
								<h3>Bạn chưa tải lên tài liệu nào cả!</h3>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid mt-2 col col-md-6 mb-2 flex-row d-flex">
			<div class="group-joined card p-3 m-3">
				<div class="group-joined__text">
					<h3>Nhóm đã tham gia</h3>
				</div>

				<div class="group-joined-list">
					
				</div>
			</div>

			<div class="group-created card p-3 m-3">
				<div class="group-created__text">
					<h3>Nhóm đã tạo</h3>
				</div>

				<div class="group-created-list">
					
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/home/script.js"></script>
@endsection