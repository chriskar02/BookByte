<?php
  include 'html/top.html';
  include 'php/connect.php';
  include 'php/session_auth.php';

  $conn = OpenCon();
  $is_auth = getAuth($conn);
  if($is_auth){
    header("Location: home");
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

        $query = "select username, password from user where username = '" . $username . "'";
        $result = mysqli_query($conn, $query);
        $tr = mysqli_fetch_row($result);
        if($tr[1] == $password){

          #create cookies (expire after 3 days: (86400 * 3))
          $sessionToken = bin2hex(random_bytes(32));
          $ret = setcookie('session_token', $sessionToken, time() + (86400 * 3), '/');
          if(!$ret){
            echo "<div class='feedback red'>cookie set failed, try again.</div>";
          }
          $ret = setcookie('username', $username, time() + (86400 * 3), '/');
          if(!$ret){
            echo "<div class='feedback red'>cookie set failed, try again.</div>";
          }
          #add token to databse
          $query = "insert into session_tokens (username, token) values ('" . $username . "','" . $sessionToken . "')";
          $result = mysqli_query($conn, $query);
          if($result){
            #login
            header("Location: home");
            exit;
          }
          else{
            echo "<div class='feedback red'>login failed, try again.</div>";
          }
          echo "<div class='feedback green'>logging in...</div>";
        }
        else{
          echo "<div class='feedback red'>incorrect username or password.</div>";
        }
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
