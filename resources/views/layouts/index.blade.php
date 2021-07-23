<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>DocsStorage - {{$title}}</title>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="css/layouts/main.css">
</head>
<body>
	<div class="wrapper">
		@yield('itemSidebarSelected')
	@include('layouts.sidebar', ['bien' => 'test'])

		<div class="wrapper-in">
			@yield('content')
		</div>
	</div>

	<script type="text/javascript" src="js/sidebar/script.js"></script>
</body>
</html>