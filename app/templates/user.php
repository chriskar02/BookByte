<?php
	include 'html/top.html';
?>
<title>[username here] | BookByte</title>

<link rel="stylesheet" type="text/css" href="../static/css/nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/btn-nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/user.css">

</head><body>
	<?php
		include 'html/header.html';
	?>
<main>
	<div class="area-left">
		<button id="selected-left"class="btn-simple-blue"onclick="selectThisBtn(this);">Account</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Loans</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Reservations</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">History</button>
		<p>HANDLER</p>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Resistration Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Loan Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Reservation Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Review Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Add New Book</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Find School Users</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Stats</button>
		<p>ADMIN</p>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Backup / Restore</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Handler Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this);">Stats</button>
	</div>
	<div class="content-right"></div>
</main>

<script type="text/javascript">
	function selectThisBtn(d){
		if(document.getElementById('selected-left')) document.getElementById('selected-left').id="";
		d.id="selected-left";
	}
</script>

</body></html>
