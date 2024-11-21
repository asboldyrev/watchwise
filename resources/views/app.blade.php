<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>WatchWise</title>
	@vite(['resources/scss/style.scss', 'resources/js/app.js'])
</head>

<body>
	<div id="app"></div>
</body>

</html>
