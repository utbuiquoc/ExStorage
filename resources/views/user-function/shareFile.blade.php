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

							$limitedView = false;

							if ($allowshare) $limitedView = true;
							
							if ($limitedView) { // Nếu file bị giới hạn người xem
								$viewer = $item->viewer;
								
								$viewer = explode('|', $viewer);
								
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
														if ($item->type == 'docx') { echo 'docx-icon.png'; }
														else if ($item->type == 'pdf') { echo 'pdf-icon.png'; }
														else if ($item->type == 'xlsx') { echo 'xlsx-icon.png'; }
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
										<div class="selector">
											<div class="file-type">
												<img src="img/docs-icon/<?php 
												if ($item->type == 'docx') { echo 'docx-icon.png'; }
												else if ($item->type == 'pdf') { echo 'pdf-icon.png'; }
												else if ($item->type == 'xlsx') { echo 'xlsx-icon.png'; }
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
			<iframe id="pdf-js-viewer" src="/viewer?file=pdfFile/test2.pdf" title="webviewer" frameborder="0" width="100%" height="100%"></iframe>
		</div>
	</div>

	<div class="modal fade" tabindex='-1' id="uploadFileModal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Modal title</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<div id="drop-area">
						<form class="my-form">
							<p>Tải lên bài làm</p>
							<input type="file" id="fileElem" multiple accept="image/*,.doc,.docx,.pdf" onchange="handleFiles(this.files)">
							<label class="button" for="fileElem">Nhấp để chọn tệp tin</label>
						</form>

						<div id="gallery" >
							
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Nộp bài</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/user-function/shareFile.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@endsection