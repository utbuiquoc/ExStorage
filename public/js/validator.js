function Validator(options) {

	function getParent(element, selector) {
		while (element.parentElement) {
			if (element.parentElement.matches(selector)) {
				return element.parentElement;
			}

			element = element.parentElement;
		}
	}

	var selectorRules = {};

	// Hàm thực hiện validate 
	function validate(inputElement, rule) {
		var errorMessage;
		var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);

		// Lấy ra các rules của selector
		var rules = selectorRules[rule.selector] 

		// Lặp qua từng rule & kiểm tra
		// Nếu có lỗi => dừng việc kiểm tra
		for (var i = 0; i < rules.length; ++i) {
			switch (inputElement.type) {
                case 'radio':
                case 'checkbox':
                    errorMessage = rules[i](
                        formElement.querySelector(rule.selector + ':checked')
                    );
                    break;
                default:
                    errorMessage = rules[i](inputElement.value);
            }
			
			if (errorMessage) break;
		}

		if (errorMessage) {
			errorElement.innerText = errorMessage;
			getParent(inputElement, options.formGroupSelector).classList.add('invalid');
		} else {
			errorElement.innerText = '';
			getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
		}

		return !errorMessage;
	}


	//Lấy Element của form cần validate
	var formElement = document.querySelector(options.form);
	if (formElement) {
		
		// Khi submit form
		formElement.onsubmit = function (e) {
			e.preventDefault();

			var isFormValid = true;

			// Lặp qua từng rule và validate
			options.rules.forEach(function(rule) {
				var inputElement = formElement.querySelector(rule.selector);
				var isValid = validate(inputElement, rule);
				if (!isValid) {
					isFormValid = false;
				}
			});


			if (isFormValid) {
				// Trường hợp submit với javascript
				if (typeof options.onSubmit === 'function') {

				var enableInputs = formElement.querySelectorAll('[name]:not([disabled])');
			
				var formValues = Array.from(enableInputs).reduce(function (values, input) {
					
					switch (input.type) {
						case 'radio':
							values[input.name] = formElement.querySelector('input[name="' + input.name + '"]:checked').value;
							break;
						case 'checkbox':
							// statements_1
							if (!input.matches(':checked')) {
								values[input.name] = '';
								return values;
							}

							if (!Array.isArray(values[input.name])) {
								values[input.name] = [];
							}

							values[input.name].push(input.value);

							break;
						case 'file':
							values[input.name] = input.files;
							break;
						default:
							// statements_def
							values[input.name] = input.value;
							break;
					}

					return values;
				}, {});	


					options.onSubmit(formValues)
				}
				// Trường hợp submit với hành vi mặc định
				else {
					formElement.submit()
				}
			}
		}

		// Lặp qua mỗi rule và xử lí (lắng nghe sự kiện blur, input, ...)
		options.rules.forEach(function(rule) {

			// Lưu lại các rules cho mỗi input
			if (Array.isArray(selectorRules[rule.selector])) {
				selectorRules[rule.selector].push(rule.test)
			} else {
				selectorRules[rule.selector] = [rule.test];
			}

			var inputElements = formElement.querySelectorAll(rule.selector);
			 

			Array.from(inputElements).forEach(function (inputElement) {
				//Lấy Element của form cần validate
				if (inputElement) {
					// Xử lí trường hợp blur khỏi input
					inputElement.onblur = function() {
						// value: inputElement.value
						// test func: rule.test
						validate(inputElement, rule)
					}
				}

				// Xử lí mỗi khi người dùng nhập vào input
				inputElement.oninput = function() {
					var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
					errorElement.innerText = '';
					getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
				}
			});

			
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
			return value ? undefined : message || 'Vui lòng nhập trường này';
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