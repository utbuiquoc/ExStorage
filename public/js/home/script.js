axios.get('friend-table-info')
.then(response => {
    console.log(response);

    let numberFriends = response.data.friends; 
    let numberRequest = response.data.request;

    if (numberFriends != '') {
        numberFriends = numberFriends.split('|').length;
    } else {
        numberFriends = 0;
    }
    
    if (numberRequest != '') {
        numberRequest = numberRequest.split('|').length;
    } else {
        numberRequest = 0;
    }

    console.log(numberFriends, numberRequest);
    document.querySelector('.number-of-friend').innerText = numberFriends;
    document.querySelector('.friend-request').innerText = numberRequest;
})
.catch(error => {
    console.log(error);
});

const joinedGroup = document.querySelector('.group-joined-list');
const ownershipGroup = document.querySelector('.group-created-list');

axios.get('get-list-group-joined')
.then(response => {
    console.log(response);
    joinedGroup.innerHTML = '';
    response.data.forEach(group => {
        if (group != null) {
            joinedGroup.insertAdjacentHTML('beforeend', `
            <div class="group-joined card p-2 d-flex flex-row w-100 mt-2">
                <div class="friend-accept-item__avt">
                    <img src="img/avatar/default/default-group-avt.jpg" alt="avatar" class="avatar">
                </div>

                <div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
                    <div class="friend-accept-item__info d-flex flex-column">
                        <h6 class="friend-accept-item__info--name"><strong>${group.name}</strong></h6>
                        <div class="group-info d-flex justify-content-between">
                        <p class="friend-accept-item__info--id text-primary">Số lượng thành viên: ${group.number_mem}</p>
                            <p class="friend-accept-item__info--id">#<span class="groupId">${group.id}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        `);
        }
    });
})
.catch(error => {
    console.log(error);
})

axios.get('get-list-ownership-group')
.then(response => {
    console.log(response);
    ownershipGroup.innerHTML = '';
    response.data.forEach(group => {
        if (group != null) {
            ownershipGroup.insertAdjacentHTML('beforeend', `
            <div class="group-joined card p-2 d-flex flex-row w-100 mt-2">
                <div class="friend-accept-item__avt">
                    <img src="img/avatar/default/default-group-avt.jpg" alt="avatar" class="avatar">
                </div>

                <div class="friend-accept-item__more w-100 p-1 justify-content-center d-flex flex-column">
                    <div class="friend-accept-item__info d-flex flex-column">
                        <h6 class="friend-accept-item__info--name"><strong>${group.name}</strong></h6>
                        <div class="group-info d-flex justify-content-between">
                        <p class="friend-accept-item__info--id text-primary">Số lượng thành viên: ${group.number_mem}</p>
                            <p class="friend-accept-item__info--id">#<span class="groupId">${group.id}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        `);
        }
    });
})
.catch(error => {
    console.log(error);
})