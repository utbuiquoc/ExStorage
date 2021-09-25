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


function previewDocsAction() {
	var file = document.querySelectorAll('.file-address');
	file.forEach(function(item) {
		itemElement = item.parentElement;
		itemSelector = itemElement.querySelector('.selector');

		itemSelector.addEventListener('click', () => {
		// change src attribute of iframe
		preview.src = 'http://docsstorage.dev/viewer?file=fileUploaded/' + item.value;
		})
	});
}
previewDocsAction();

// Ẩn hiện phần thêm type
var addTypeBtn = document.querySelector('.file-type-icon');
var modalLibrary = document.querySelector('.modal-library');
var createNewType = document.querySelector('.create-new-type');
var cancelBtn = document.querySelectorAll('.cancel-btn');

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
var folder = document.querySelectorAll('.folder-item');
var currentRootDir = document.querySelector('#currentDir').value;
var backBtn = document.querySelector('.back-btn');
var dirItem = document.querySelectorAll('.dirItem');
var dirCache = [];

function addCache() {
	var currentDir = document.querySelector('#currentDir').value;
	dirCache.push(currentDir);
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
	var file = document.querySelectorAll('.file-address');
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
	var folder = document.querySelectorAll('.folder-item');
	folder.forEach(function(folderItem) {
		var currentDir = document.querySelector('#currentDir').value;
		var folderDir = folderItem.querySelector('.folder-address').value;
		var folderDirDefault = getItemDir(folderDir);

		if (folderDirDefault === currentDir) {
			folderItem.classList.replace('d-none', 'd-flex');
		}
	});
}

function hideFile() {
	var dirItem = document.querySelectorAll('.dirItem');
	dirItem.forEach(function(item) {
		item.classList.replace('d-flex', 'd-none');
	});
}



function currentDir() { //Hàm để get dữ liệu cho các thẻ input selectValue;
	var value = document.querySelector('#currentDir').value;
	selectValue.forEach(function(selectInput) {
		selectInput.value = value;
	});
}

currentDir() //Chạy hàm currentDir để lấy giá trị cho các thẻ input selectValue


function folderOnlickAction() {
	var folderAddress = document.querySelectorAll(".folder-address");
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
}
folderOnlickAction();

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

// Thao tác với option
var currentUser = document.querySelector('#currentUser').value;

var fileItemElement = document.querySelectorAll('.file-item');
var coverPage = document.querySelector('.cover-page');
var optionDialogs = document.querySelectorAll('.option-dialog');
var optionBtns = document.querySelectorAll('.option-btn');
var itemSelected;
// modal variable
var confirmModal = document.querySelector('.confirm-modal');
var cancelConfirmBtn = document.querySelector('.cancel-confirm');
var renameModal = document.querySelector('.rename-modal');
var cancelRenameBtn = document.querySelector('.cancel-rename');
var shareModal = document.querySelector('.share-modal');
var cancelShareBtn = document.querySelector('.cancel-share');

