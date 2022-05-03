//So sánh coi phần tử object có nằm trong phần tử của một mảng không (return True/False)
function compareInclude(obj, arr) {
    const objRaw = JSON.stringify(obj);
    for (i = 0; i < arr.length; i++) {
        let itemRaw = JSON.stringify(arr[i]);
        if (itemRaw === objRaw) {
            return true;
        }
    }

    return false;
}

// Hàm mở toast
function openToast(toast, notification) { //Hàm  mở toast
	var myAlert = document.querySelector(toast);//select id of toast
	var bsAlert = new bootstrap.Toast(myAlert);//inizialize it
	myAlert.querySelector('.toast-notifi').innerHTML = notification;
	bsAlert.show();//show it
};


// Chuyển nhóm đã tham gia
const joinedGroup = document.querySelector('.joined-group');
const ownershipGroup = document.querySelector('.ownership-group-div');
const joinedGroupBtn = document.querySelector('.joined-group-btn');
const ownershipGroupBtn = document.querySelector('.ownership-group-btn');

joinedGroupBtn.onclick = function() {
    joinedGroup.classList.replace('d-none', 'd-flex');
    ownershipGroup.classList.replace('d-flex', 'd-none');
}

ownershipGroupBtn.onclick = function() {
    joinedGroup.classList.replace('d-flex', 'd-none');
    ownershipGroup.classList.replace('d-none', 'd-flex');
}



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

// Phần tìm kiếm
var searchBar = document.querySelector('.find-input');
var searchForm = document.querySelector('.search-form');

var friendFindedList = document.querySelector('.list-friend');

//Danh sách user được thêm vào nhóm chuẩn bị tạo
var listUser = [];
var listUserTemp = [];

//Hàm hiển thị user đã được thêm vào group chuẩn bị được tạo
const listUserInput = document.querySelector('.list-user');
function showUser() {
    listUserInput.value = '';
    listUser.forEach(user => {
        listUserInput.value += ' @' + user.name + ';';
    });
}

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
        listUserTemp = [];
		friendFindedList.innerHTML = ''; //Xóa những item trước để in ra item mới
		// In ra bạn tìm đc		
        let index = -1; //Chỉ mục của phần tử trong mảng
		response.data.forEach(function(item) {
			console.log(item);
            if (!compareInclude(item,listUser)) {
                index++;
                listUserTemp.push(item);
                friendFindedList.insertAdjacentHTML('beforeend',
                `
                    <div class="card friend__list--item flex-row p-1 mb-2">
                        <input type='hidden' class='index' value='${index}' />
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
                            <div class="add-friend__btn">
                                <i class="bi bi-node-plus-fill add-friend__btn--icon"></i>
                            </div>
                        </div>
                    </div>
                `
            );
            }
			addToGroup();
		});
	})
	.catch(function(error) {
		console.log(error);
		friendFindedList.innerHTML = '';
	});

	return false;
}

showUser();
// Phần thêm thành viên vào nhóm
function addToGroup() {
	const friendsFinded = document.querySelectorAll('.friend__list--item');

	friendsFinded.forEach(function(userItem) {
		// let formData = new FormData();
		// formData.append('friendId', friendId);

        let sendRequestBtn = userItem.querySelector('.add-friend__btn');
        
		sendRequestBtn.onclick = function() {
            const userIndex = userItem.querySelector('.index').value;
            //Thêm user được chọn vào danh sách thành viên của group chuẩn bị khởi tạo
            listUser.push(listUserTemp[userIndex]);
            showUser(); //load lại user được chọn

            userItem.remove(); //Sau khi được thêm vào thì xóa nó ở danh sách thêm đi
		}

	});
}

addToGroup();


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

// Tạo nhóm
var nameGroupNew = document.querySelector('.group-name');
function createNewGroup() {
    const nameGroupNewValue = nameGroupNew.value;
    
    let listUsersNew = [];
    let listIdUsersNew = [];
    listUser.forEach(user => {
        listUsersNew.push(user.name);
        listIdUsersNew.push(user.id);
    })

    let formData = new FormData;
    formData.append('name', nameGroupNewValue);
    formData.append('numberMembers', listUsersNew.length);
    formData.append('members', listUsersNew);
    formData.append('membersId', listIdUsersNew);

    axios.post('create-new-group', formData)
    .then(response => {
        console.log(response);
        nameGroupNew.value = '';
        listUser = [];
        getListOwnershipGroup(true);
        showUser();
        viewOwnershipGroup();

        openToast('.sucessToast', 'Đã tạo nhóm mới!');
    })
    .catch(error => {
        let errorsList = error.response.data.errors;
        
        for (errorMsgKey in errorsList) {
            errorsList[errorMsgKey].forEach(errorTitle => openToast('.warningToast', errorTitle));
        }
    });
}

