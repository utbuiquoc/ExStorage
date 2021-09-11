var provincesElement = document.querySelector('.provinces');
var provinceChildsElement = document.querySelector('.city');

// Fetch API Tỉnh/Thành
const url = 'https://provinces.open-api.vn/api/';

fetch(url)
.then(function(response) {
	var provincesArray = response.json();
	provincesArray.then(function(provincesItem) {
		console.log(provincesItem);
		provincesItem.forEach(function(item) {
			provincesElement.insertAdjacentHTML('beforeend', `
				<option value='${item.name}' class='${item.code}'>${item.name}</option>
			`);
		});
	});
})
.catch(function(error) {
	console.log(error);
});

// Fetch API thành phố/quận huyện khi lấy đc mã code Tỉnh/Thành
provincesElement.onchange = function() {
	let provinceSelected = this.options[this.selectedIndex].text;
	let provinceCode = provincesElement.options[provincesElement.selectedIndex].classList[0];
	console.log(provinceCode);
	const url = `https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`;

	fetch(url)
	.then(function(response) {
		var provincesChildsArray = response.json();
		provincesChildsArray.then(function(provinceChildsItem) {
			let provinceDistricts = provinceChildsItem.districts;
			// In ra thành phố/Quận Huyện với Tỉnh/Thành đã chọn
			if (provinceCode !== "") {
				provinceChildsElement.removeAttribute('disabled');

				// Chèn các tỉnh thành vào
				provinceChildsElement.innerHTML = '';

				provinceDistricts.forEach(function(districtsItem) {
					provinceChildsElement.insertAdjacentHTML('beforeend', `
						<option value='${districtsItem.name}'>${districtsItem.name}</option>
					`);
				})
					
				console.log(provinceChildsItem.districts);
			} else { // Nếu tỉnh thành đc chọn ko hợp lệ (cố tình chọn vào cái mặc định)
				provinceChildsElement.removeAttribute('disabled');
				provinceChildsElement.setAttribute('disabled', '');
				provinceChildsElement.innerHTML = '<option value="0">--- Chọn Thành Phố/Quận Huyện ---</option>';

			}
		})
	})
	.catch(function(error) {
		console.log(error);
	});
}