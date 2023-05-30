<?php
  include 'html/top.html';
  include 'php/connect.php';
  include 'php/session_auth.php';
  include 'php/nav_buttons.php';
  include 'php/html_disp.php';

  $conn = OpenCon();
  $is_auth = getAuth($conn);
  if(!$is_auth){
    header("Location: /login");
    exit;
  }

  logout_option($conn);

	$username = $_COOKIE['username'];
	if($page_username == "") $page_username = $username;

	# page_username exists from routes (index.php)

	#check if user exists and get user info
  $query = "select name, email, sch_id, user_verified, password from user where username = '".$page_username."'";
  $result = mysqli_query($conn, $query);
  $tr=mysqli_fetch_row($result);
  if(!mysqli_num_rows($result) || $tr[0]==""){
    header("Location: /404");
    exit;
  }

	$name = $tr[0];
	$email = $tr[1];
	$sch_id = $tr[2];
	$user_verified = $tr[3];
	$password = $tr[4];

	$query = "select name, city from school where id = '".$sch_id."'";
  $result = mysqli_query($conn, $query);
  $tr=mysqli_fetch_row($result);

	$sch_full_name = $tr[0]." of ".$tr[1];

?>
<title><?php echo $page_username ?> | BookByte</title>

<link rel="stylesheet" type="text/css" href="../static/css/nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/btn-nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/dropdown.css">
<link rel="stylesheet" type="text/css" href="../static/css/user.css">

</head><body>
	<?php
		include 'php/header.php';
	?>
