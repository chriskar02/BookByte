<?php

#return [$is_teacher, $is_verified_handler, $is_valid_handler, $is_admin]
function getStatus($conn, $this_username, $page_username=""){
	#find if is teacher
  $query = "select * from teacher where username = '".$this_username."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) > 0){
		echo "<script>document.getElementById('tag-teacher').style.display='inline-block';</script>";
		return [1,0];
  }
  else{
		return [0,0];
  }
}

?>
