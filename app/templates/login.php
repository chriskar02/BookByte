<?php
  include 'html/top.html';
  include 'php/connect.php';
  include 'php/session_auth.php';

  $conn = OpenCon();
  $is_auth = getAuth($conn);
  if($is_auth){
    header("Location: /");
    exit;
  }

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

    <form action="" method="POST">
  <div class="form__group field">
    <input type="input" name="username" class="form__field" placeholder="Name" required>
    <label for="username" class="form__label">USERNAME</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="password" name="password" class="form__field" placeholder="Name" required>
    <label for="password" class="form__label">PASSWORD</label>
  </div>
  <div class="input-div">
    <label class="forgot" onclick="alert('Contact your school')">Forgot password?</label>
  </div>

  <div class="input-div">

    <?php
      if(isset($_POST['submit_login'])){

        $username = $_POST['username'];
        $password = $_POST['password'];

        auth_login($conn, $username, $password);
      }

      CloseCon($conn);
    ?>

  </div>





  <div class="input-div-center">
    <button class="button" name="submit_login" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">LOGIN</span>
      </span>
    </button>
  </div>
</form>


		<div class="input-div-center">
				<label class="small">Not a user? </label>
				<a class="small-red"href="register">Register</a>
		</div>

	</div>
</div>



</body></html>