createNewGroupBtn = document.querySelector('.create-new-group-btn');
createNewGroupBtn.onclick = createNewGroup;

// Đổ danh sách các nhóm đã tham gia
function getListGroupJoined() {
    axios.get('get-list-group-joined')
    .then(response => {
        console.log(response);
        joinedGroup.innerHTML = '';
        response.data.forEach(group => {
            if (group != null) {
                joinedGroup.insertAdjacentHTML('beforeend', `
                <div class="group-joined card p-1 d-flex flex-row w-100 mt-2">
                    <div class="friend-accept-item__avt">
                        <img src="img/avatar/default/default-group-avt.jpg" alt="avatar" class="avatar">
                    </div>

                    <div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
                        <div class="friend-accept-item__info d-flex flex-column">
                            <h6 class="friend-accept-item__info--name"><strong>${group.name}</strong></h6>
                            <div class="group-info d-flex justify-content-between">
                            <p class="friend-accept-item__info--id text-primary">Số lượng thành viên: ${group.number_mem}</p>
                                <p class="friend-accept-item__info--id">#<span class='groupId'>${group.id}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="friend-accept-item__setting edit-group mt-1 d-flex flex-column justify-content-center">
                        <i class="bi bi-pencil-square friend-accept-item__setting--icon" type="button" data-bs-toggle="modal" data-bs-target="#unfriend-modal"></i>
                    </div>
                </div>
            `);
            }
        });
        viewGroupJoined();
    })
    .catch(error => {
        console.log(error);
    })
}


// Đổ danh sách các nhóm đã tạo
function getListOwnershipGroup(create = false) {
    axios.get('get-list-ownership-group')
    .then(response => {
        console.log(response);
        ownershipGroup.innerHTML = '';
        response.data.forEach(group => {
            if (group != null) {
                ownershipGroup.insertAdjacentHTML('beforeend', `
                    <div class="ownership-group card p-1 d-flex flex-row w-100 mt-2">
                        <div class="friend-accept-item__avt">
                            <img src="img/avatar/default/default-group-avt.jpg" alt="avatar" class="avatar">
                        </div>

                        <div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
                            <div class="friend-accept-item__info d-flex flex-column">
                                <h6 class="friend-accept-item__info--name"><strong>${group.name}</strong></h6>
                                <div class="group-info d-flex justify-content-between">
                                <p class="friend-accept-item__info--id text-primary">Số lượng thành viên: ${group.number_mem}</p>
                                    <p class="friend-accept-item__info--id">#<span class='groupId'>${group.id}</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="friend-accept-item__setting  edit-ownership-group mt-1 d-flex flex-column justify-content-center">
                            <i class="bi bi-pencil-square friend-accept-item__setting--icon" type="button" data-bs-toggle="modal" data-bs-target="#unfriend-modal"></i>
                        </div>
                    </div>
                `);
            }
        }); 
        viewOwnershipGroup();
        if (create) {
            document.querySelector('.ownership-group:last-child').querySelector('.friend-accept-item__setting--icon').click();
        }
    })
    .catch(error => {
        console.log(error);
    })
}

getListGroupJoined();
getListOwnershipGroup();

const idJoinedElement = document.querySelector('.id-group');
const ownerNameJoinedElement = document.querySelector('.owner-name');
const groupNameJoinedElement = document.querySelector('#group-name__input');
const groupMembers = document.querySelector('#group-name__members');
const groupLinkJoinedElement = document.querySelector('#group-name__join-link');
const numberOfMembersElemnt = document.querySelector('.number_of_members');
const openLinkJoin = document.querySelector('#allow-join-via-link');
const exitGroupBtn = document.querySelector('.exit-group-btn');