function fileOptionAction() {
	var fileItemElement = document.querySelectorAll('.file-item');
	var optionDialogs = document.querySelectorAll('.option-dialog');
	var optionBtns = document.querySelectorAll('.option-btn');

	fileItemElement.forEach(function(item) {
		var optionBtn = item.querySelector('.option-btn');
		var optionDialog = item.querySelector('.option-dialog');
		var isOpen = false;

		// Ẩn hiện option
		function Open() {
			isOpen = true;
			optionDialog.classList.replace('d-none', 'd-flex');
			coverPage.classList.remove('d-none');
			optionBtn.classList.add('d-flex');
		}

		function Close() {
			isOpen = false;
			optionDialog.classList.replace('d-flex', 'd-none');
			coverPage.classList.add('d-none');
			optionBtn.classList.remove('d-flex');
		}

		function moveDialog() {
			if (optionDialog.getBoundingClientRect().top < 92) {
				optionDialog.classList.remove('mb-5');
				optionDialog.classList.add('mt-5');
			}
			if (optionDialog.getBoundingClientRect().top > (document.body.clientHeight - 135)) {
				optionDialog.classList.add('mb-5');
				optionDialog.classList.remove('mt-5');
			}
		}

		optionBtn.onclick = function() {
			itemSelected = optionBtn.parentElement.parentElement;

			// Tạo value (dir item) cho form
			var itemDir = item.querySelector('.file-dir').value;
			document.querySelectorAll('.itemDir-selected').forEach(function(item) {
				item.value = itemDir;
			});
			
			var itemName = item.querySelector('.file-address').value;
			document.querySelectorAll('.itemName-selected').forEach(function(item) {
				item.value = itemName;
			});


			if (isOpen) {			
				Close();
			} else {
				Open();
			}

			coverPage.onclick = function() {
				isOpen = false;
				coverPage.classList.add('d-none');
				optionDialogs.forEach(function(item) {
					item.classList.replace('d-flex', 'd-none');
				});
				optionBtns.forEach(function(item) {
					item.classList.remove('d-flex');
				});
			}

			moveDialog();
		}


		var shareOption = item.querySelector('.share-option');
		var renameOption = item.querySelector('.rename-option');
		var removeOption = item.querySelector('.remove-option');

		// Mở remove Modal
		removeOption.onclick = function() {
			Close();
			modalLibrary.setAttribute('style', 'display:flex !important');
			confirmModal.classList.replace('d-none', 'd-flex');
		}

		// Mở rename Modal
		renameOption.onclick = function() {
			Close();
			modalLibrary.setAttribute('style', 'display:flex !important');
			renameModal.classList.replace('d-none', 'd-flex');
		}

		// Mở share
		shareOption.onclick = function() {
			Close();
			modalLibrary.setAttribute('style', 'display:flex !important');
			shareModal.classList.replace('d-none', 'd-flex');

			let shareStatus = item.querySelector('.allcanview-file').value;
			document.querySelector('.isallcanview').value = shareStatus;
			if (shareStatus ===  '1') {
				shareStatus = true;
			} else  {
				shareStatus = false;
			}


			//Phần liên kết chia sẻ file
			var linkShare = document.querySelector('.linkShare');

			var fileLink = itemSelected.querySelector('.file-address').value;
			linkShare.value = encodeURI(window.location.hostname + "/share/file/" + currentUser + '/' + fileLink);

			// var optionSelect = document.querySelector('.optionSelect');
			// if (optionSelect.value === 'Riêng tư') {
			// 	privateOptionSeleted();
			// } else if (optionSelect.value === 'Bị hạn chế') {
			// 	limitedOptionSelected();
			// } else if (optionSelect.value === 'Với bất kỳ ai') {
			// 	publicOptionSelected();
			// }

			if (shareStatus) {
				publicOptionSelected();
			} else {
				privateOptionSeleted();
			}
		}
	});
}

fileOptionAction();

cancelConfirmBtn.onclick = function() {
	modalLibrary.setAttribute('style', 'display:none !important');
	confirmModal.classList.replace('d-flex', 'd-none');
}

cancelRenameBtn.onclick = function() {
	modalLibrary.setAttribute('style', 'display:none !important');
	renameModal.classList.replace('d-flex', 'd-none');
}

cancelShareBtn.onclick = function() {
	modalLibrary.setAttribute('style', 'display:none !important');
	shareModal.classList.replace('d-flex', 'd-none');
}

// Nút sao chép link
var copyBtn = document.querySelector('.copy-btn');

new ClipboardJS('.copy-btn');


// Các hàm thao tác với axios

function openToast(toast, notification) { //Hàm  mở toast
	var myAlert = document.querySelector(toast);//select id of toast
	var bsAlert = new bootstrap.Toast(myAlert);//inizialize it
	myAlert.querySelector('.toast-notifi').innerHTML = notification;
	bsAlert.show();//show it
};

var progressUploadFile = document.querySelector('.progressUploadFile');
var progressBarUploadFile = document.querySelector('.progressBarUploadFile');
var fileList = document.querySelector('.list-file');

