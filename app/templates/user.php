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
  if(mysqli_num_rows($result) > 0 && $tr[0]!=""){
    $is_teacher = 1;
    if($tr[0] == 1){
      $is_verified_handler = 1;
      echo "<script>document.getElementById('tag-verified-handler').style.display='inline-block';</script>";
    }
    else{
      $is_verified_handler = 0;
      if($tr[1] == 1){
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
		<button id="selected-left"class="btn-simple-blue"onclick="selectThisBtn(this,1);"><?php if($is_my_profile) echo "My Account"; else echo $page_username; ?></button>

    <?php
    if($is_valid_handler || $is_admin || $is_my_profile) {
      echo '<button class="btn-simple-blue"onclick="selectThisBtn(this,2);">Loans</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,3);">Reservations</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,4);">Reviews</button>';
    }
    if($is_my_profile && ($is_verified_handler || $is_admin)) {
      echo '<p>HANDLER</p>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,5);">Registration Requests</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,6);">Loan Requests</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,7);">Reservation Requests</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,8);">Review Requests</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,9);">Add New Book</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,10);">Find Users</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,11);">Stats</button>';
    }
    if($is_my_profile && $is_admin) {
      echo '<p>ADMIN</p>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,12);">Backup / Restore</button>
      <button class="btn-simple-blue"onclick="selectThisBtn(this,13);">Handler Requests</button>
  		<button class="btn-simple-blue"onclick="selectThisBtn(this,14);">Stats</button>';
    }
    ?>
  </div>
	<div class="content-right">
    <?php
    echo '<div id="page1"class="userpage">'; include 'user_pages/account.php'; echo "</div>";
    if($is_valid_handler || $is_admin || $is_my_profile) {
      echo '<div id="page2"class="userpage">'; include 'user_pages/loans.php'; echo "</div>";
      echo '<div id="page3"class="userpage">'; include 'user_pages/reservations.php'; echo "</div>";
      echo '<div id="page4"class="userpage">'; include 'user_pages/reviews.php'; echo "</div>";
    }
    if($is_my_profile && ($is_verified_handler || $is_admin)) {
      echo '<div id="page5"class="userpage">'; include 'user_pages/regreq.php'; echo "</div>";
      echo '<div id="page6"class="userpage">'; include 'user_pages/loanreq.php'; echo "</div>";
      echo '<div id="page7"class="userpage">'; include 'user_pages/rsvreq.php'; echo "</div>";
      echo '<div id="page8"class="userpage">'; include 'user_pages/reviewreq.php'; echo "</div>";
      echo '<div id="page9"class="userpage">'; include 'user_pages/newbook.php'; echo "</div>";
      echo '<div id="page10"class="userpage">'; include 'user_pages/findusers.php'; echo "</div>";
      echo '<div id="page11"class="userpage">'; include 'user_pages/handlerstats.php'; echo "</div>";
    }
    if($is_my_profile || $is_admin) {
      echo '<div id="page12"class="userpage">'; include 'user_pages/backuprestore.php'; echo "</div>";
      echo '<div id="page13"class="userpage">'; include 'user_pages/handlerreq.php'; echo "</div>";
      echo '<div id="page14"class="userpage">'; include 'user_pages/adminstats.php'; echo "</div>";
    }
    ?>
	</div>
</main>


<script type="text/javascript">
	function selectThisBtn(d,pageindex){
		if(document.getElementById('selected-left')) document.getElementById('selected-left').id="";
		d.id="selected-left";
		for(const i of document.getElementsByClassName('userpage')) i.style.display = 'none';
		document.getElementById('page'+(pageindex).toString()).style.display = 'block';
	}
</script>
<script>
  const btns = document.getElementsByTagName('main')[0].getElementsByClassName('area-left')[0].getElementsByTagName('button');
  btns[3].click();
</script>


</body></html>
