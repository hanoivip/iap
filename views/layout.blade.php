<html>
<head>
<title>@yield('title')</title>
</head>
<body>
	<style>
html, body {
	height: 100%;
}

.main {
	height: 100%;
	width: 100%;
	display: table;
}

form {
	margin: auto;
	width: 50%;
	border: 3px solid green;
	padding: 10px;
	text-align: center;
	font-size: xx-large;
}

form img {
    width: 60%;
}

form p.success {
    color: green;
}

form p.error {
    color: red;
}
</style>
	<div class="main">@yield('content')</div>
</body>
</html>