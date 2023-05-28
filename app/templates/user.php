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

  profile_option();
  book_option();
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
<link rel="stylesheet" type="text/css" href="../static/css/user.css">

</head><body>
	<?php
		include 'php/header.php';
	?>
<main>
	<div class="area-left">
		<button id="selected-left"class="btn-simple-blue"onclick="selectThisBtn(this,1);">Account</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,2);">Loans</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,3);">Reservations</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,4);">History</button>
		<p>HANDLER</p>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,5);">Resistration Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,6);">Loan Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,7);">Reservation Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,8);">Review Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,9);">Add New Book</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,10);">Find School Users</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,11);">Stats</button>
		<p>ADMIN</p>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,12);">Backup / Restore</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,13);">Handler Requests</button>
		<button class="btn-simple-blue"onclick="selectThisBtn(this,14);">Stats</button>
	</div>
	<div class="content-right">
		<div id="page1"class="userpage"><?php include 'user_pages/account.php'; ?></div>
		<div id="page2"class="userpage"><?php include 'user_pages/loans.php'; ?></div>
		<div id="page3"class="userpage"><?php include 'user_pages/reservations.php'; ?></div>
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



</body></html>
