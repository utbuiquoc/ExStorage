// Kéo ra kéo vào sidebar
var extendsBtn = document.querySelector('.extend-icon');
var sidebar = document.querySelector('.sidebar');
var wrapperIn = document.querySelector('.wrapper-in');
var isExtend = false;
var navbar = document.querySelector('.navbar');
var navbarText = document.querySelectorAll('.file-btn--description');

var widthExtend = '150px';
var widthNoExtend = '50px';

extendsBtn.onclick = function() {
	if (isExtend) {
		isExtend = false;
		sidebar.classList.remove('sidebar-extended');
		sidebar.classList.add('sidebar-inExtended');

		wrapperIn.classList.remove('extend-for-sidebar');
		wrapperIn.classList.add('inExtend-for-sidebar');

		extendsBtn.classList.add('bi-arrow-bar-right');
		extendsBtn.classList.remove('bi-arrow-bar-left');

		navbar.classList.remove('navbar-extends');
		navbar.classList.add('navbar-inExtends');

		navbarText.forEach(function(textENB) {
			textENB.style.display = 'none';
		});
	} else {
		isExtend = true;
		sidebar.classList.remove('sidebar-inExtended');
		sidebar.classList.add('sidebar-extended');

		wrapperIn.classList.remove('inExtend-for-sidebar');
		wrapperIn.classList.add('extend-for-sidebar');

		extendsBtn.classList.remove('bi-arrow-bar-right');
		extendsBtn.classList.add('bi-arrow-bar-left');

		navbar.classList.remove('navbar-inExtends');
		navbar.classList.add('navbar-extends');

		setTimeout(function() {
			navbarText.forEach(function(textENB) {
				textENB.style.display = 'block';
			});
		}, 175);
	}
}

// Phần chọn selected cho thanh sidebar

var itemSelection = document.querySelector('#itemSidebar-Selected').value;

var sidebarSelection = document.querySelectorAll('.item');

sidebarSelection.forEach(function(sidebarItem) {
	if (itemSelection === sidebarItem.id) {
		sidebarItem.classList.add('selected');
	}
});