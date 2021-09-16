var userId = document.querySelector('.user__id').innerHTML.replace('#', '');

// Phần option
var friendsListOptions = document.querySelector('.friend-list-option');

var backBtn = document.querySelector('.back-btn');
var friendsRequestTitle = document.querySelector('.friend-request__heading');

var friendsRequestBtn = document.querySelector('.friend-request');
var friendsRequestList = document.querySelector('.friend-request-list');

var friendsListBtn = document.querySelector('.friends-list');

var myFriendsList = document.querySelector('.friend-accept-list');
var myFriendsTitle = document.querySelector('.friend-accepted__heading');

// Onclick để hiện danh sách lời mời kết bạn
friendsRequestBtn.onclick = function() {
	friendsListOptions.classList.replace('d-flex', 'd-none');
	friendsRequestList.classList.replace('d-none', 'd-flex');

	backBtn.classList.replace('d-none', 'd-flex');
	friendsRequestTitle.classList.replace('d-none', 'd-flex');
}

// Onclick để hiện danh sách bạn bè
friendsListBtn.onclick = function() {
	friendsListOptions.classList.replace('d-flex', 'd-none');
	myFriendsList.classList.replace('d-none', 'd-flex');

	backBtn.classList.replace('d-none', 'd-flex');
	myFriendsTitle.classList.replace('d-none', 'd-flex');
}

// Onclick để trở về
backBtn.onclick = function() {
	friendsListOptions.classList.replace('d-none', 'd-flex');
	friendsRequestList.classList.replace('d-flex', 'd-none');

	myFriendsList.classList.replace('d-flex', 'd-none');

	backBtn.classList.replace('d-flex', 'd-none');
	friendsRequestTitle.classList.replace('d-flex', 'd-none');
	myFriendsTitle.classList.replace('d-flex', 'd-none');
}

// Phần tìm kiếm
var searchBar = document.querySelector('.find-input');
var searchForm = document.querySelector('.search-form');

var friendFindedList = document.querySelector('.friend__list');

function searchFriend() {
	var friendInfo = searchBar.value;
	var formData = new FormData();

	if (friendInfo[0] === '#') {
		friendInfo = friendInfo.replace('#', '');
	}

	formData.append('friendInfo', friendInfo);
	axios.post('find-friend', formData)
	.then(function(response) {
		console.log(response);
		friendFindedList.innerHTML = ''; //Xóa những item trước để in ra item mới
		// In ra bạn tìm đc		
		response.data.forEach(function(item) {
			console.log(item);

			let isSended = false;
			let isSendRequest = false;

			if (item.request !== null) {
				const request = item.request.split('|');

				request.forEach(function(item) {
					if (item == userId) {
						isSended = true;
					}
				});
			}

			document.querySelectorAll('.friend-item__info--id').forEach(function(friendRequestId) {
				if (friendRequestId.innerText === `#${item.id}`) {
					isSendRequest = true;
				}
			});

			let sendIcon;
			let sendElement;
			if (isSendRequest) {
				sendElement = 'sendedRequest';
				sendIcon = 'bi-person-plus-fill';
			} else {
				if (isSended) {
					sendElement = 'sended';
					sendIcon = 'bi-person-x-fill';
				} else {
					sendElement = 'send';
					sendIcon = 'bi-person-plus-fill';
				}
			}

			friendFindedList.insertAdjacentHTML('beforeend',
				`
					<div class="card friend__list--item flex-row p-1 mb-2">
						<div class="friend-avt p-1">
							<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
						</div>

						<div class="friend-info">
							<div class="friend-info--detail">
								<h6 class="friend__info--name"><strong>${item.name}</strong></h6>
								<h6 class="friend__info--location">${item.location}</h6>
							</div>
							<p class="friend__info--id">#${item.id}</p>
						</div>

						<div class="add-friend">
							<div class="add-friend__btn ${sendElement}">
								<i class="bi ${sendIcon} add-friend__btn--icon"></i>
							</div>
						</div>
					</div>
				`
			);

			createSendFriendRequestAction();
		});
	})
	.catch(function(error) {
		console.log(error);
		friendFindedList.innerHTML = '';
	});

	return false;
}

