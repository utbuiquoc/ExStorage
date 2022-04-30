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
				<?php 
					$type = new App\Dir();
					//Set dữ liệu cho $typeDir = type đầu tiên
					if (!isset($typeDir) && Auth::user() !== null) {
						$typeDir = $type->where('owner', Auth::user()->name)->take(1)->get();
						$typeDir = $typeDir[0]->dir;
					}
				?>

				<div class="minisize card-library w-100 text-center">
					<i class="bi bi-arrow-left type-selection__icon"></i>
				</div>
			</div>

			{{-- Current Dir --}}
			<input type="hidden" value="<?php echo $folderInfo; ?>" id="currentDir">
			<div class="selection d-flex flex-column">
				<div class="col col-md-12 card-library p-1 list-file h-100">
					<div class="item-list">
						<div class="card back-btn d-none mt-1 flex-row justify-content-center">
							<i class="bi bi-file-arrow-up me-2"></i>Trở lại
						</div>

						<?php 
							$folder = new App\Dir;
							$item = $folder->where('owner', $owner)->where('parent', $folderParent)->get();
							
							foreach ($item as $key => $value) {
								$arrayP = explode('/', $value->dir);
								$folderName = end($arrayP);
								if (count($arrayP) >= 2 && $value->allcanview == true) { ?>
									<div class="dirItem folder-item d-none card mt-1">
										<input type="hidden" class="folder-address" value="<?php echo $value->dir ?>">
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
									</div>
									<?php
								} else if (count($arrayP) >= 2 && $value->allowshare == true && Auth::user() !== null) {
									if (strpos($value->viewer, Auth::user()->name) !== false) {?>
										<div class="dirItem folder-item d-none card mt-1">
											<input type="hidden" class="folder-address" value="<?php echo $value->dir ?>">
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
										</div>
										<?php
									}
								}
							}
						?>

						{{-- Get data file --}}
						<?php 
							$file = new App\Files;

							$item = $file->where('parent', $folderParent)->where('owner', $owner)->get();

							if (count($item) > 0) {
								foreach ($item as $key => $value) { 
									if ($value->allcanview == true) {?>
										<div class="dirItem file-item d-none card mt-1">
											<input type="hidden" class="file-address" value="<?php echo $value->name; ?>">
											<input type="hidden" class="file-dir" value="<?php echo $value->dir; ?>">
											<div class="selector">
												<div class="file-type">
													<img src="img/docs-icon/<?php 
													if ($value->type == 'docx') { echo 'docx-icon.png'; }
													else if ($value->type == 'pdf') { echo 'pdf-icon.png'; }
													else if ($value->type == 'xlsx') { echo 'xlsx-icon.png'; }
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
										</div>
									<?php 
									} else if (Auth::user() != null) {
											if ($value->allowshare == true && strpos($value->viewer, Auth::user()->name) !== false) {?>
											<div class="dirItem file-item d-none card mt-1">
												<input type="hidden" class="file-address" value="<?php echo $value->name; ?>">
												<input type="hidden" class="file-dir" value="<?php echo $value->dir; ?>">
												<div class="selector">
													<div class="file-type">
														<img src="img/docs-icon/<?php 
														if ($value->type == 'docx') { echo 'docx-icon.png'; }
														else if ($value->type == 'pdf') { echo 'pdf-icon.png'; }
														else if ($value->type == 'xlsx') { echo 'xlsx-icon.png'; }
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
											</div>
										<?php }
									}
								}
							} ?>
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

	<script type="text/javascript" src="js/user-function/shareFolder.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@endsection