function viewGroupJoined() {
    let groupsJoined = document.querySelectorAll('.group-joined');
    groupsJoined.forEach(group => {
        editGroupBtn = group.querySelector('.edit-group');
        console.log(editGroupBtn);
        editGroupBtn.onclick = function() {
            let groupId = group.querySelector('.groupId').innerText;

            axios.get('get-group-detail', {
                params: {
                    'groupId': groupId,
                }
            })
            .then(response => {
                groupData = response.data;
                console.log(groupData);

                exitGroupBtn.innerText = 'Rời nhóm';
                idJoinedElement.innerText = groupData.id;
                ownerNameJoinedElement.innerText = groupData.owner;
                groupNameJoinedElement.value = groupData.name;
                groupLinkJoinedElement.value = window.location.host + '/invite/' + groupData.invite_link;
                groupMembers.value = groupData.members.split(',').map(memName => '@' + memName).join(', ');
                numberOfMembersElemnt.innerText = groupData.number_mem;
                openLinkJoin.setAttribute('disabled','');
                if (groupData.is_open == true) {
                    openLinkJoin.checked = true;
                } else {
                    openLinkJoin.checked = false;
                }

                editMembersListBtn.onclick = editMembersList(groupId, false);

                // Function rời nhóm
                exitGroupBtn.onclick = function() {
                    if (confirm('Bạn có chắc chắn muốn rời nhóm?')) {
                        let groupFormData = new FormData;
                        groupFormData.append('groupId', groupId);
                        axios.post('exit-group', groupFormData)
                        .then(response => {
                            console.log(response);
                            getListGroupJoined();
                            closeYnModal.click();
                        })
                        .catch(error => {
                            console.log(error);
                        })
                    }
                }
            })
            .catch(error => {
                console.log(error);
            })
        }
    });
}

var groupSelected = [];

function viewOwnershipGroup() {
    let groupsJoined = document.querySelectorAll('.ownership-group');
    groupsJoined.forEach(group => {
        editGroupBtn = group.querySelector('.edit-ownership-group');
        console.log(editGroupBtn);
        editGroupBtn.onclick = function() {
            let groupId = group.querySelector('.groupId').innerText;
            console.log(groupId);

            axios.get('get-group-detail', {
                params: {
                    'groupId': groupId,
                }
            })
            .then(response => {
                console.log(response);
                groupData = response.data;
                // console.log(groupData);

                exitGroupBtn.innerText = 'Xóa nhóm';
                idJoinedElement.innerText = groupData.id;
                ownerNameJoinedElement.innerText = groupData.owner;
                groupNameJoinedElement.value = groupData.name;
                groupLinkJoinedElement.value = window.location.host + '/invite/' + groupData.invite_link;
                groupMembers.value = groupData.members.split(',').map(memName => '@' + memName).join(', ');
                numberOfMembersElemnt.innerText = groupData.number_mem;
                openLinkJoin.removeAttribute('disabled');
                if (groupData.is_open == true) {
                    openLinkJoin.checked = true;
                } else {
                    openLinkJoin.checked = false;
                }  

                openLinkJoin.onclick = function() {
                    if (openLinkJoin.checked == true) {
                        let formDataId = new FormData;
                        formDataId.append('groupId', groupId);
                        axios.post('enable-open-link-share', formDataId)
                        .then(response => {
                            console.log(response);
                            openToast('.sucessToast', 'Đã đổi trạng thái!');
                            return;
                        })
                        .catch(error => {
                            console.log(error);
                            openToast('.errorToast', 'Đã có lỗi xảy ra!');
                        })
                    } else {
                        let formDataId = new FormData;
                        formDataId.append('groupId', groupId);
                        axios.post('disable-open-link-share', formDataId)
                        .then(response => {
                            console.log(response);
                            openToast('.sucessToast', 'Đã đổi trạng thái!');
                            return;
                        })
                        .catch(error => {
                            console.log(error);
                            openToast('.errorToast', 'Đã có lỗi xảy ra!');
                        })
                    }
                };

                editMembersListBtn.onclick = editMembersList(groupId, true);

                // Function xóa nhóm
                exitGroupBtn.onclick = function() {
                    if (confirm('Bạn có chắc chắn muốn xóa nhóm?')) {
                        let groupFormData = new FormData;
                        groupFormData.append('groupId', groupId);
                        axios.post('remove-group', groupFormData)
                        .then(response => {
                            console.log(response);
                            getListOwnershipGroup();
                            closeYnModal.click();
                        })
                        .catch(error => {
                            console.log(error);
                        })
                    }
                }
            })
            .catch(error => {
                console.log(error);
            })
        }
    });
}

