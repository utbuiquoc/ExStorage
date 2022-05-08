<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>ExStorage - {{$title}}</title>
	<!-- CSS only -->
	<base href="{{asset('')}}">
	<!-- CSS only (bootstrap 5) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="css/layouts/main.css">
	<link rel="icon" type="image/png" sizes="192x192" href="favicon/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
</head>
<body>
	
    @yield('content')

</body>
</html>