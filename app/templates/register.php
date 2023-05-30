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
	<form class="main-input box-shadow"action=""method="post">

		<div class="form__group field">
		    <input type="input" class="form__field" name="username"placeholder="" maxlength="20"required="">
		    <label for="name" class="form__label">USERNAME</label>
		</div>
		<br class="half-br">
		<div class="form__group field">
				<input type="password" class="form__field" name="password"placeholder="" maxlength="40"required="">
				<label for="name" class="form__label">PASSWORD</label>
		</div>

		<br class="half-br">
		<div class="form__group field">
				<input type="input" class="form__field"name="email" placeholder=""maxlength="60" required="">
				<label for="name" class="form__label">EMAIL</label>
		</div>

		<br class="half-br">
		<div class="form__group field">
				<input type="input" class="form__field" name="fullname"placeholder="" maxlength="50"required="">
				<label for="name" class="form__label">FULL NAME</label>
		</div>

		<br class="half-br">
		<div class="input-div-center">
		<div class="dropdown">

		  <select class="dropdown-select"name="school-select" required="">
		    <option value="">select school</option>
        <?php

        $query = "select name, city, id from school";
        $result = mysqli_query($conn, $query);
        while($tr = mysqli_fetch_row($result)){
          echo '<option value="'.$tr[2].'">'. $tr[0] .' of '.$tr[1].'</option>';
        }

        ?>
		  </select>
		</div>
		</div>

    <br class="half-br">
		<div class="input-div-center">
      register as
		<div class="dropdown">

		  <select class="dropdown-select"name="status-select" required="" onchange="statusChanged(this.value)">
        <option value="student">student</option>
		    <option value="teacher">teacher</option>
		  </select>
		</div>
    <br class="half-br">
    <div class="input-div-center" id="bdate2" style="display:none;">
      birth date
    <input type="date" id="bdate" name="bdate" value="" />
    </div>
		</div>
    <script>
      function statusChanged(s){
        const bdate2 = document.getElementById('bdate2');
        const bdate = document.getElementById('bdate');
        if(s === 'teacher'){
          bdate2.style.display='block';
          bdate.required = true;
        }
        else{
          bdate2.style.display='none';
          bdate.required = false;
        }
      }

    </script>





		<div class="input-div-center">
			<button class="button"type="submit"name="submit_register">
    		<span class="button_lg">
        	<span class="button_sl"></span>
        	<span class="button_text">REGISTER</span>
    		</span>
			</button>
		</div>

    <?php
      if(isset($_POST['submit_register'])){
        $sch_id = $_POST['school-select'];
        $status = $_POST['status-select'];
        $bdate = $_POST['bdate'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $fullname = $_POST['fullname'];

        $query = "select * from user where username = '" . $username . "'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
          $tr = mysqli_fetch_row($result);
          echo "<div class='feedback red'>username exists!</div>";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          echo "<div class='feedback red'>invalid emailaddress!</div>";
        }
        else{
          #valid input
          #save to database
          $query = "insert into user (username, password, email, name, sch_id, user_verified) values ('".$username."','".$password."','".$email."','".$fullname."','".$sch_id."','0')";
          if($result = mysqli_query($conn, $query)){
            #success
            if($status == 'teacher'){
              $query = "insert into teacher (username, birth) values ('".$username."','".$bdate."')";
              if($result = mysqli_query($conn, $query)){
                echo "<div class='feedback green'>successful registration!</div>";
                auth_login($conn, $username, $password);
              }
              else{
                echo "<div class='feedback red'>[database error] account created, but not as teacher. Contact your network administrator.</div>";
              }
            }
          }
          else{
            echo "<div class='feedback red'>[database error] registration failed, try again.</div>";
          }
        }
      }

      #CloseCon($conn);
    ?>

		<div class="input-div-center">
				<label class="small">Already a user? </label>
				<a class="small-red"href="/login">Log In</a>
		</div>

	</form>
</div>

<script>

</script>

</body></html>
