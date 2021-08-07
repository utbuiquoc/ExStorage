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

function genderChanged(obj) {
    var value = obj.value;
    // selectValue.forEach(function(selectInput) {
    // 	selectInput.value = value;
    // });
    window.location = '/library/' + value;
}

// Xuất ra file của folder, nút back
var indexLevel = 0;
var folderAddress = document.querySelectorAll(".folder-address");
var folder = document.querySelectorAll('.folder-item');
var currentRootDir = document.querySelector('#currentDir').value;
console.log(currentDir);
var backBtn = document.querySelector('.back-btn');
var dirItem = document.querySelectorAll('.dirItem');
var dirCache = [];

function addCache() {
	var currentDir = document.querySelector('#currentDir').value;
	dirCache.push(currentDir);
	// console.log(dirCache);
}

function getItemDir(item) { //Chuyển dir của item thành current dir
	newArrayDir = item.split('/');
	if (newArrayDir.length > 1) {
		newArrayDir.pop();
		return newArrayDir.join('/');
	}
}

function showFile(dir) {
	var currentDir = document.querySelector('#currentDir').value;
	file.forEach(function(fileItem) {
		var fileElement = fileItem.parentElement;
		var fileDir = fileElement.childNodes[3].value;
		if (typeof dir != "undefined") {
			if (fileDir === dir) { //Nếu có tùy chọn
				fileElement.classList.replace('d-none', 'd-flex');
			}
		} else { //Mặc định
			if (fileDir === currentDir) {
				fileElement.classList.replace('d-none', 'd-flex');
			}
		}
	});
}

function showFolder() {
	folder.forEach(function(folderItem) {
		var currentDir = document.querySelector('#currentDir').value;
		var folderDir = folderItem.querySelector('.folder-address').value;
		var folderDirDefault = getItemDir(folderDir);

		if (folderDirDefault === currentDir) {
			folderItem.classList.replace('d-none', 'd-flex');
			console.log('folderDir: ' + folderDir);
		}
	});
}

function hideFile () {
	dirItem.forEach(function(item) {
		item.classList.replace('d-flex', 'd-none');
	});
}



function currentDir() { //Hàm để get dữ liệu cho các thẻ input selectValue;
	var value = document.querySelector('#currentDir').value;
	// console.log(value);
	selectValue.forEach(function(selectInput) {
		selectInput.value = value;
	});
}

currentDir() //Chạy hàm currentDir để lấy giá trị cho các thẻ input selectValue


folderAddress.forEach(function(folderItemAddress) { //Onclick cho folder
    var folderElement = folderItemAddress.parentElement;
    folderElement.onclick = function() {
        hideFile();
        addCache();
        backBtn.classList.replace('d-none', 'd-flex'); //Hiện lên nút back khi nhấp vào folder
        folderDir = folderElement.childNodes[1].value;
        document.querySelector('#currentDir').value = folderDir;
        currentDir();
        showFile();
        showFolder()
    }
});

backBtn.onclick = function() {
	// Gán dir trước cho currentDir
	newDir = dirCache.pop();
	document.querySelector('#currentDir').value = newDir;

	if (document.querySelector('#currentDir').value === currentRootDir) {
		backBtn.classList.replace('d-flex', 'd-none'); //Nếu currentDir là root thì ẩn nút back
	}
	hideFile();
	showFolder();
	currentDir();
	showFile();
}

showFolder();
showFile();