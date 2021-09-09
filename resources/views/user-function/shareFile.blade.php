@extends('layouts.index')

@section('itemSidebarSelected')
	<input type="hidden" id="itemSidebar-Selected" value="file-btn">
@endsection

@section('content')
	@include('navbar.home-navbar')

	<link rel="stylesheet" type="text/css" href="css/user-function/share.css">

	<div class="wrapper-on">
		<div class="col col-md-4 d-flex flex-column sidebar-menu">
			<div class="type-selection flex-row">
				{{-- Get list of type (category) --}}

				<div class="minisize card-library w-100 text-center">
					<i class="bi bi-arrow-left type-selection__icon"></i>
				</div>
			</div>

			{{-- Current Dir --}}
			
			<div class="selection d-flex flex-column">
				<div class="col col-md-12 card-library p-1 list-file h-100">

					<div class="card back-btn d-none mt-1 flex-row justify-content-center">
						<i class="bi bi-file-arrow-up me-2"></i>Trở lại
					</div>

					{{-- Get data file --}}
					<?php 
						$file = new App\Files;

						$item = $file->where('owner', $owner)->where('name', $itemName)->where('allcanview', true)->get();
						if (count($item) > 0) foreach ($item as $key => $value) {
					?>

						<div class="dirItem file-item d-flex card mt-1">
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

					<?php } ?>
				</div>
			</div>
		</div>

		<div class="col col-md-8 card-library preview-docs">
			<iframe id="pdf-js-viewer" src="/viewer?file=pdfFile/test2.pdf" title="webviewer" frameborder="0" width="100%" height="100%"></iframe>
		</div>
	</div>

	<script type="text/javascript" src="js/user-function/share.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@endsection