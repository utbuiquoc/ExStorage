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
		// sidebar.style.width = widthNoExtend;
		// wrapperIn.style.marginLeft = widthNoExtend;
		// navbar.style.width = `calc(100% - ${widthNoExtend})`;
		extendsBtn.classList.add('bi-arrow-bar-right');
		extendsBtn.classList.remove('bi-arrow-bar-left');
		navbarText.forEach(function(textENB) {
			textENB.style.display = 'none';
		});
	} else {
		isExtend = true;
		sidebar.classList.remove('sidebar-inExtended');
		sidebar.classList.add('sidebar-extended');
		wrapperIn.classList.remove('inExtend-for-sidebar');
		wrapperIn.classList.add('extend-for-sidebar');
		// sidebar.style.width = widthExtend;
		// wrapperIn.style.marginLeft = widthExtend;
		// navbar.style.width = `calc(100% - ${widthExtend})`;
		extendsBtn.classList.remove('bi-arrow-bar-right');
		extendsBtn.classList.add('bi-arrow-bar-left');
		setTimeout(function() {
			navbarText.forEach(function(textENB) {
				textENB.style.display = 'block';
			});
		}, 175);
	}
}