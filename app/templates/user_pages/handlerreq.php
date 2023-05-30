<p>Handler Requests</p>
<center>
<?php

if(isset($_POST['submit_acc_hand_req'])){
  $usrnm = $_POST['username'];
  $query = "update teacher set handler_verified = 1 where username = '".$usrnm."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>verified!</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_decl_hand_req'])){
  $usrnm = $_POST['username'];
  $query = "update teacher set handler_request = 0 where username = '".$usrnm."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>declined!</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}

$query = "SELECT username from teacher where handler_request = 1 and handler_verified <> 1 order by username";
$result = mysqli_query($conn, $query);
$output = '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Action</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  $output .= '<tr>';
  $output .= '<td><a href="/user/'.$tr[0].'">' . $tr[0] . '</a></td>';
  $output .= '<td><form action=""method="post">
    <button class="button" name="submit_acc_hand_req" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">accept</span>
      </span>
    </button>
    <button class="button" name="submit_decl_hand_req" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">decline</span>
      </span>
    </button>
    <input type="text" value="'.$tr[0].'" name="username" style="display:none;"/>
  </form></td>';
  $output .= '</tr>';
}
$output .= '</tbody></table>';
echo $output;
?>
</center>
