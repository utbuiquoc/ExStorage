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
		friendFindedList.innerHTML = ''; //Xóa những item trước để in ra item mới
		// In ra bạn tìm đc		
		response.data.forEach(function(item) {
			console.log(item);

			friendFindedList.insertAdjacentHTML('beforeend',
				`
					<div class="card friend__list--item flex-row p-1 mb-2">
						<div class="friend-avt p-1">
							<img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
						</div>

						<div class="friend-info">
							<h6 class="friend__info--name"><strong>${item.name}</strong></h6>
							<p class="friend__info--id">#${item.id}</p>
						</div>

						<div class="add-friend">
							<div class="add-friend__btn">
								<i class="bi bi-person-plus-fill add-friend__btn--icon"></i>
							</div>
						</div>
					</div>
				`
			);
		});
	})
	.catch(function(error) {
		console.log(error);
		friendFindedList.innerHTML = '';
	});

	return false;
}