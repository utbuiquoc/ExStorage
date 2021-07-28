@extends('layouts.index')

@section('itemSidebarSelected')
	<input type="hidden" id="itemSidebar-Selected" value="file-btn">
@endsection

@section('content')
	@include('navbar.home-navbar')

	<link rel="stylesheet" type="text/css" href="css/user-function/library.css">
	<link rel="stylesheet" href="pdfjs/web/viewer.css">

	<div class="wrapper-on">
		<div class="col col-md-4 d-flex flex-column sidebar-menu">
			<div class="type-selection flex-row">
				<div class="file-type-icon card-library">
					<i class="bi bi-files-alt type-selection__icon"></i>
				</div>
				<select class="form-select select-type">
					<option selected>Thể loại: Tài liệu dạy học</option>
					<option value="1">One</option>
					<option value="2">Two</option>
					<option value="3">Three</option>
				</select>

				<div class="minisize card-library">
					<i class="bi bi-arrow-left type-selection__icon"></i>
				</div>
			</div>
			
			<div class="selection d-flex">
				<div class="col col-md-12 card-library p-1 list-file">
					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6Giáo án ngữ văn 6Giáo án ngữ văn 6Giáo án ngữ văn 6Giáo án ngữ văn 6Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item d-flex card mt-1">
						<div class="file-type">
							<img src="img/docs-icon/docx-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Giáo án ngữ văn 6</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">07/07/2021</p>
								<p class="file-info__type">.docx file</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>

					<div class="file-item folder d-flex card mt-1">
						<div class="file-type folder-img">
							<img src="img/docs-icon/folder-icon.png" alt="">
						</div>

						<div class="file-info">
							<h6 class="file-info__name">Đề cương cho học sinh</h6>
							<div class="file-info__extends d-flex">
								<p class="file-info__time">08/07/2021</p>
								<p class="file-info__type">Folder</p>
							</div>
						</div>

						<div class="file-share">
							<i class="bi bi-share"></i>
						</div>
					</div>
				</div>

				<div class="{{-- col col-md-6 --}} card-library p-1 list-file">
				
				</div>
			</div>
		</div>

		<div class="col col-md-8 card-library preview-docs">
			<iframe id="pdf-js-viewer" src="/viewer?file=pdfFile/test2.pdf" title="webviewer" frameborder="0" width="100%" height="100%"></iframe>
		</div>

	<script type="text/javascript" src="js/user-function/library.js"></script>
@endsection