// Phần gửi lời mời kết bạn
function createSendFriendRequestAction() {
	const friendsFinded = document.querySelectorAll('.friend__list--item');

	friendsFinded.forEach(function(friendItem) {
		let friendName = friendItem.querySelector('.friend__info--name').innerText;
		let friendId = friendItem.querySelector('.friend__info--id').innerText;

		let sendRequestBtn = friendItem.querySelector('.add-friend__btn');
		let sendRequestIcon = friendItem.querySelector('.add-friend__btn--icon');
		
		friendId = friendId.replace('#', ''); // Xóa dấu # trong id

		let formData = new FormData();
		formData.append('friendId', friendId);

		sendRequestBtn.onclick = function() {
			// Nếu chưa kết bạn thì thực thi route kết bạn, không thì thực thi route hủy
			if (sendRequestBtn.classList[1] === 'send') {
				axios.post('send-friend-request', formData)
				.then(function(response) {
					console.log(response);

					sendRequestBtn.classList.replace('send', 'sended');
					sendRequestIcon.classList.replace('bi-person-plus-fill', 'bi-person-x-fill')

				})
				.catch(function(error) {
					console.log(error);
				})
			} else if (sendRequestBtn.classList[1] === 'sendedRequest') { // Nếu đối phương đã gửi lời mời kết bạn, khi nhấp sẽ kết bạn
				axios.post('send-friend-request', formData)
				.then(function(response) {
					console.log(response);

					friendInfoValue = response.data;

					sendRequestBtn.classList.replace('sendedRequest', 'accepted');
					sendRequestIcon.classList.replace('bi-person-plus-fill', 'bi-person-check-fill');

					let acceptFriendRequestElement = document.querySelectorAll('.friend-item');
					acceptFriendRequestElement.forEach(function(item) {
						const itemId = item.querySelector('.friend-item__info--id');
						if (itemId.innerText == `#${friendId}`) {
							item.remove();
						}
					});

					myFriendsList.insertAdjacentHTML('afterbegin', `
						<div class="friend-accept-item card p-1 d-flex flex-row w-100 mt-2">
							<div class="friend-accept-item__avt">
								<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
							</div>

							<div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
								<div class="friend-accept-item__info d-flex flex-column">
									<h6 class="friend-accept-item__info--name"><strong>${friendInfoValue.name}</strong></h6>
									<p class="friend-accept-item__info--id">#${friendInfoValue.id}</p>
								</div>
							</div>

							<div class="friend-accept-item__setting mt-1 d-flex flex-column justify-content-center">
								<i class="bi bi-x friend-accept-item__setting--icon" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#unfriend-modal"></i>
							</div>
						</div>
					`);

					insertIdForInput();
				})
				.catch(function(error) {
					console.log(error);
				})
			} else {
				axios.post('cancel-friend-request', formData)
				.then(function(response) {
					console.log(response);

					sendRequestBtn.classList.replace('sended', 'send');
					sendRequestIcon.classList.replace('bi-person-x-fill', 'bi-person-plus-fill');

				})
				.catch(function(error) {
					console.log(error);
				})
			}
		}

	});
}

createSendFriendRequestAction();

// Phần chấp nhận lời mời kết bạn
var friendsRequest = document.querySelectorAll('.friend-item');