<main>
  <?php

  #test if is a verfied handler
  $query = "select handler_verified, handler_request from teacher where username = '".$username."'";
  $result = mysqli_query($conn, $query);
  $tr=mysqli_fetch_row($result);
  $is_req_handler = 0;
  $is_teacher = 0;
  if(mysqli_num_rows($result) > 0 && $tr[0]!=""){
    $is_teacher = 1;
    if($tr[0] == 1){
      $is_verified_handler = 1;
      echo "<script>document.getElementById('tag-verified-handler').style.display='inline-block';</script>";
    }
    else{
      $is_verified_handler = 0;
      if($tr[1] == 1){
        $is_req_handler = 1;
        echo "<script>document.getElementById('tag-req-handler').style.display='inline-block';</script>";
      }
    }
  }

  #test if user is a verified handler in the same school as the user profile
  $query = "select v.username from verified_handler as v join user on v.username = user.username where v.username = '".$username."' and user.sch_id = '".$sch_id."'";
  $result = mysqli_query($conn, $query);
  $tr=mysqli_fetch_row($result);
  if(mysqli_num_rows($result) > 0 && $tr[0]!=""){
    $is_valid_handler = 1;
    echo "<script>document.getElementById('tag-valid-handler').style.display='inline-block';</script>";
  }
  else{
    $is_valid_handler = 0;
  }

  #test if user is a verified handler in the same school as the user profile
  $query = "select username from admin where username = '".$username."'";
  $result = mysqli_query($conn, $query);
  $tr=mysqli_fetch_row($result);
  if(mysqli_num_rows($result) > 0 && $tr[0]!=""){
    $is_admin = 1;
    echo "<script>document.getElementById('tag-admin').style.display='inline-block';</script>";
  }
  else{
    $is_admin = 0;
  }

  if($username == $page_username){
    $is_my_profile = 1;
  }

  ?>
	<div class="area-left">
    <p>USER</p>
		<button id="selected-left"class="btn-simple-blue"onclick="selectThisBtn(this,1);"><?php if($is_my_profile) echo "My Account"; else echo $page_username; ?></button>

    <?php
    if($is_valid_handler || $is_my_profile) {
      echo '<button class="btn-simple-blue"onclick="selectThisBtn(this,2);">Loans</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,3);">Reservations</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,4);">Reviews</button>';
    }
    if($is_my_profile && $is_teacher && !$is_verified_handler) {
      echo '<button class="btn-simple-blue"onclick="selectThisBtn(this,15);">Become Handler</button>';
    }
    if($is_my_profile && $is_verified_handler) {
      echo '<p>HANDLER</p>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,5);">Registration Requests</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,6);">Loan Requests</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,8);">Review Requests</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,9);">Add New Book</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,26);">Edit a Book</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,10);">Find Users</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,16);">Avg Ratings Per User</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,17);">Avg Ratings Per Category</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,18);">Late Returns</button>';
    }
    if($is_my_profile && $is_admin) {
      echo '<p>ADMIN</p>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,12);">Backup / Restore</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,13);">Handler Requests</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,25);">New School</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,14);">3.1.1</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,19);">3.1.2</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,20);">3.1.3</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,21);">3.1.4</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,22);">3.1.5</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,23);">3.1.6</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,24);">3.1.7</button>';
    }
    ?>
  </div>


	<div class="content-right">
    <?php
    echo '<div id="page1"class="userpage">'; include 'user_pages/account.php'; echo "</div>";
    if($is_valid_handler || $is_my_profile) {
      echo '<div id="page2"class="userpage">'; include 'user_pages/loans.php'; echo "</div>";
      echo '<div id="page3"class="userpage">'; include 'user_pages/reservations.php'; echo "</div>";
      echo '<div id="page4"class="userpage">'; include 'user_pages/reviews.php'; echo "</div>";
    }
    if($is_my_profile && $is_teacher && !$is_verified_handler) {
      echo '<div id="page15"class="userpage">'; include 'user_pages/reqtohandler.php'; echo "</div>";
    }
    if($is_my_profile && $is_verified_handler) {
      echo '<div id="page5"class="userpage">'; include 'user_pages/regreq.php'; echo "</div>";
      echo '<div id="page6"class="userpage">'; include 'user_pages/loanreq.php'; echo "</div>";
      echo '<div id="page8"class="userpage">'; include 'user_pages/reviewreq.php'; echo "</div>";
      echo '<div id="page9"class="userpage">'; include 'user_pages/newbook.php'; echo "</div>";
      echo '<div id="page26"class="userpage">'; include 'user_pages/editbook.php'; echo "</div>";
      echo '<div id="page10"class="userpage">'; include 'user_pages/findusers.php'; echo "</div>";
      echo '<div id="page16"class="userpage">'; include 'user_pages/avgperuser.php'; echo "</div>";
      echo '<div id="page17"class="userpage">'; include 'user_pages/avgpercategory.php'; echo "</div>";
      echo '<div id="page18"class="userpage">'; include 'user_pages/latereturns.php'; echo "</div>";
    }
    if($is_my_profile && $is_admin) {
      echo '<div id="page12"class="userpage">'; include 'user_pages/backuprestore.php'; echo "</div>";
      echo '<div id="page13"class="userpage">'; include 'user_pages/handlerreq.php'; echo "</div>";
      echo '<div id="page25"class="userpage">'; include 'user_pages/newschool.php'; echo "</div>";
      echo '<div id="page14"class="userpage">'; include 'user_pages/admin1.php'; echo "</div>";
      echo '<div id="page19"class="userpage">'; include 'user_pages/admin2.php'; echo "</div>";
      echo '<div id="page20"class="userpage">'; include 'user_pages/admin3.php'; echo "</div>";
      echo '<div id="page21"class="userpage">'; include 'user_pages/admin4.php'; echo "</div>";
      echo '<div id="page22"class="userpage">'; include 'user_pages/admin5.php'; echo "</div>";
      echo '<div id="page23"class="userpage">'; include 'user_pages/admin6.php'; echo "</div>";
      echo '<div id="page24"class="userpage">'; include 'user_pages/admin7.php'; echo "</div>";
    }
    ?>
	</div>
</main>
<script type="text/javascript">
  function selectThisBtn(d,pageindex){
    if(document.getElementById('selected-left')) document.getElementById('selected-left').id="";
    d.id="selected-left";
    if(document.getElementsByClassName('userpage')) for(const i of document.getElementsByClassName('userpage')) i.style.display = 'none';
    if(document.getElementById('page'+(pageindex).toString()))document.getElementById('page'+(pageindex).toString()).style.display = 'block';
  }
</script>




</body></html>
