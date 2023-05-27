<?php
/* button for logout must have name attribute = 'submit_logout' */
function logout_option($conn){

	if(isset($_POST['submit_logout'])){
		$username = $_COOKIE['username'];
		$session_token = $_COOKIE['session_token'];
		unset($_COOKIE['username']);
		unset($_COOKIE['session_token']);
		$query = "delete from session_tokens where username = '" . $username . "' and token = '" . $session_token . "'";
		$result = mysqli_query($conn, $query);
		if($result){
			#logout
			header("Location: login");
			exit;
		}
		else{
			echo "<div class='feedback red'>logout failed, try again.</div>";
		}

	}
}

/* button for profile must have name attribute = 'submit_profile' */
function profile_option(){
	if(isset($_POST['submit_profile'])){
		$username = $_COOKIE['username'];
		header("Location: user");
		exit;
	}
}

/* button for book must have name attribute = 'submit_book' and title element name attr = 'book-title' */
function book_option(){
	if(isset($_POST['submit_book'])){
		$title = $_POST['book-title'];
		header("Location: book");
		exit;
	}
}
?>