var removeFriendBtn = document.querySelector('.remove-friend-btn');
var closeYnModal = document.querySelector('.close-yn-modal');


// Chỉnh sửa danh sách nhóm
const editMembersListBtn = document.querySelector('.show-members-list--btn');
const membersListModal = document.querySelector('.list-members-modal');
function editMembersList(groupId, isCreate) {
    membersListModal.innerHTML = '';
    axios.get('get-group-detail', {
        params: {
            'groupId': groupId,
        }
    })
    .then(response => {
        console.log(response);
        let members = response.data.members.split(',');
        console.log(members);

        axios.get('get-user-info', {
            params: {
                'members': members,
            }
        })
        .then(response => {
            console.log(response);

            response.data.forEach(member => {
                if (isCreate) {
                    membersListModal.insertAdjacentHTML('beforeend', `
                        <div class="friend-accept-item card p-1 d-flex flex-row w-100 mt-2 user-item" userId='${member.id}'>
                            <div class="friend-accept-item__avt">
                                <img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
                            </div>

                            <div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
                                <div class="friend-accept-item__info d-flex flex-column">
                                    <h6 class="friend-accept-item__info--name"><strong>${member.name}</strong></h6>
                                    <p class="friend-accept-item__info--id">#<span class='memberUserId'>${member.id}</span></p>
                                </div>
                            </div>

                            <div class="friend-accept-item__setting mt-1 d-flex flex-column justify-content-center">
                                <i class="bi bi-x friend-accept-item__setting--icon remove_member--btn" type="button"></i>
                            </div>
                        </div>
                    `);
                } else {
                    membersListModal.insertAdjacentHTML('beforeend', `
                        <div class="friend-accept-item card p-1 d-flex flex-row w-100 mt-2">
                            <div class="friend-accept-item__avt">
                                <img src="img/avatar/default/default-avatar.png" alt="avatar" class="avatar">
                            </div>

                            <div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
                                <div class="friend-accept-item__info d-flex flex-column">
                                    <h6 class="friend-accept-item__info--name"><strong>${member.name}</strong></h6>
                                    <p class="friend-accept-item__info--id">#<span class='memberUserId'>${member.id}</span></p>
                                </div>
                            </div>
                        </div>
                    `);
                }
              
                // Thêm chức năng xóa thành viên
                let memberElements = document.querySelectorAll('.user-item');

                memberElements.forEach(memberElement => {
                    const removeMemberBtn = memberElement.querySelector('.remove_member--btn');
                    removeMemberBtn.onclick = function() {
                        let isConfirm = confirm('Bạn có muốn thực thi hành động?');
                        if (isConfirm) {
                            let memberId = memberElement.getAttribute('userId');
                            let memberName = memberElement.querySelector('.friend-accept-item__info--name').innerText;
                            console.log(memberName);
                            
                            let memberInfo = new FormData;
                            memberInfo.append('memberId', memberId);
                            memberInfo.append('groupId', groupId);

                            axios.post('remove-member', memberInfo)
                            .then(response => {
                                console.log(response);
                                
                                // Chỉnh sửa lại các element có dữ liệu đã thay đổi
                                numberOfMembersElemnt.innerText = Number(numberOfMembersElemnt.innerText) - 1;

                                let value = '@' + memberName;
                                let arr = groupMembers.value.split(',');
                                arr = arr.filter(item => item.trim() !== value);
                                console.log(arr);
                                groupMembers.value = arr.map(memName => memName.trim()).join(', ');
                                getListOwnershipGroup();

                                memberElement.remove();
                                openToast('.sucessToast', 'Đã xóa người dùng khỏi nhóm');
                            })
                            .catch(error => {
                                console.log(error);
                            })
                        }
                    }
                })
            })
        })
        .catch(error => {
            console.log(error);
        })
    })
    .catch(error => {
        console.log(error);
    })
}

// Sao chép đường link
new ClipboardJS('#copy-btn');