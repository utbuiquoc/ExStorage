@extends('layouts.navbar')

@section('center')
@endsection

@section('right')
    <?php
        if (Auth::user() !== null) {
            ?>
            <div class="user__info">
                <h6 class="user__name">{{Auth::user()->name}}</h6>
                <p class="user__id">#{{Auth::user()->id}}</p>
            </div>
            <?php
        } else {
            ?>
            <div class="user__info">
                <h6 class="user__name">Khách</h6>
                <p class="user__id">#0</p>
            </div>
            <?php
        }
    ?>
    
    <div class="user__avatar">
        <img src="img/avatar/default/default-avatar.png" alt="default-avatar" class="user__img">
    </div>
@endsection