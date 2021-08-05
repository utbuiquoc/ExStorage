@extends('layouts.index')

@section('itemSidebarSelected')
<input type="hidden" id="itemSidebar-Selected" value="home-btn">
@endsection
@section('content')
	@include('navbar.home-navbar')

	<link rel="stylesheet" type="text/css" href="css/home/style.css">

	<div class="container-fluid home-page">
		<div class="upload card p-2 mt-3">
			<h2 class="upload-text">Tải lên file mới ?</h2>

			<div class="upload-input">
				<form action="/" enctype="multipart/form-data" method="POST">
					{{csrf_field()}}

					@if (count($errors) > 0)
	                    <div class="alert alert-danger mt-2">
	                        @foreach ($errors->all() as $err)
	                            {{$err}}<br/>
	                        @endforeach
	                    </div>
	                @endif

	                @if (session('thongbao'))
	                    <div class="alert alert-danger mt-2">
	                        {{session('thongbao')}}
	                    </div>
	                @endif

					<script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
				  	<div class="image-upload-wrap">
				    	<input class="file-upload-input" type='file' name='fileUpload' onchange="readURL(this);" accept=".xls,.xlsx,.pdf,.docx"/>
				    	<div class="drag-text">
				      		<h3>Kéo thả hoặc nhấp vào để chọn tệp tin</h3>
				      		<p>Chỉ hỗ trợ docx, xlsx, xls, pdf</p>
				    		</div>
				  	</div>

				  	<div class="file-upload-content">
					    <div class="file-upload-section p-3">
					    	<div class="file-controll">
					    		<label class="form-control-label">Chọn nơi lưu trữ</label>
					    		<select name="mainDir" class="form-select mt-2">
					    			<option value="default" selected="">-- Mặc định --</option>
					    		</select>

					    		<label class="form-control-label mt-3">Chọn thư mục</label>
					    		<select name="folder" class="form-select mt-2">
					    			<option value="" selected="">-- Mặc định --</option>
					    		</select>
					    	</div>
					    	<div class="file-img">
					    		<img class="file-upload-type" src="img/docs-icon/document.png" alt="docs">
					    	</div>
					    	<div class="file-title-wrap">
					    		<div class="input-group">
					    			<label class="input-group-text">Tên tài liệu</label>
					    			<input type="text" name='docsName' class="form-control" placeholder="(Không bắt buộc)">
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

					<?php 
						$file = new App\File;
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

							<div class="file-item__action">
								<button class="btn btn-outline-primary">Mở</button>
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

	<script type="text/javascript" src="js/home/script.js"></script>
@endsection