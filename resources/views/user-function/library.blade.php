@extends('layouts.index')

@section('itemSidebarSelected')
	<input type="hidden" id="itemSidebar-Selected" value="file-btn">
@endsection

@section('content')
	@include('navbar.home-navbar')

	<link rel="stylesheet" type="text/css" href="css/user-function/library.css">
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>

	<div class="wrapper-on">
		<div class="col col-md-3 d-flex flex-column sidebar-menu">
			<div class="type-selection flex-row">
				<div class="file-type-icon card-library">
					<i class="bi bi-gear-wide-connected type-selection__icon"></i>
				</div>

				<select class="form-select select-type" id="selection" onchange="genderChanged(this)">
					{{-- Get list of type (category) --}}
					<?php 
						$type = new App\Dir();
						//Set dữ liệu cho $typeDir = type đầu tiên
						if (!isset($typeDir)) {
							$typeDir = $type->where('owner', Auth::user()->name)->take(1)->get();
							$typeDir = $typeDir[0]->dir;			
						}
						$typeItem = $type->where('owner', Auth::user()->name)->where('parent', 'root')->get();
						foreach ($typeItem as $key => $value) {
							if ($typeDir == $value->dir) {
								$select = 'selected';
							} else {
								$select = '';
							}
							echo "<option ". $select ." value='$value->dir'>Loại: " . $value->dir . "</option>";
						}
					?>
				</select>

				<div class="minisize card-library">
					<i class="bi bi-arrow-left type-selection__icon"></i>
				</div>
			</div>

			{{-- Current Dir --}}
			<input type="hidden" value="<?php echo $typeDir; ?>" id="currentDir">

			{{-- Current User --}}
			<input type="hidden" value="<?php echo Auth::user()->name; ?>" id="currentUser">
			
			<div class="selection d-flex flex-column">
				<div class="col col-md-12 card-library p-1 list-file-column">
					<div class="card back-btn d-none mt-1 flex-row justify-content-center">
						<i class="bi bi-file-arrow-up me-2"></i>Trở lại
					</div>

					<span class="list-file">
						<?php 
							$folder = new App\Dir;
							$item = $folder->where('owner', Auth::user()->name)->where('parent', $typeDir)->get();
							foreach ($item as $key => $value) {
								$arrayP = explode('/', $value->dir);
								$folderName = end($arrayP);
								if (count($arrayP) >= 2) { ?>
									<div class="dirItem folder-item d-none card mt-1">
										<input type="hidden" class="folder-address" value="<?php echo $value->dir ?>">
										<input type="hidden" class="allcanview-file" value="<?php echo $value->allcanview; ?>">
										<input type="hidden" class="is-limited" value="<?php echo $value->allowshare; ?>">
										
										<div class="file-type">
											<img src="img/docs-icon/folder-icon.png" alt="">
										</div>

										<div class="file-info">
											<h6 class="file-info__name"><?php echo $folderName; ?></h6>
											<div class="file-info__extends d-flex">
												<p class="file-info__time"><?php echo $value->created_at; ?></p>
												<p class="file-info__type">Folder</p>
											</div>
										</div>

										<div class="file-share">
											<i class="option-btn bi bi-three-dots-vertical"></i>
											<div class="option-dialog d-none">
												<ul class="option-list">
													<li class="option-list__item share-folder-option"><i class="bi bi-share"></i><p class="option-text">Chia sẻ</p></li>
													<li class="option-list__item rename-folder-option"><i class="bi bi-pencil-square"></i><p class="option-text">Đổi tên</p></li>
													<li class="option-list__item remove-folder-option" ><i class="bi bi-trash"></i><p class="option-text">Xóa</p></li>
												</ul>
											</div>							
										</div>
									</div>
									<?php
								}
							}
						?>

						{{-- Get data file --}}
						<?php 
							$file = new App\Files;
							$item = $file->where('owner', Auth::user()->name)->where('parent', $typeDir)->get();
							if (count($item) > 0) foreach ($item as $key => $value) {
						?>

							<div class="dirItem file-item d-none card mt-1">
								<input type="hidden" class="file-address" value="<?php echo $value->name; ?>">
								<input type="hidden" class="file-dir" value="<?php echo $value->dir; ?>">
								<input type="hidden" class="allcanview-file" value="<?php echo $value->allcanview; ?>">
								<input type="hidden" class="is-limited" value="<?php echo $value->allowshare; ?>">
								<div class="selector">
									<div class="file-type">
										<img src="img/docs-icon/<?php 
										if ($value->type == 'docx' || $value->type == 'doc') { echo 'docx-icon.png'; }
										else if ($value->type == 'pdf') { echo 'pdf-icon.png'; }
										else if ($value->type == 'xlsx' || $value->type == 'xls') { echo 'xlsx-icon.png'; }
										 ?>" alt="">
									</div>

									<div class="file-info">
										<h6 class="file-info__name"><?php echo $value->fileName; ?></h6>
										<div class="file-info__extends d-flex">
											<p class="file-info__time"><?php echo $value->created_at; ?></p>
											<p class="file-info__type">.<?php echo $value->type ?> file</p>
										</div>
									</div>
								</div>

								<div class="file-share">
									<i class="option-btn bi bi-three-dots-vertical"></i>
									<div class="option-dialog d-none">
										<ul class="option-list">
											<li class="option-list__item share-option"><i class="bi bi-share"></i><p class="option-text">Chia sẻ</p></li>
											<li class="option-list__item rename-option"><i class="bi bi-pencil-square"></i><p class="option-text">Đổi tên</p></li>
											<li class="option-list__item remove-option" ><i class="bi bi-trash"></i><p class="option-text">Xóa</p></li>
										</ul>
									</div>							
								</div>
							</div>

						<?php } ?>
					</span>
				</div>

				<div class="card-library d-flex option">
					<div class="btn-group d-flex">
						<div class="add-option col col-sm-12">
							<button class="btn btn-outline-primary w-100 rounded-0"><i class="bi bi-plus-lg"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col col-md-9 card-library preview-docs">
			<iframe id="pdf-js-viewer" src="/viewer?file=blank.pdf" title="webviewer" frameborder="0" width="100%" height="100%"></iframe>
		</div>

		<div class="cover-page d-none"></div>

		<div class="modal-library" style="
		<?php
			if (count($errors) > 0 || session('thongbao')) {
				echo 'display: flex !important;';
			} else {
				echo 'display: none !important;';
			}
		?>
		">
			<div class="d-flex create-new-type card p-3" style='display: none !important;'>
				<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
				    <li class="nav-item" role="presentation">
				    	<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#createType" type="button" role="tab" aria-controls="home" aria-selected="true">Tạo loại mới</button>
				    </li>
				    <li class="nav-item" role="presentation">
				    	<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#removeType" type="button" role="tab" aria-controls="profile" aria-selected="false">Xóa loại</button>
				    </li>
				</ul>
				<hr class="bar" style="color: black">

				<div class="tab-content" id="myTabContent">

				    <div class="tab-pane fade show active" id="createType" role="tabpanel" aria-labelledby="home-tab">
				    	{{-- Upload File mới --}}
				    	<form action="createType" method="POST" class="d-flex flex-column">
							{{csrf_field()}}
							<div class="input-group type-input mt-3">
								<label class="input-group-text">Tên loại mới</label>
								<input type="text" class="form-control" name="newType">
							</div>

							<div class="form-check mt-2">
								<input type="checkbox" class="form-check-input" value='true' name='allcanview'>
								<label class="form-check-label">Cho phép chia sẻ</label>
							</div>

							<div class="form-submit d-flex mt-2">
								<div class="col col-md-6 pe-2">
									<input type="submit" class="btn btn-outline-primary form-control" value="Tạo mới">
								</div>
								<div class="col col-md-6 ps-2">
									<input type="button" class="btn btn-outline-danger form-control cancel-btn" value="Hủy bỏ">
								</div>
							</div>
						</form>
				    </div>

				    <div class="tab-pane fade" id="removeType" role="tabpanel" aria-labelledby="profile-tab">
					    {{-- Tạo thư mục mới --}}
					    <form action="removeType" method="POST" class="d-flex flex-column">
							{{csrf_field()}}
							<div class="type-input mt-3">
								<label class="form-label">Tên loại</label>
								<select class="form-select mb-1" name="dirToRemove" id="selection">
									{{-- Get list of type (category) --}}
									<?php 
										$type = new App\Dir();
										$typeItem = $type->where('owner', Auth::user()->name)->get();
										foreach ($typeItem as $key => $value) {
											$arrayP = explode('/', $value->dir);
											if (count($arrayP) === 1) {
												echo "<option selected value='$value->dir'>Loại: " . $value->dir . "</option>";
											}
										}
									?>
								</select>
							</div>

							<div class="form-submit d-flex mt-2">
								<div class="col col-md-6 pe-2">
									<input type="submit" class="btn btn-outline-primary form-control" value="Xóa">
								</div>
								<div class="col col-md-6 ps-2">
									<input type="button" class="btn btn-outline-danger form-control cancel-btn" value="Hủy bỏ">
								</div>
							</div>
						</form>
				    </div>
				</div>
			</div>

			{{-- Thông báo thêm --}}
			<div class="d-flex add-modal card p-3" style='display: none !important;'>
				<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
				    <li class="nav-item" role="presentation">
				    	<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#uploadFile" type="button" role="tab" aria-controls="home" aria-selected="true">Thêm tệp tin</button>
				    </li>
				    <li class="nav-item" role="presentation">
				    	<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#createFolder" type="button" role="tab" aria-controls="profile" aria-selected="false">Thêm thư mục</button>
				    </li>
				</ul>
				<hr class="bar" style="color: black">

				<div class="tab-content" id="myTabContent">

				    <div class="tab-pane fade show active" id="uploadFile" role="tabpanel" aria-labelledby="home-tab">
				    	{{-- Upload File mới --}}
				    	<form action="uploadFile" method="POST" enctype="multipart/form-data" onsubmit="return uploadFile()">
				    		{{csrf_field()}}
				    		<input type="hidden" class='selectValue currentDirUploadFile' name='currentDir'>
				    		<input type="hidden" name='rootDir' class="rootDirUploadFile" value="<?php echo $typeDir; ?>">
				    		<div class="input-group type-input mt-3">
								<input type="file" class="form-control file-upload-input" name="fileUpload" accept=".xls,.xlsx,.pdf,.docx,.doc">
							</div>

							<div class="form-check mt-2">
								<input type="checkbox" class="form-check-input allcanviewUploadFile" value='true' name='allcanview'>
								<label class="form-check-label">Cho phép chia sẻ</label>
							</div>

							<div class="progress progressUploadFile d-none">
							  	<div class="progress-bar progressBarUploadFile" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
							</div>

							<div class="form-submit d-flex mt-2">
								<div class="col col-md-6 pe-2">
									<input type="submit" class="btn btn-outline-primary form-control" value="Tải lên">
								</div>
								<div class="col col-md-6 ps-2">
									<input type="button" class="btn btn-outline-danger form-control cancel-add-btn" value="Hủy bỏ">
								</div>
							</div>
				    	</form>
				    </div>

				    <div class="tab-pane fade" id="createFolder" role="tabpanel" aria-labelledby="profile-tab">
					    {{-- Tạo thư mục mới --}}
					    <form action="createFolder" method="POST" onsubmit="return createFolder()">
					    	{{csrf_field()}}
				    		<input type="hidden" class='selectValue currentDirCreateFolder' name='currentDir'>
				    		<input type="hidden" class='rootDirCreateFolder' name='rootDir' value="<?php echo $typeDir; ?>">
					    	<div class="input-group type-input mt-3">
								<input type="text" class="form-control newFolderName" name="newFolder" placeholder="Nhập tên thư mục">
							</div>

							<div class="form-check mt-2">
								<input type="checkbox" class="form-check-input allcanviewCreateFolder" value='true' name='allcanview'>
								<label class="form-check-label">Cho phép chia sẻ</label>
							</div>

							<div class="form-submit d-flex mt-2">
								<div class="col col-md-6 pe-2">
									<input type="submit" class="btn btn-outline-primary form-control" value="Tạo mới">
								</div>
								<div class="col col-md-6 ps-2">
									<input type="button" class="btn btn-outline-danger form-control cancel-add-btn" value="Hủy bỏ">
								</div>
							</div>
					    </form>
				    </div>

				    
				</div>
			</div>

			{{-- Thông báo xác nhận (xóa) --}}
			<div class="d-flex confirm-modal card p-3 d-none">
				<div class="d-flex justify-content-between">
					<h4>Xác nhận</h4>
				</div>
				<hr class="bar" style="color: black"/>
				<div class="create-form">
					<form action="removeItem" method="POST" class="d-flex confirm-modal__form" onsubmit="return removeItem()">
						@csrf
						<input type="hidden" class='itemDir-selected itemDirSelectedRemoveItem' name="itemDirSelected">
						<input type="hidden" class='itemName-selected itemNameSelectedRemoveItem' name="itemNameSelected">
						<label for="" class="form-label">Bạn có chắc chắn?</label>
						<div class="yes-no-section d-flex">
							<div class="col col-md-6 d-flex">
								<button class="btn btn-outline-primary w-100 me-1" type="submit">Xóa</button>
							</div>
							<div class="col col-md-6 d-flex">
								<button class="btn btn-danger cancel-confirm w-100 ms-1" type="button">Hủy bỏ</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			{{-- Thông báo rename --}}
			<div class="d-flex rename-modal card p-3 d-none">
				<div class="d-flex justify-content-between">
					<h4>Đổi tên</h4>
				</div>
				<hr class="bar" style="color: black"/>
				<div class="create-form">
					<form action="renameItem" method="POST" class="d-flex confirm-modal__form" onsubmit="return renameItem()">
						@csrf
						<input type="hidden" class='itemDir-selected itemDirSelectedRenameItem' name="itemDirSelected">
						<input type="hidden" class='itemName-selected itemNameSelectedRenameItem' name="itemNameSelected">
						<label for="" class="form-label">Nhập tên muốn đổi</label>
						<input type="text" class="form-control newItemName" name="name">
						<div class="yes-no-section d-flex mt-3">
							<div class="col col-md-6 d-flex">
								<button class="btn btn-outline-primary w-100 me-1" type="submit">Đổi</button>
							</div>
							<div class="col col-md-6 d-flex">
								<button class="btn btn-danger cancel-rename w-100 ms-1" type="button">Hủy bỏ</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			{{-- Share Panel --}}
			<div class="d-flex share-modal card p-3 d-none">
				<div class="d-flex justify-content-between">
					<h4>Chia sẻ</h4>
				</div>
				<hr class="bar" style="color: black"/>
				<div class="create-form">
					<form action="renameItem" method="POST" class="d-flex confirm-modal__form">
						@csrf
						<input type="hidden" class='itemDir-selected' name="itemDirSelected">
						<input type="hidden" class='itemName-selected' name="itemNameSelected">
						<input type="hidden" class="isallcanview" name="allcanview">
						<div class="select-share-people mb-2 d-none">
							<p><i class="bi bi-person-plus-fill me-1"></i>Những ai có thể xem: </p>
							<input type="text" class="form-control">
						
						<hr class="m-0 mt-2" />
						</div>

						<div class="share-option-section mt-2">
							<select class="form-select optionSelect" autocomplete="off">
								<option class="private-option">Riêng tư</option>
								<option class="limited-option">Bị hạn chế</option>
								<option class="public-option">Với bất kỳ ai</option>
							</select>

							<div class="share-option-description p-1">
								<p class="private-option-description m-0 mt-1 d-none"><i class="bi bi-person-circle me-1"></i>Chỉ mình tôi được xem.</p>
								<p class="limited-option-description m-0 mt-1 d-none"><i class="bi bi-people-fill me-1"></i>Chỉ có những người được cho phép mới được xem.</p>
								<p class="public-option-description m-0 mt-1 d-none"><i class="bi bi-person-lines-fill me-1"></i>Tất cả ai có liên kết có thể xem.</p>
							</div>
						</div>

						<hr class="m-0 mb-2" />
						
						<div class="share-limited-section d-none flex-column">

							<label for="" class="form-label">Chia sẻ với mọi người</label>

							<div class="select-section">
								<input type="text" class="form-control friend-select"><span class="placeholder-input">Thêm</span>
								<div class="friend-added">
									
								</div>

								<div class="list-friend-selecton-limited">
									<div class="list-group list-select-friend d-none">
										
									</div>
								</div>
							</div>

							<hr class="m-0 mt-2 mb-2" />
						</div>		

						<div class="link-share-section d-flex flex-column">
							<label for="" class="form-label">Liên kết</label>

							<div class="d-flex">
								<input type="text" id="linkShare" class="form-control linkShare d-flex" readonly>
								<button type="button" class="btn btn-outline-success w-25 ms-1 copy-btn" data-clipboard-target="#linkShare">Sao chép</button>
							</div>

							<div class="mark-type form-switch mt-2">
								<input class="form-check-input" type="checkbox" role="switch" id="mark-as-exercise" checked>
								<label class="form-check-label" for="mark-as-exercise">Đánh dấu là bài tập</label>
							</div>
						</div>

						<div class="yes-no-section d-flex mt-3">
							<div class="col col-md-12 d-flex">
								<button class="btn btn-danger cancel-share w-100 ms-1" type="button">Thoát</button>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>

	{{-- Toast --}}
	<div class="toast-container position-absolute p-3 end-0" id="toastPlacement">
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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/user-function/library.js"></script>
@endsection