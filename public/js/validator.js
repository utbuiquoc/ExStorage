
// Đối tượng 'Validator'
function Validator(options) {



	// Lấy element của form cần validate
	var formElement = document.querySelector(options.form);

	var selectorRules = {};
	if (formElement) {
		
		// Khi submit form
		formElement.onsubmit = function (e) {
			e.preventDefault();

			// Lặp qua từng rule
			options.rules.forEach(function(rule) {
				let inputElement = formElement.querySelector(rule.selector);
				validate(inputElement, rule);
			});
		}

		// Lặp qua mỗi rule và xử lí (lắng nghe sư)
		options.rules.forEach(function(rule) {

			// Lưu lại các rules cho mỗi input

			if (Array.isArray(selectorRules[rule.selector])) {
				selectorRules[rule.selector].push(rule.test)
			} else {
				selectorRules[rule.selector] = [rule.test];
			}

			


			let inputElement = formElement.querySelector(rule.selector);
			var errorElement = inputElement.parentElement.querySelector(options.errorSelector);
			// Hàm thực hiện validate 
			function validate(inputElement, rule) {
				var errorMessage;

				// Lấy ra các rules của selector
				var rules = selectorRules[rule.selector] 

				// Lặp qua từng rule & kiểm tra
				// Nếu có lỗi => dừng việc kiểm tra
				for (var i = 0; i < rules.length; ++i) {
					errorMessage = rules[i](inputElement.value);
					if (errorMessage) break;
				}

				if (errorMessage) {
					errorElement.innerText = errorMessage;
					inputElement.parentElement.classList.add('invalid');
				} else {
					errorElement.innerText = '';
					inputElement.parentElement.classList.remove('invalid');
				}
			}

			//Lấy Element của form cần validate
			if (inputElement) {
				inputElement.onblur = function() {
					// value: inputElement.value
					// test func: rule.test
					validate(inputElement, rule)
				}
			}

			// Xử lí mỗi khi người dùng nhập vào input
			inputElement.oninput = function() {
				errorElement.innerText = '';
				inputElement.parentElement.classList.remove('invalid');
			}
		});

		// console.log(selectorRules);
	}
}

// Định nghĩa các rules
// Nguyên tắc của các rules:
// 1. Khi có lỗi => trả ra message lỗi
// 2. Khi hợp lệ => không trả ra cái gì cả (undefined);
Validator.isRequired = function(selector, message) {
	return {
		selector: selector,
		test: function(value) {
			return value.trim() ? undefined : message || 'Vui lòng nhập trường này';
		}
	}
}

Validator.isEmail = function(selector, message) {
	return {
		selector: selector,
		test: function(value) {
			var regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
			return regex.test(value) ? undefined : message || 'Trường này phải là email';
		}
	}
}

Validator.minLength = function(selector, min, message) {
	return {
		selector: selector,
		test: function(value) {
			return value.length >= min ? undefined : message || `Vui lòng nhập tối thiểu ${min} kí tự`;
		}
	}
}

Validator.isConfirmed = function (selector, getConfirmValue, message) {
	return {
		selector: selector,
		test: function (value) {
			return value === getConfirmValue() ? undefined : message || 'Giá trị nhập vào không chính xác'
		}
	}
}


// submitBtn = document.querySelector('#form-submitBtn');
// submitBtn.submit();