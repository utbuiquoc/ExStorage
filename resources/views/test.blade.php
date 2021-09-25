<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.4/axios.min.js" integrity="sha512-lTLt+W7MrmDfKam+r3D2LURu0F47a3QaW5nF0c6Hl0JDZ57ruei+ovbg7BrZ+0bjVJ5YgzsAWE+RreERbpPE1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
	<div class="container">
		<input type="text" class="form-control findInput mt-3">

		<div class="row">
			<div class="col col-md-3 result">
				
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var fileInput = document.querySelector('.findInput');
		var result = document.querySelector('.result');

		fileInput.onkeypress = function(e) {

			let formData = new FormData();
			let text = fileInput.value;
			console.log(text);

			if (text.length > 1) {
				formData.append('text', text);

				axios.post('findItem', formData)
				.then((response) => {
					console.log(response);
					let valueReturned = response.data;

					result.innerHTML = '';
					
					valueReturned.forEach((item) => {
						result.insertAdjacentHTML('beforeend', `<p>${item.user}</p>`)
					})
				})
				.catch((error) => {
					console.log(error);
				})
			}
		}
	</script>
</body>
</html>