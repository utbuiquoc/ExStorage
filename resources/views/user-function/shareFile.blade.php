@extends('layouts.index')

@section('itemSidebarSelected')
	<input type="hidden" id="itemSidebar-Selected" value="file-btn">
@endsection

@section('content')
	@include('navbar.share-navbar')

	<link rel="stylesheet" type="text/css" href="css/user-function/share.css">
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

	<div class="wrapper-on">
		<div class="col col-md-3 d-flex flex-column sidebar-menu">
			<div class="type-selection flex-row">
				{{-- Get list of type (category) --}}

				<div class="minisize card-library w-100 text-center">
					<i class="bi bi-arrow-left type-selection__icon"></i>
				</div>
			</div>

			{{-- Current Dir --}}
			<input type="hidden" value="<?php echo $itemName; ?>" id="currentDir">
			
			<div class="selection d-flex flex-column">
				<div class="col col-md-12 card-library p-1 list-file h-100">

					<div class="item-list">
					<div class="card back-btn d-none mt-1 flex-row justify-content-center">
							<i class="bi bi-file-arrow-up me-2"></i>Trở lại
						</div>

						{{-- Get data file --}}
						<?php 
							$file = new App\Files;
							$item = $file->where('owner', $owner)->where('name', $itemName)->get()[0];
							
							$allcanview = $item->allcanview;
							$allowshare = $item->allowshare;

							$groupAllowed = $item->group_viewer;
							$groupAllowedArr = [];
							$group = new App\Groups;
							
							if ($groupAllowed != null) {
								foreach (explode('|', $groupAllowed) as $key => $groupEl) {
									$groupArr = $group->where('name', $groupEl)->get()[0]->members;
									$groupAllowedArr = array_merge($groupAllowedArr, explode(',', $groupArr));
								}
							}

							$limitedView = false;

							if ($allowshare) $limitedView = true;
							
							if ($limitedView) { // Nếu file bị giới hạn người xem
								$viewer = $item->viewer;
								
								$viewer = explode('|', $viewer);
								$viewer = array_unique(array_merge($groupAllowedArr, $viewer));
								
								if (Auth::user() !== null) {
									foreach ($viewer as $key => $value) {
										if ($value == Auth::user()->name) {
										?>
											<div class="dirItem file-item d-flex card mt-1">
												<input type="hidden" class="file-address" value="<?php echo $item->name; ?>">
												<input type="hidden" class="file-dir" value="<?php echo $item->dir; ?>">
												<div class="selector">
													<div class="file-type">
														<img src="img/docs-icon/<?php 
														if ($item->type == 'docx' || $item->type == 'doc') { echo 'docx-icon.png'; }
														else if ($item->type == 'pdf') { echo 'pdf-icon.png'; }
														else if ($item->type == 'xlsx' || $item->type == 'xls') { echo 'xlsx-icon.png'; }
														?>" alt="">
													</div>

													<div class="file-info">
														<h6 class="file-info__name"><?php echo $item->fileName; ?></h6>
														<div class="file-info__extends d-flex">
															<p class="file-info__time"><?php echo $item->created_at; ?></p>
															<p class="file-info__type">.<?php echo $item->type; ?> file</p>
														</div>
													</div>
												</div>
											</div>
										<?php
										}
									}
								}
							} else {
								if ($allcanview) {
									?>
									<div class="dirItem file-item d-flex card mt-1">
										<input type="hidden" class="file-address" value="<?php echo $item->name; ?>">
										<input type="hidden" class="file-dir" value="<?php echo $item->dir; ?>">
										<a href="fileUploaded/<?php echo $item->name ?>" class="download-doc" target="_blank"></a>
										<div class="selector">
											<div class="file-type">
												<img src="img/docs-icon/<?php 
												if ($item->type == 'docx' || $item->type == 'doc') { echo 'docx-icon.png'; }
												else if ($item->type == 'pdf') { echo 'pdf-icon.png'; }
												else if ($item->type == 'xlsx' || $item->type == 'xls') { echo 'xlsx-icon.png'; }
												?>" alt="">
											</div>

											<div class="file-info">
												<h6 class="file-info__name"><?php echo $item->fileName; ?></h6>
												<div class="file-info__extends d-flex">
													<p class="file-info__time"><?php echo $item->created_at; ?></p>
													<p class="file-info__type">.<?php echo $item->type; ?> file</p>
												</div>
											</div>
										</div>
									</div>
									<?php
								}
							}
						?>
					</div>

					<div class="exercise btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
						Đã làm xong bài tập?
					</div>
				</div>
			</div>
		</div>

		<div class="col col-md-9 card-library preview-docs">
			<iframe id="pdf-js-viewer" src="/viewer?file=blank.pdf" title="webviewer" frameborder="0" width="100%" height="100%"></iframe>
		</div>
	</div>

	<div class="modal fade overflow-hidden" tabindex='-1' id="uploadFileModal">
		<div class="modal-dialog modal-dialog-centered modal-upload-asn" style="max-width: 100vw !important;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Nộp bài</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<div id="drop-area">
						<form class="my-form">
							<p>Tải lên bài làm</p>

							<div class="input-group mb-3 d-none" id="ans-user">
								<span class="input-group-text" id="basic-addon1">Tên</span>
								<input type="text" class="form-control" placeholder="VD: Nguyễn Văn A" aria-label="Username" aria-describedby="basic-addon1">
							</div>

							<input type="file" id="fileElem" multiple accept="image/*,.doc,.docx,.xls,.xlsx,.pdf" onchange="handleFiles(this.files)">
							<label class="button" for="fileElem">Nhấp để chọn tệp tin</label>
						</form>

						<div id="gallery" >
							
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
				</div>
			</div>
		</div>
	</div>

	{{-- Toast --}}
	<div class="toast-container position-absolute p-3 end-0" id="toastPlacement" style="z-index: 99999">
		{{-- Success Toast --}}
		<div class="toast successToast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header bg-uploadSuccess text-white">
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
	<script type="text/javascript" src="js/user-function/shareFile.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@endsection