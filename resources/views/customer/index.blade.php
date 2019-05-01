<!DOCTYPE html>
<html>
<head>
	<title>Application</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
	
	<style type="text/css">
		.pagination > li > a, .pagination > li:first-child > a, .pagination > li:last-child > a, .pagination > li > span, .pagination > li:first-child > span, .pagination > li:last-child > span {
			border-radius: 100px !important;
    		margin: 0 2px;
   
		}
	</style>
</head>
<body>
	<div class="container mt-4">
		<div id="app">
			<data-viewer source="api/customer" title="Customer"></data-viewer>
		</div>
	</div>

	<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
	
</body>
</html>