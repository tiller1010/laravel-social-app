<!DOCTYPE html>
<html>
<head>
	<title>Inbox</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
	<h1>Inbox</h1>
	<div id="app" class="container">
		@yield('messages')
	</div>
</body>
	<script src="{{ asset('js/app.js') }}"></script>
</html>