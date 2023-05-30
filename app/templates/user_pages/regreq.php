<p>Registration Requests</p>
<center>
<?php

if(isset($_POST['submit_reg_accept'])){
  echo "here";
  $usernamereq = $_POST['usernamereq'];

  $query = "update user set user_verified = '1' where username = '".$usernamereq."'";
  #echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>accepted registration</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_reg_decline'])){
  $usernamereq = $_POST['usernamereq'];

  $query = "delete from user where username = '".$usernamereq."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>declined registration</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
$query = "SELECT user.username, user.email, user.name, school.name, city, CASE WHEN teacher.username IS NOT NULL THEN 'teacher' ELSE 'student' END AS 'register as' FROM user JOIN school ON user.sch_id = school.id LEFT JOIN teacher ON user.username = teacher.username WHERE user_verified <> '1' and sch_id = '".$sch_id."'";
$result = mysqli_query($conn, $query);
$output = '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Email</th><th>Name</th><th>School</th><th>Reg. As</th><th>Action</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  $output .= '<tr>';
  $output .= '<td><a href="/user/'.$tr[0].'">' . $tr[0] . '</a></td>';
  $output .= '<td>' . $tr[1] . '</td>';
  $output .= '<td>' . $tr[2] . '</td>';
  $output .= '<td>' . $tr[3] . ' of ' . $tr[4] . '</td>';
  $output .= '<td>' . $tr[5] . '</td><td>';
  $output .= '<form action=""method="post">
  <button class="button" name="submit_reg_accept" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">accept</span>
    </span>
  </button>
  <button class="button" name="submit_reg_decline" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">decline</span>
      </span>
    </button>
    <input type="text" value="'.$tr[0].'" name="usernamereq" style="display:none;"/>
  </form></td>';
  $output .= '</tr>';
}
$output .= '</tbody></table>';
echo $output;


?>

</center>
