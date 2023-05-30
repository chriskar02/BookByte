<center>
<?php

if($is_teacher){
  echo "You are already a teacher.";
}
else{
  echo "Request to become a teacher";
  echo '<form action=""method="post">
  birth date: <input type="date" id="date" name="bdate" reqired="">
  <button class="button" name="submit_become_teacher" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">become a teacher</span>
    </span>
  </button>
  </form>';
}

if(isset($_POST['submit_become_teacher'])){
  $bdate = $_POST['bdate'];
  $query = "update teacher set handler_request = '1' where username = '".$username."'";
  /*#echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>request sent</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }*/
}

?>

</center>
