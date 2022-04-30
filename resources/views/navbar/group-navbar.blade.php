@extends('layouts.navbar')

@section('center')
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