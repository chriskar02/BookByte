<p>Registration Requests</p>
<center>
<?php
/*
$query = "select username, password, , title, name, city, book.isbn, sch_id from reservation as r join book on r.isbn = book.isbn join school on school.id = r.sch_id where username = '".$page_username."' order by r.date desc";
$result = mysqli_query($conn, $query);
$output = '<table class="custom-table"><tr><thead><tr><th>Date</th><th>Book Title</th><th>Borrowed From</th><th>Action</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  $output .= '<tr>';
  $output .= '<td>' . $tr[0] . '</td>';
  $output .= '<td>' . $tr[1] . '</td>';
  $output .= '<td>' . $tr[2] . ' of ' . $tr[3] . '</td><td>';
  if($rem_loans > 0){
    $output .= '<form action=""method="post">
      <button class="button" name="submit_borrow_from_rsv" type="submit">
        <span class="button_lg">
          <span class="button_sl"></span>
          <span class="button_text">borrow (and verify)</span>
        </span>
      </button>
      <input type="text" value="'.$tr[4].'" name="isbn" style="display:none;"/>
      <input type="text" value="'.$tr[5].'" name="sch-id" style="display:none;"/>
    </form>';
  }
  $output .= '<form action=""method="post">
    <button class="button" name="submit_cancel_rsv" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">cancel</span>
      </span>
    </button>
    <input type="text" value="'.$tr[4].'" name="isbn" style="display:none;"/>
  </form></td>';
  $output .= '</tr>';
}
$output .= '</tbody></table>';
echo $output;

if(isset($_POST['submit_borrow_from_rsv'])){
  $form_isbn = $_POST['isbn'];
  $form_schid = $_POST['sch-id'];
  $form_handla = $username;

  $query = "insert into loan (username, isbn, handler_username, sch_id, in_out, transaction_verified) values ('".$page_username."', '".$form_isbn."', '".$form_handla."', '".$form_schid."', 'borrowed', '1')";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>submitted loan!</label>";
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }

  $query = "delete from reservation where username = '".$page_username."' and isbn = '".$form_isbn."'";
  #echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>deleted reservation!</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_cancel_rsv'])){
  $form_isbn = $_POST['isbn'];

  $query = "delete from reservation where username = '".$page_username."' and isbn = '".$form_isbn."'";
  #echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>canceled!</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}*/
?>

</center>
