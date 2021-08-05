// Phần thu ra thu vào cho thanh sidebar
var minisizeBtn = document.querySelector('.minisize');
var sidebarMenu = document.querySelector('.sidebar-menu');
var previewDocs = document.querySelector('.preview-docs');
var extendSidebar = document.querySelector('.extendsize');

var isMinisize = false;

minisizeBtn.onclick = function() {
	isMinisize = true;
	sidebarMenu.classList.remove('showSidebar');
	sidebarMenu.classList.add('hideSidebar');
	extendSidebar.style.display = 'unset';
	previewDocs.classList.remove('minisizePreviewDocs');
	previewDocs.classList.add('extendPreviewDocs');
}

extendSidebar.onclick = function() {
	sidebarMenu.style.display = 'block';
	sidebarMenu.classList.remove('hideSidebar');
	sidebarMenu.classList.add('showSidebar');
	extendSidebar.style.display = 'none';
	previewDocs.classList.remove('extendPreviewDocs');
	previewDocs.classList.add('minisizePreviewDocs');
}

// Gán link để preview cho từng file
var file = document.querySelectorAll('.file-address');
var preview = document.querySelector('#pdf-js-viewer');


file.forEach(function(item) {
	itemElement = item.parentElement;

	itemElement.addEventListener('click', () => {
	// change src attribute of iframe
	preview.src = 'http://docsstorage.dev/viewer?file=fileUploaded/' + item.value;
	})
});

// Ẩn hiện phần thêm type
var addTypeBtn = document.querySelector('.file-type-icon');
var modalLibrary = document.querySelector('.modal-library');
var createNewType = document.querySelector('.create-new-type');
var cancelBtn = document.querySelectorAll('.cancel-btn');

var cancelIcon = document.querySelector('.cancel-icon');
var modalError = document.querySelector('.error-modal');

addTypeBtn.onclick = function() {
	modalLibrary.style.display = 'flex';
	createNewType.style.display = 'flex';
}

cancelBtn.forEach(function(cancelBtnItem) {
	cancelBtnItem.onclick = function() {
		modalLibrary.setAttribute('style', 'display:none !important');
		createNewType.setAttribute('style', 'display:none !important');
	}
});

cancelIcon.onclick = function() {
	modalLibrary.setAttribute('style', 'display:none !important');
	modalError.setAttribute('style', 'display:none !important');
}

// Ẩn hiện phần thêm file/folder
var addOption = document.querySelector('.add-option');
var addModal = document.querySelector('.add-modal');
var cancelAddBtn = document.querySelectorAll('.cancel-add-btn');
var selectValue = document.querySelectorAll('.selectValue');
var selection = document.querySelector('#selection');

addOption.onclick = function() {
	modalLibrary.setAttribute('style', 'display:flex !important');
	addModal.setAttribute('style', 'display:flex !important');
}

cancelAddBtn.forEach(function(cancelBtn) {
	cancelBtn.onclick = function() {
		modalLibrary.setAttribute('style', 'display:none !important');
		addModal.setAttribute('style', 'display:none !important');
	}
});

var value = selection.value;
selectValue.forEach(function(selectInput) {
	selectInput.value = value;
});


function genderChanged(obj) {
    var value = obj.value;
    // selectValue.forEach(function(selectInput) {
    // 	selectInput.value = value;
    // });
    window.location = '/library/' + value;
}

// Xuất ra file của folder
var indexLevel = 0;
var folderAddress = document.querySelectorAll(".folder-address");

file.forEach(function(fileItem) {
	var fileElement = fileItem.parentElement;
	console.log(fileElement.childNodes[3]);
});

folderAddress.forEach(function(folderItemAddress) {
	var folderElement = folderItemAddress.parentElement;
	// console.log(folderElement);
});