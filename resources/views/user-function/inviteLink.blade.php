@extends('layouts.blank')

@section('content')
    <link rel="stylesheet" type="text/css" href="css/user-function/inviteLink.css">
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <div class="wrapper">
        <div class="col col-md-5 d-flex flex-column justify-content-center">
            <div class="invite text-center p-5">
                <div class="invite__icon pb-3">
                    <img class="invite__icon--tag" src="img/avatar/default/default-group-avt.jpg" alt="group-icon">
                </div>

                <div class="invite__content">
                    <p class="invite-content"><span class="group-owner">utbuiquoc</span> mời bạn vào nhóm</p>
                    <h2 class="group-name">Nhóm học tập</h2>

                    <div class="group-info p-3">
                        <div class="btn btn-secondary number--members">
                            Số thành viên <span class="badge bg-info"><span class="number-members">4</span></span>
                        </div>

                        <div class="btn btn-secondary group--id">
                            Id nhóm <span class="badge bg-warning">#<span class="id-group">4</span></span>
                        </div>
                    </div>
                </div>

                <div class="invite__acp">
                    <button class="btn btn-primary w-100 acp-invitation--btn">Chấp nhận</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="js/user-function/inviteLink.js"></script>
@endsection