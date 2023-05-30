<center>
<?php

if(!$is_teacher){
  echo "Only teachers can become handlers.";
}
else if($is_verified_handler){
  echo "You are already a verified handler.";
}
else if($is_req_handler){
  echo "You have are already requested to become a handler.";
  echo '<form action=""method="post">
  <button class="button" name="submit_cancel_req_handler" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">cancel request</span>
    </span>
  </button>
  </form>';
}
else{
  echo "Request to become a handler";
  echo '<form action=""method="post">
  <button class="button" name="submit_become_handler" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">become a handler</span>
    </span>
  </button>
  </form>';
}

if(isset($_POST['submit_become_handler'])){
  $query = "update teacher set handler_request = '1' where username = '".$username."'";
  #echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>request sent</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_cancel_req_handler'])){
  $query = "update teacher set handler_request = '0' where username = '".$username."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>canceled request registration</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}

?>

</center>
