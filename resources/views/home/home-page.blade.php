@extends('layouts.index')

@section('content')
	<link rel="stylesheet" type="text/css" href="css/home/style.css">

	<div class="container-fluid home-page">
		<div class="upload card p-2 mt-3">
			<h2 class="upload-text">Tải lên file mới ?</h2>

			<div class="upload-input">
				<form action="">
					<script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
				  	<div class="image-upload-wrap">
				    	<input class="file-upload-input" type='file' onchange="readURL(this);" accept=".xls,.xlsx,.pdf,.docx"/>
				    	<div class="drag-text">
				      		<h3>Kéo thả hoặc nhấp vào để chọn tệp tin</h3>
				      		<p>Chỉ hỗ trợ docx, xlsx, xls, pdf</p>
				    		</div>
				  	</div>

				  	<div class="file-upload-content">
					    <div class="file-upload-section p-3">
					    	<div class="file-controll">
					    		<label class="form-control-label">Chọn nơi lưu trữ</label>
					    		<select name="category" class="form-select mt-2">
					    			<option value="1">Mục 1 : Tài liệu giảng</option>
					    			<option value="2">Mục 2 : Môn học</option>
					    			<option value="3">Mục 3 : Tài liệu cho học sinh</option>
					    		</select>

					    		<label class="form-control-label mt-3">Chọn thư mục</label>
					    		<select name="category" class="form-select mt-2">
					    			<option value="1">Mục 1 : Tài liệu giảng</option>
					    			<option value="2">Mục 2 : Môn học</option>
					    			<option value="3">Mục 3 : Tài liệu cho học sinh</option>
					    		</select>
					    	</div>
					    	<div class="file-img">
					    		<img class="file-upload-type" src="img/docs-icon/document.png" alt="docs">
					    	</div>
					    	<div class="file-title-wrap">
					    		<div class="input-group">
					    			<label class="input-group-text">Tên tài liệu</label>
					    			<input type="text" class="form-control" placeholder="Nhập tên">
					    		</div>
					      		<button type="button" onclick="removeUpload()" class="remove-file m-auto">Remove <span class="image-title">Uploaded Image</span></button>
					      		<button type="submit" class="upload-file m-auto">Tải lên</button>
					    	</div>
					    </div>
				  	</div>
				</form>
			</div>

		</div>

		<div class="container-fluid mt-2 mb-2">
			<div class="file-uploaded-recently m-auto card">
				<div class="col col-md-8 m-auto">
					<div class="btn-group d-flex mt-2">
						<button type="button" class="btn btn-outline-primary active" href="" class="file-tab text-center m-2">Gần đây</button>
						<button type="button" class="btn btn-outline-primary" href="" class="file-tab text-center m-2">Đã đánh dấu</button>
					</div>

					<div class="list-file-recently card p-2 m-2">
						<div class="file-item">
							<div class="file-item__img">
								<img src="img/docs-icon/docx-icon.png" alt="docx-icon">
							</div>

							<div class="file-item__content">
								<h5>Bài giảng 2.docx</h5>
								<div class="file-item__content-location">
									<p>Nơi chứa: </p>
									<span>Tài liệu giảng » Môn học</span>
								</div>
							</div>

							<div class="file-item__timeuploaded">
								<span>22/07/2021</span>
							</div>

							<div class="file-item__action">
								<button class="btn btn-outline-primary">Mở</button>
							</div>
						</div>
					</div>

					<div class="list-file-recently card p-2 m-2">
						<div class="file-item">
							<div class="file-item__img">
								<img src="img/docs-icon/docx-icon.png" alt="docx-icon">
							</div>

							<div class="file-item__content">
								<h5>Bài giảng 2.docx</h5>
								<div class="file-item__content-location">
									<p>Nơi chứa: </p>
									<span>Tài liệu giảng » Môn học</span>
								</div>
							</div>

							<div class="file-item__timeuploaded">
								<span>22/07/2021</span>
							</div>

							<div class="file-item__action">
								<button class="btn btn-outline-primary">Mở</button>
							</div>
						</div>
					</div>

					<div class="list-file-recently card p-2 m-2">
						<div class="file-item">
							<div class="file-item__img">
								<img src="img/docs-icon/docx-icon.png" alt="docx-icon">
							</div>

							<div class="file-item__content">
								<h5>Bài giảng 2.docx</h5>
								<div class="file-item__content-location">
									<p>Nơi chứa: </p>
									<span>Tài liệu giảng » Môn học</span>
								</div>
							</div>

							<div class="file-item__timeuploaded">
								<span>22/07/2021</span>
							</div>

							<div class="file-item__action">
								<button class="btn btn-outline-primary">Mở</button>
							</div>
						</div>
					</div>

					<div class="list-file-recently card p-2 m-2">
						<div class="file-item">
							<div class="file-item__img">
								<img src="img/docs-icon/docx-icon.png" alt="docx-icon">
							</div>

							<div class="file-item__content">
								<h5>Bài giảng 2.docx</h5>
								<div class="file-item__content-location">
									<p>Nơi chứa: </p>
									<span>Tài liệu giảng » Môn học</span>
								</div>
							</div>

							<div class="file-item__timeuploaded">
								<span>22/07/2021</span>
							</div>

							<div class="file-item__action">
								<button class="btn btn-outline-primary">Mở</button>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/home/script.js"></script>
@endsection