<?php
  include 'html/top.html';
?>

<title>Register | BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/login.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/dropdown.css">

</head><body>

<div class="main">
	<div class="main-title">
		<img src="../static/assets/logo-light.png" alt="logo couldn't load">
	</div>
	<div class="main-input box-shadow">

		<div class="form__group field">
		    <input type="input" class="form__field" placeholder="Name" required="">
		    <label for="name" class="form__label">USERNAME</label>
		</div>
		<br class="half-br">
		<div class="form__group field">
				<input type="password" class="form__field" placeholder="Name" required="">
				<label for="name" class="form__label">PASSWORD</label>
		</div>

		<br class="half-br">
		<div class="form__group field">
				<input type="input" class="form__field" placeholder="Name" required="">
				<label for="name" class="form__label">EMAIL</label>
		</div>

		<br class="half-br">
		<div class="form__group field">
				<input type="input" class="form__field" placeholder="Name" required="">
				<label for="name" class="form__label">FULL NAME</label>
		</div>

		<br class="half-br">
		<div class="input-div-center">
		<div class="dropdown">

		  <select class="dropdown-select">
		    <option value="default" disabled selected hidden>select school</option>
		    <option value="item1">Item 1</option>
		    <option value="item2">Item 2</option>
		    <option value="item3">Item 3</option>
		  </select>
		</div>
		</div>



		<div class="input-div-center">
			<button class="button">
    		<span class="button_lg">
        	<span class="button_sl"></span>
        	<span class="button_text">REGISTER</span>
    		</span>
			</button>
		</div>

		<div class="input-div-center">
				<label class="small">Already a user? </label>
				<a class="small-red"href="login.html">Log In</a>
		</div>

	</div>
</div>

<script>

</script>

</body></html>
