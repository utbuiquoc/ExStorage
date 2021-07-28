<head>
	<link rel="stylesheet" type="text/css" href="css/layouts/navbar.css">
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container-fluid">
		<div class="extend__sidebar">
			<i class="bi bi-arrow-bar-right navbar-size extend-icon"></i>
			<i class="bi bi-arrow-right extendsize navbar-size ms-2"></i>
		</div>

		<div class="navbar__search d-flex">
      		@yield('center')
		</div>

		<div class="navbar__user">
			@yield('right')
		</div>
	</div>
</nav>