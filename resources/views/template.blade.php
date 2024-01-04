<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>WatchWise</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<style>
		.vote-0 {
			background-color: #adb5bd;
		}

		.vote-1 {
			background-color: #58151c;
		}

		.vote-2 {
			background-color: #842029;
		}

		.vote-3 {
			background-color: #b02a37;
		}

		.vote-4 {
			background-color: #dc3545;
		}

		.vote-5 {
			background-color: #e35d6a;
		}

		.vote-6 {
			background-color: #0a3622;
		}

		.vote-7 {
			background-color: #0f5132;
		}

		.vote-8 {
			background-color: #146c43;
		}

		.vote-9 {
			background-color: #198754;
		}

		.vote-10 {
			background-color: #479f76;
		}

		svg {
			width: 25px;
			height: 25px;
		}
	</style>
</head>

<body>
	<x-menu />
	<div class="container-fluid">
		@yield('content')
	</div>

</body>

</html>