// Tải lên file
function uploadFile() {
	const config = {
	    onUploadProgress: function(progressEvent) {
	    	var progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
	    	progressUploadFile.classList.replace('d-none', 'd-flex');
	    	progressBarUploadFile.style.width = progress + '%';
	    	progressBarUploadFile.innerText = progress + '%';
	    	if (progress === 100) {
	    		setTimeout(function() {
	    			progressUploadFile.classList.replace('d-flex', 'd-none');
	    		}, 1000);
	    	}
	    }
	}
	var formData = new FormData();
	var docsFile = document.querySelector('.file-upload-input');
	var allcanviewUploadFile = document.querySelector('.allcanviewUploadFile').checked;
	var acv = 0;
	if (allcanviewUploadFile) {
		acv = 1;
	}

	if (docsFile.value == '') {
		openToast('.warningToast', 'Vui lòng chọn tệp tin cần tải lên!');
		return false;
	}

	formData.append('fileUpload', docsFile.files[0]);
	formData.append('currentDir', document.querySelector('.currentDirUploadFile').value);
	formData.append('rootDir', document.querySelector('.rootDirUploadFile').value);
	formData.append('allcanview', allcanviewUploadFile);

	axios.post('uploadFile', formData, config)
	.then(function(response) {
		console.log(response);
		if (response.status === 200) {
			openToast('.sucessToast', 'Tải lên tệp tin thành công!');
		}

		//Thêm file vừa mới upload vào list file
		var currentRootDir = document.querySelector('#currentDir').value;

		var dataReturned = response.data;
		var dataReturned = dataReturned.split('|');

		var fileNameToSave = dataReturned[0];
		var extension = dataReturned[1];
		var fileIcon;
		if (extension === 'docx') { fileIcon = 'docx-icon.png'; }
		else if (extension === 'pdf') { fileIcon = 'pdf-icon.png'; }
		else if (extension === 'xlsx') { fileIcon = 'xlsx-icon.png'; }

		var filename = dataReturned[2];
		var currentTime = dataReturned[3];
		

		fileList.insertAdjacentHTML('beforeend',
		`
			<div class="dirItem file-item d-none card mt-1">
				<input type="hidden" class="file-address" value="${fileNameToSave}">
				<input type="hidden" class="file-dir" value="${currentRootDir}">
				<input type="hidden" class="allcanview-file" value="${acv}">

				<div class="selector">
					<div class="file-type">
						<img src="img/docs-icon/${fileIcon}" alt="${fileIcon}">
					</div>

					<div class="file-info">
						<h6 class="file-info__name">${filename}</h6>
						<div class="file-info__extends d-flex">
							<p class="file-info__time">${currentTime}</p>
							<p class="file-info__type">.${extension} file</p>
						</div>
					</div>
				</div>

				<div class="file-share">
					<i class="option-btn bi bi-three-dots-vertical"></i>
					<div class="option-dialog d-none">
						<ul class="option-list">
							<li class="option-list__item share-option"><i class="bi bi-share"></i><p class="option-text">Chia sẻ</p></li>
							<li class="option-list__item rename-option"><i class="bi bi-pencil-square"></i><p class="option-text">Đổi tên</p></li>
							<li class="option-list__item remove-option"><i class="bi bi-trash"></i><p class="option-text">Xóa</p></li>
						</ul>
					</div>							
				</div>
			</div>
		`);

		hideFile();
		showFile();
		showFolder();
		fileOptionAction();
		previewDocsAction();
	})
	.catch(function(error) {
		console.log(error);
		if (error.response.status === 422) {
			openToast('.errorToast', 'Định dạng tệp tin không hợp lệ!');
		}
	});


	// e.preventDefault();
	return false;
}

