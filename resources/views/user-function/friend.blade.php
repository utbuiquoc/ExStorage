@extends('layouts.index')

@section('itemSidebarSelected')
	<input type="hidden" id='itemSidebar-Selected' value="friend-btn">
@endsection

@section('content')
	@include('navbar.friend-navbar')

	<link rel="stylesheet" type="text/css" href="css/user-function/friend.css">
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

	<div class="wrapper-on">
		<div class="col col-md-3 card-library">
			<div class="heading d-flex flex-column p-2">
				<div class="heading-info d-flex">
					<div class="back-btn d-none flex-column justify-content-center">
						<i class="bi bi-arrow-left-short back-btn__icon"></i>
					</div>

					<div class="friend-header p-3">
						<h4 class="friend-header__heading">Bạn bè</h4>
						<p class="friend-request__heading m-0 d-none">Lời mời kết bạn</p>
						<p class="friend-accepted__heading m-0 d-none">Danh sách bạn bè</p>
					</div>
				</div>

				<hr class="m-0 mt-2" />
			</div>

			<div class="friend-list-option p-2 d-flex flex-column">
				<div class="option-item friend-request card p-1">
					<div class="item-icon">
						<div class="item-icon--border">
							<i class="bi bi-person-plus-fill item-icon__i"></i>
						</div>
					</div>

					<div class="item-text">
						<h6 class="item-text__heading">Lời mời kết bạn</h6>
					</div>

					<div class="item-chevron">
						<i class="bi bi-chevron-right item-chevron-icon"></i>
					</div>
				</div>

				<div class="option-item friends-list card p-1 mt-2">
					<div class="item-icon">
						<div class="item-icon--border">
							<i class="bi bi-person-lines-fill item-icon__i"></i>
						</div>
					</div>

					<div class="item-text">
						<h6 class="item-text__heading">Danh sách bạn bè</h6>
					</div>

					<div class="item-chevron">
						<i class="bi bi-chevron-right item-chevron-icon"></i>
					</div>
				</div>
			</div>

			<div class="friend-request-list d-none p-2 flex-column">
				{{-- Hiển thị danh sách lời mời kết bạn --}}
				<?php
					$friend = new App\Friends;

					$userRequested = App\Friends::find(Auth::user()->id)->request;
					$userFriends = App\Friends::find(Auth::user()->id)->friends;

					if ($userRequested != null) {
						$userRequestedArr = explode('|', $userRequested);

						foreach($userRequestedArr as $key => $value) {
							$userSended = App\User::find($value);
							?>
							<div class="friend-item card p-1 d-flex flex-row w-100 mt-2">
								<div class="friend-item__avt">
									<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
								</div>

								<div class="friend-item__more w-100 p-1">
									<div class="friend-item__info d-flex justify-content-between">
										<h6 class="friend-item__info--name"><strong><?php echo $userSended->name; ?></strong></h6>
										<p class="friend-item__info--id">#<?php echo $userSended->id; ?></p>
									</div>

									<div class="friend-item__confirm mt-1 d-flex">
											<div class="btn-confirm w-50 pe-1 d-flex">
												<button class="btn btn-primary w-100 accept-friend-request-btn">Xác nhận</button>
											</div>
											<div class="btn-refuse w-50 ps-1 d-flex">
												<button class="btn btn-secondary w-100 cancel-friend-request-btn">Từ chối</button>
											</div>
									</div>
								</div>
							</div>
							<?php
						}
					}						
				?>
			</div>

			<div class="friend-accept-list d-none p-2 flex-column">
				<?php 
					if ($userFriends != null) {
						$myFriends = explode('|', $userFriends);

						foreach ($myFriends as $key => $myFriendId) {
							$myFriendAcc = App\User::find($myFriendId);
							?>
								<div class="friend-accept-item card p-1 d-flex flex-row w-100 mt-2">
									<div class="friend-accept-item__avt">
										<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
									</div>

									<div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
										<div class="friend-accept-item__info d-flex flex-column">
											<h6 class="friend-accept-item__info--name"><strong><?php echo $myFriendAcc->name; ?></strong></h6>
											<p class="friend-accept-item__info--id">#<?php echo $myFriendId; ?></p>
										</div>
									</div>

									<div class="friend-accept-item__setting mt-1 d-flex flex-column justify-content-center">
										<i class="bi bi-x friend-accept-item__setting--icon" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#unfriend-modal"></i>
									</div>
								</div>
							<?php 
						}
					}
				?>		
			</div>
		</div>

		<div class="col col-md-9 d-flex justify-content-center flex-row p-3">
			<div class="friend-finded w-75 card p-3">
				<div class="friend__heading">
					<h4>Mọi người</h4>
					<hr/>
				</div>

				<div class="friend__list">
					<?php
						$user = new App\User;
						$item = $user->where('location', Auth::user()->location)->where('id', '!=', Auth::user()->id)->get();

						foreach ($item as $key => $value) {
							$friendItem = App\Friends::find($value->id);

							// Xử lý dữ liệu, nếu đã gửi lời mời kết bạn thì nút send có class là sended, nếu không thì send
							$friendRequest = $friendItem->request;

							$isSended = false;
							$isSendedRequest = false;
							$isAccept = false;

							if ($userRequested != null) {
								$userRequestedArr = explode('|', $userRequested);
								foreach ($userRequestedArr as $key2 => $value2) {
									if ($value2 == $value->id) {
										$isSendedRequest = true;
									}
								}
							}

							if ($userFriends != null) {
								$userFriendsArr = explode('|', $userFriends);
								foreach ($userFriendsArr as $key3 => $value3) {
									if ($value3 == $value->id) {
										$isAccept = true;
									}
								}
							}

							if ($friendRequest != "") {
								$friendRequest = explode('|', $friendRequest);

								foreach ($friendRequest as $idUser => $idSended) {
									if ($idSended == Auth::user()->id) {
										$isSended = true;
									}
								}
							}

							?>

							<div class="card friend__list--item flex-row p-1 mb-2">
								<div class="friend-avt p-1">
									<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
								</div>

								<div class="friend-info">
									<div class="friend-info--detail">
										<h6 class="friend__info--name"><strong><?php echo $value->name; ?></strong></h6>
										<h6 class="friend__info--location"><?php echo $value->location; ?></h6>
									</div>
									<p class="friend__info--id">#<?php echo $value->id ?></p>
								</div>

								<div class="add-friend">
									<div class="add-friend__btn 
									<?php 
										if ($isAccept) {
											echo 'accepted';
										} else if ($isSendedRequest) {
											echo "sendedRequest";
										} else {
											if ($isSended) {
												echo "sended";
											} else {
												echo "send";
											}
										}
									?>">
										<i class="bi <?php 
											if ($isAccept) {
												echo "bi-person-check-fill";
											} else if ($isSended) {
												echo "bi-person-x-fill";
											} else {
												echo "bi-person-plus-fill";
											} 
										?> 
									add-friend__btn--icon"></i>
									</div>
								</div>
							</div>

							<?php
						}
					?>
				</div>
			</div>
		</div>
	</div>

	<div id='unfriend-modal' class="modal fade" tabindex="-1">
		<input type="hidden" id="friend-info--id">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header p-3">
					<h5 class="modal-title">Hủy kết bạn</h5>
				</div>
				<div class="modal-body">
					<p>Một khi đã thực hiện, điều này không thể hoàn tác.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary close-yn-modal" data-bs-dismiss="modal">Đóng</button>
					<button type="button" class="btn btn-primary remove-friend-btn" onclick="return unfriend()">Tiếp tục</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src=js/user-function/friend.js></script>
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
@endsection