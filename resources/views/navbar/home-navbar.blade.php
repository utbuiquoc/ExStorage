@extends('layouts.navbar')

@section('center')
	<form action="" method="POST" class="d-flex input-group search-form">
		<div class="input-group-text"><i class="bi bi-search"></i></div>
		<input class="form-control me-2" type="search" placeholder="Tìm kiếm tài liệu, bạn bè, nhóm,..." aria-label="Search">
	</form>
@endsection

@section('right')
	<div class="user__info">
		<h6 class="user__name">{{Auth::user()->name}}</h6>
		<p class="user__id">#{{Auth::user()->id}}</p>
	</div>
	<div class="user__avatar">
		<img src="img/avatar/default/default-avatar.png" alt="default-avatar" class="user__img">
	</div>
@endsection