function createFolder() {
	var formData = new FormData();
	var allcanview = document.querySelector('.allcanviewCreateFolder').checked;
	var acv;
	if (allcanview) {
		acv = 1;
	} else {
		acv = 0;
	}

	var newFolderName = document.querySelector('.newFolderName').value;

	if (newFolderName.trim() == '') {
		openToast('.warningToast', 'Vui lòng điền tên thư mục!');
		return false;
	}

	formData.append('allcanview', allcanview);
	formData.append('newFolder', newFolderName);
	formData.append('currentDir', document.querySelector('.currentDirCreateFolder').value);
	formData.append('rootDir', document.querySelector('.rootDirCreateFolder').value);

	axios.post('createFolder', formData)
	.then(function(response) {
		console.log(response);
		if (response.status === 200) {
			console.log(response);
			
			openToast('.sucessToast', 'Tạo thư mục thành công!');

			var dataReturned = response.data;
			var dataReturned = dataReturned.split('|');
			var dirFolder = dataReturned[0];
			var currentTime = dataReturned[1];

			fileList.insertAdjacentHTML('afterbegin',
			`
				<div class="dirItem folder-item d-flex card mt-1">
					<input type="hidden" class="folder-address" value="${dirFolder}>">
					<input type="hidden" class="allcanview-folder" value="${acv}">

					<div class="file-type">
						<img src="img/docs-icon/folder-icon.png" alt="folder-icon.png">
					</div>

					<div class="file-info">
						<h6 class="file-info__name">${newFolderName}</h6>
						<div class="file-info__extends d-flex">
							<p class="file-info__time">${currentTime}</p>
							<p class="file-info__type">Folder</p>
						</div>
					</div>

					<div class="file-share">
						<i class="option-btn bi bi-three-dots-vertical"></i>
					</div>
				</div>
			`);
			folderOnlickAction();
		}
	})
	.catch(function(error) {
		console.log(error);
		if (error.response.status === 422) {
			openToast('.warningToast', 'Tên thư mục phải lớn hơn 2 ký tự!');
		}
	});

	return false;
}

// Xóa file

function removeItem() {
	var itemDir = document.querySelector('.itemDirSelectedRemoveItem').value;
	var itemName = document.querySelector('.itemNameSelectedRemoveItem').value;

	var formData = new FormData();
	formData.append('itemDirSelected', itemDir);
	formData.append('itemNameSelected', itemName);

	axios.post('removeItem', formData)
	.then(function(response) {

		itemSelected.remove();

		openToast('.sucessToast', 'Đã xóa tệp tin!');

		document.querySelector('.cancel-confirm').click();
	})
	.catch(function(error) {
		openToast('.errorToast', 'Đã có lỗi xảy ra!');
	})

	return false;
}

function renameItem() {
	var newName = document.querySelector('.newItemName').value;

	if(newName.trim() === "") {
		openToast('.warningToast', 'Vui lòng nhập tên muốn đổi!');
		return false;
	}

	var itemDir = document.querySelector('.itemDirSelectedRenameItem').value;
	var itemName = document.querySelector('.itemNameSelectedRenameItem').value;

	var formData = new FormData();
	formData.append('itemDirSelected', itemDir);
	formData.append('itemNameSelected', itemName);
	formData.append('name', newName);

	axios.post('renameItem', formData)
	.then(function(response) {
		console.log(response);

		itemRenamed = itemSelected.querySelector('.file-info__name');
		extension = itemRenamed.innerText.split('.');
		extension = extension[extension.length - 1];
		itemRenamed.innerText = newName + '.' + extension;

		openToast('.sucessToast', 'Đổi tên thành công!');
	})
	.catch(function(error) {
		console.log(error);

		openToast('.errorToast', 'Đã có lỗi xảy ra!');
	});

	return false;
}

// Thao tác với share
var privateOption = document.querySelector('.private-option');
var limitedOption = document.querySelector('.limited-option');
var publicOption = document.querySelector('.public-option');

var privateOptionDescription = document.querySelector('.private-option-description');
var limitedOptionDescription = document.querySelector('.limited-option-description');
var publicOptionDescription = document.querySelector('.public-option-description');

var linkShareSection = document.querySelector('.link-share-section');
var limitedShareSection = document.querySelector('.share-limited-section');

var linkShare = document.querySelector('.linkShare');

