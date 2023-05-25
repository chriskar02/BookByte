<?php
  include 'html/top.html';
?>

<title>Login | BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/login.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">

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
		<div class="input-div">
			<label class="forgot"onclick="alert('Contact your school')">Forgot password?</label>
		</div>
		<div class="input-div-center">
			<button class="button">
    		<span class="button_lg">
        	<span class="button_sl"></span>
        	<span class="button_text">LOGIN</span>
    		</span>
			</button>
		</div>

		<div class="input-div-center">
				<label class="small">Not a user? </label>
				<a class="small-red"href="register.html">Register</a>
		</div>

	</div>
</div>

<script>

</script>

</body></html>
