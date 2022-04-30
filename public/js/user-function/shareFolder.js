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
	itemSelector = itemElement.querySelector('.selector');

	itemSelector.addEventListener('click', () => {
	// change src attribute of iframe
	preview.src = '/viewer?file=fileUploaded/' + item.value;
	})
});

// Ẩn hiện phần thêm file/folder
var addOption = document.querySelector('.add-option');
var addModal = document.querySelector('.add-modal');
var cancelAddBtn = document.querySelectorAll('.cancel-add-btn');
var selectValue = document.querySelectorAll('.selectValue');
var selection = document.querySelector('#selection');

// Xuất ra file của folder, nút back
var indexLevel = 0;
var folderAddress = document.querySelectorAll(".folder-address");
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

// Upload file
// Kiểm tra có phải là bài tập không
const owner = window.location.pathname.split('/')[3];
const rootDir = document.querySelector('#currentDir').value;
axios.get('confirm-ex-folder', {
	params: {
		'owner': owner,
		'rootDir': rootDir
	}
})
.then(response => {
	console.log(response);
	if (response.data == true) {
		document.querySelector('.exercise').classList.replace('d-none', 'd-block');
	}
})
.catch(error => {
	console.log(error);
});

// ************************ Drag and drop ***************** //
let dropArea = document.getElementById("drop-area");

// Prevent default drag behaviors
;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
	dropArea.addEventListener(eventName, preventDefaults, false);
	document.body.addEventListener(eventName, preventDefaults, false);
})

// Highlight drop area when item is dragged over it
;['dragenter', 'dragover'].forEach(eventName => {
 	 dropArea.addEventListener(eventName, highlight, false);
})

;['dragleave', 'drop'].forEach(eventName => {
 	 dropArea.addEventListener(eventName, unhighlight, false);
})

// Handle dropped files
dropArea.addEventListener('drop', handleDrop, false);

function preventDefaults (e) {
	e.preventDefault();
	e.stopPropagation();
}

function highlight(e) {
	dropArea.classList.add('highlight');
}

function unhighlight(e) {
	dropArea.classList.remove('active');
}

function handleDrop(e) {
	var dt = e.dataTransfer;
	var files = dt.files;

	handleFiles(files);
}

function handleFiles(files) {
	files = [...files];
	files.forEach(uploadFile);
	files.forEach(previewFile);
}

function previewFile(file) {
	let fileType = file.name.split('.').slice(-1)[0];
	console.log(fileType);
	if (fileType === 'docx' || fileType == 'doc') {
		document.getElementById('gallery').insertAdjacentHTML('beforeend', `
			<div class="doc-file">
				<img src="./docx-icon-large.png" alt="doc-icon">

				<div class="file-info-upload">
					<h3 class="file-name-upload">${file.name}</h3>
				</div>
			</div>
		`);
	} else if (fileType == 'pdf') {
		document.getElementById('gallery').insertAdjacentHTML('beforeend', `
			<div class="doc-file">
				<img src="./pdf-icon.png" alt="doc-icon">

				<div class="file-info-upload">
					<h3 class="file-name-upload">${file.name}</h3>
				</div>
			</div>
		`);
	} else {
		let reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onloadend = function() {
			img = reader.result;
			document.getElementById('gallery').insertAdjacentHTML('beforeend', `
				<div class="doc-file">
					<img src="${img}" alt="img">

					<div class="file-info-upload">
						<h3 class="file-name-upload">${file.name}</h3>
					</div>
				</div>
			`);
		}
	}
}

function uploadFile(file, i) {
	var formData = new FormData();

	// Update progress (can be used to show progress indicator)
	// xhr.upload.addEventListener("progress", function(e) {;
	// 	updateProgress(i, (e.loaded * 100.0 / e.total) || 100);
	// });

	// xhr.addEventListener('readystatechange', function(e) {
	// 	if (xhr.readyState == 4 && xhr.status == 200) {
	// 		updateProgress(i, 100); // <- Add this
	// 	}
	// 	else if (xhr.readyState == 4 && xhr.status != 200) {
	// 	// Error. Inform the user
	// 	}
	// })

	formData.append('upload_preset', 'ujpu6gyk');
	formData.append('file', file);

	console.log(file);
	// xhr.send(formData);
}