function privateOptionSeleted() {
	removeSelected();
	privateOption.selected = 'selected';

	linkShareSection.classList.remove('d-flex');
	linkShareSection.classList.add('d-none');
	linkShare.classList.remove('public-link');

	limitedShareSection.classList.replace('d-flex', 'd-none');

	privateOptionDescription.classList.replace('d-none', 'd-flex');
	limitedOptionDescription.classList.replace('d-flex', 'd-none');
	publicOptionDescription.classList.replace('d-flex', 'd-none');
} 

function limitedOptionSelected() {
	removeSelected();
	limitedOption.selected = 'selected';

	linkShareSection.classList.remove('d-none');
	linkShareSection.classList.add('d-flex');
	linkShare.classList.remove('public-link');

	limitedShareSection.classList.replace('d-none', 'd-flex');

	privateOptionDescription.classList.replace('d-flex', 'd-none');
	limitedOptionDescription.classList.replace('d-none', 'd-flex');
	publicOptionDescription.classList.replace('d-flex', 'd-none');
}

function publicOptionSelected() {
	removeSelected();
	publicOption.selected = 'selected';

	linkShareSection.classList.remove('d-none');
	linkShareSection.classList.add('d-flex');
	linkShare.classList.remove('public-link');
	linkShare.classList.add('public-link');

	limitedShareSection.classList.replace('d-flex', 'd-none');

	privateOptionDescription.classList.replace('d-flex', 'd-none');
	limitedOptionDescription.classList.replace('d-flex', 'd-none');
	publicOptionDescription.classList.replace('d-none', 'd-flex');
}

function removeSelected() {
	var optionSelect = document.querySelectorAll('.optionSelect>option');

	optionSelect.forEach(function(item) {
		item.removeAttribute('selected');
	});
}

privateOption.onclick = function() {
	privateOptionSeleted();
	let currentDirName = document.querySelector('.itemName-selected').value;
	let isallcanview = document.querySelector('.isallcanview').value;

	if (isallcanview === '1') {
		// Sửa allcanview của file lại thành true (1)
		var formData = new FormData();
		formData.append('itemNameSelected', currentDirName);
		formData.append('isallcanview', isallcanview);

		axios.post('allcanview', formData)
		.then(function(response) {
			console.log(response);
		})
		.catch(function(error) {
			console.log(error);
		});

		document.querySelector('.isallcanview').value = '0';
		let dirItemCurrent = document.querySelectorAll('.file-item');
		dirItemCurrent.forEach(function(item) {
			if (item.querySelector('.file-address').value === currentDirName) {
				item.querySelector('.allcanview-file').value = '0';
			}
		});
		openToast('.sucessToast', 'Đã đặt loại tệp tin thành riêng tư!');
	}
}

limitedOption.onclick = function() {
	limitedOptionSelected();
}

publicOption.onclick = function() {
	publicOptionSelected();
	let currentDirName = document.querySelector('.itemName-selected').value;
	let isallcanview = document.querySelector('.isallcanview').value;

	if (isallcanview === '0') {
		// Sửa allcanview của file lại thành true (1)
		var formData = new FormData();
		formData.append('itemNameSelected', currentDirName);
		formData.append('isallcanview', isallcanview);

		axios.post('allcanview', formData)
		.then(function(response) {
			console.log(response);
		})
		.catch(function(error) {
			console.log(error);
		});

		document.querySelector('.isallcanview').value = '1';
		let dirItemCurrent = document.querySelectorAll('.file-item');
		console.log(dirItemCurrent);
		dirItemCurrent.forEach(function(item) {
			if (item.querySelector('.file-address').value === currentDirName) {
				item.querySelector('.allcanview-file').value = '1';
			}
		});
		openToast('.sucessToast', 'Đã tạo liên kết chia sẻ tệp!');
	}
}

// Đổ dữ liệu vào friend-select-input
var friendsSelectSection = document.querySelector('.list-select-friend');

// Onfocus cho friend-select-input
var friendsSelectInput = document.querySelector('.friend-select');

friendsSelectInput.onfocus = () => {
	friendsSelectSection.classList.replace('d-none', 'd-flex');
}