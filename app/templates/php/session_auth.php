<?php

/* checks if username has session_token that matches in db */
/* also checks if user if verified (by handler). If not, the screen says: not verified */
function getAuth($conn){

	if (!isset($_COOKIE['username']) || !isset($_COOKIE['session_token'])) {
		#if client does not have a cookie, return false
		return false;
	}
	$username = $_COOKIE['username'];
	$sessionToken = $_COOKIE['session_token'];

	$query = "select token, username from session_tokens where username = '" . $username ."';";
	$result = mysqli_query($conn, $query);
	if(!$result){
		#if username in db does not have any token, return false
		return false;
	}

	while($tr = mysqli_fetch_row($result)){
		if($tr[0] == $sessionToken){
			#if cookie token matches the one in db, return true

			#check if verified
			$query = "select user_verified from user where username = '" . $username ."';";
			$result = mysqli_query($conn, $query);
			if(!$result){
				return false;
			}
			$tr = mysqli_fetch_row($result);
			if($tr[0] != 1){
				echo "<div class='fullscreen'>Your account has not been verified yet!<br>Wait for your handler to verify you.<br><br>
				<form  action=''method='POST'>
				<button class='button'name='submit_logout'type='submit'>
					<span class='button_lg'>
						<span class='button_sl'></span>
						<span class='button_text'>LOGOUT</span>
					</span>
				</button>
			  </form>
				</div>";
			}
			return true;
		}
	}
	return false;
}

?>