friendsRequest.forEach(function(friendRequest) {
	let friendId = friendRequest.querySelector('.friend-item__info--id').innerText;
	friendId = friendId.replace('#', ''); // Xóa dấu # trong id
	const acceptFriendRequestBtn = friendRequest.querySelector('.accept-friend-request-btn');
	const cancelFriendRequestBtn = friendRequest.querySelector('.cancel-friend-request-btn');

	let formData = new FormData();
	formData.append('friendId', friendId)

	acceptFriendRequestBtn.onclick = function() { // Kết bạn khi nhấn vào nút "Xác nhận"
		axios.post('send-friend-request', formData)
		.then(function(response) {
			console.log(response);

			let friendInfoValue = response.data;

			friendRequest.remove();

			let acceptFriendRequestElement = document.querySelectorAll('.friend__list--item');
			acceptFriendRequestElement.forEach(function(item) {
				const itemId = item.querySelector('.friend__info--id');

				if (itemId.innerText == `#${friendId}`) {
					let itemStatus = item.querySelector('.add-friend__btn--icon');
					let itemBorderStatus = item.querySelector('.add-friend__btn');
					itemStatus.classList.replace('bi-person-plus-fill', 'bi-person-check-fill');
					itemBorderStatus.classList.replace('sendedRequest', 'accepted');
				}
			});

			myFriendsList.insertAdjacentHTML('afterbegin', `
				<div class="friend-accept-item card p-1 d-flex flex-row w-100 mt-2">
					<div class="friend-accept-item__avt">
						<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
					</div>

					<div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
						<div class="friend-accept-item__info d-flex flex-column">
							<h6 class="friend-accept-item__info--name"><strong>${friendInfoValue.name}</strong></h6>
							<p class="friend-accept-item__info--id">#${friendInfoValue.id}</p>
						</div>
					</div>

					<div class="friend-accept-item__setting mt-1 d-flex flex-column justify-content-center">
						<i class="bi bi-x friend-accept-item__setting--icon" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#unfriend-modal"></i>
					</div>
				</div>
			`);

			insertIdForInput();
		})
		.then(function(error) {
			console.log(error);
		});
	}
	
	cancelFriendRequestBtn.onclick = function() { // Từ chối lời mời kết bạn được gửi cho user khi nhấn nút "Từ chối"
		axios.post('cancel-friend-requested', formData)
		.then(function(response) {
			console.log(response);

			friendRequest.remove();

			let acceptFriendRequestElement = document.querySelectorAll('.friend__list--item');
			acceptFriendRequestElement.forEach(function(item) {
				const itemId = item.querySelector('.friend__info--id');

				if (itemId.innerText == `#${friendId}`) {
					let itemStatus = item.querySelector('.add-friend__btn--icon');
					itemStatus.classList.replace('bi-person-plus-fill', 'bi-person-plus-fill');
				}
			});
		})
		.catch(function(error) {
			console.log(error);
		});
	}
});

// Phần hủy kết bạn
var friendIdInput = document.querySelector('#friend-info--id');

function insertIdForInput() {
	let friendsAccepted = document.querySelectorAll('.friend-accept-item');

	friendsAccepted.forEach(function(friendAccepted) { // Dùng để gán id friend cần xóa cho thẻ input
		console.log(friendAccepted);
		const cancelFriendBtn = friendAccepted.querySelector('.friend-accept-item__setting--icon');
		
		let friendAcceptedId = friendAccepted.querySelector('.friend-accept-item__info--id').innerText;
		friendAcceptedId = friendAcceptedId.replace('#', '');

		cancelFriendBtn.onclick = function() {
			friendIdInput.value = friendAcceptedId;
		}
	});
}

insertIdForInput();

var removeFriendBtn = document.querySelector('.remove-friend-btn');
var closeYnModal = document.querySelector('.close-yn-modal');

function unfriend() {
	const friendIdToRemove = document.querySelector('#friend-info--id').value;
	let friendElement;
	let friendInListElement;

	const allFriendAcceptedId = document.querySelectorAll('.friend-accept-item__info--id');
	const allPeopleIdInList = document.querySelectorAll('.friend__info--id'); 

	allFriendAcceptedId.forEach(function(element) {
		if (element.innerText === `#${friendIdToRemove}`) {
			friendElement = element.parentElement.parentElement.parentElement;
		}
	});

	allPeopleIdInList.forEach(function(element) {
		if (element.innerText === `#${friendIdToRemove}`) {
			friendInListElement = element.parentElement.parentElement;
		}
	});

	const friendStatus = friendInListElement.querySelector('.add-friend__btn');
	const friendIconStatus = friendStatus.querySelector('.add-friend__btn--icon');

	console.log(friendStatus
,friendIconStatus);

	let formData = new FormData();

	formData.append('friendId', friendIdToRemove);
	axios.post('unfriend-accepted', formData)
	.then(function(response) {
		console.log(response);

		friendStatus.classList.replace('accepted', 'send');
		friendIconStatus.classList.replace('bi-person-check-fill', 'bi-person-plus-fill');

		friendElement.remove();
		closeYnModal.click();
	})
	.catch(function(error) {
		console.log(error);
	})
}