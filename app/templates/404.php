<?php
  include 'html/top.html';
?>
<title>404 | BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">

<style type="text/css">
html{
	text-align: center;
}
div, p{
	width: 95%;
	max-width: 300px;
	margin: auto;
	margin-top:70px;
}
p{
	font-size: 2em;
	color: #ababab;
}
</style>

</head><body>

		<p>
  		Error 404: page not found
		</p>


		<div>
			<button onclick="window.location.replace('home')" class="button">
				<span class="button_lg">
					<span class="button_sl"></span>
					<span class="button_text">Go HOME</span>
				</span>
			</button>
		</div>

</body></html>
