<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>DocsStorage - {{$title}}</title>
	<!-- CSS only -->
	<base href="{{asset('')}}">
	<!-- CSS only (bootstrap 5) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="css/layouts/main.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
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