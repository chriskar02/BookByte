<p>Reviews from users in the same school as yours</p>
<center>
<?php

if(isset($_POST['submit_rev_pub'])){
  $fusername = $_POST['fusername'];
  $fisbn = $_POST['fisbn'];
  $query = "update ratings set rating_verified = '1' where username = '".$fusername."' and isbn = '".$fisbn."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>published!</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_rev_del'])){
  $fusername = $_POST['fusername'];
  $fisbn = $_POST['fisbn'];
  $query = "delete from ratings where username = '".$fusername."' and isbn = '".$fisbn."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>deleted</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}






$query = "SELECT date, user.username, isbn, description, stars FROM ratings join user on user.username = ratings.username WHERE rating_verified <> '1' and user.sch_id = '".$sch_id."'";
#echo $query;
$result = mysqli_query($conn, $query);
$output = '<table class="custom-table"><tr><thead><tr><th>Date</th><th>User</th><th>ISBN</th><th>Description</th><th>stars</th><th>Action</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  $output .= '<tr>';
  $output .= '<td>' . $tr[0] . '</td>';
  $output .= '<td>' . $tr[1] . '</td>';
  $output .= '<td><a href="/book/'.$tr[2].'">' . $tr[2] . '</a></td>';
  $output .= '<td>' . $tr[3] . '</td>';
  $output .= '<td>' . $tr[4] . '</td>';
  $output .= '<td>';
  $output .= '<form action=""method="post">';
  $output .= '<button class="button" name="submit_rev_pub" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">publish</span>
    </span>
  </button>
  <button class="button" name="submit_rev_del" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">delete</span>
      </span>
    </button>';
  $output .= '
  <input type="text" value="'.$tr[2].'" name="fisbn" style="display:none;"/>
  <input type="text" value="'.$tr[1].'" name="fusername" style="display:none;"/>
  </form></td></tr>';
}
$output .= '</tbody></table>';
echo $output;


?>

</center>
