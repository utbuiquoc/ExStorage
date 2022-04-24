const inviteLink = window.location.pathname.replace('/invite/','');

groupOwner = document.querySelector('.group-owner');
groupNameEl = document.querySelector('.group-name');
numberMemberEl = document.querySelector('.number-members');
idGroupEl = document.querySelector('.id-group');
acpInvitationEl = document.querySelector('.acp-invitation--btn');

axios.get('get-group-detail-via-link', {
    params: {
        'inviteLink': inviteLink
    }
})
.then(response => {
    console.log(response);
    groupInfo = response.data;
    groupOwner.innerText = groupInfo.owner;
    groupNameEl.innerText = groupInfo.name;
    numberMemberEl.innerText = groupInfo.number_mem;
    idGroupEl.innerText= groupInfo.id;
    
    let formData = new FormData;
    formData.append('groupId', groupInfo.id);

    acpInvitationEl.onclick = function() {
        axios.post('accept-invition', formData)
        .then(response => {
            console.log(response);
            window.location.replace("group");
        })
        .catch(error => {
            console.log(error);
        })
    }
})
.catch(error => {
    console.log(error);
})