<p>User Loans</p>
<center>
<?php

if(isset($_POST['submit_cancel_borrow1'])){
  $form_date = $_POST['date'];
  $form_isbn = $_POST['isbn'];
  $form_borrower = $page_username;

  $query = "delete from loan where username = '".$form_borrower."' and isbn = '".$form_isbn."' and loan.date = '".$form_date."' and in_out = 'borrowed'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>verified!</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_verify'])){
  $form_date = $_POST['date'];
  $form_isbn = $_POST['isbn'];
  $form_handler = $username;
  $form_borrower = $page_username;

  $query = "update loan set transaction_verified = 1, handler_username = '".$form_handler."' where username = '".$form_borrower."' and isbn = '".$form_isbn."' and loan.date = '".$form_date."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>verified!</label>";
    echo "<script>window.location.href += '';</script>";
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_return'])){
  $form_date = $_POST['date'];
  $form_isbn = $_POST['isbn'];
  $form_schid = $_POST['sch_id'];
  $form_handler = $username;
  $form_borrower = $page_username;

  $query = "update loan set transaction_verified = 2 where username = '".$form_borrower."' and isbn = '".$form_isbn."' and loan.date = '".$form_date."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>successfully upadted last transaction.</label>";
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }

  $query = "insert into loan (username, isbn, handler_username, sch_id, in_out, transaction_verified) values ('".$form_borrower."', '".$form_isbn."', '".$form_handler."', '".$form_schid."', 'returned', '1')";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>successfully returned!</label>";

    echo "<script>window.location.href += '';</script>";

  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}

$query = "select date, title, name, city, in_out, transaction_verified, book.isbn, sch_id from loan join book on loan.isbn = book.isbn join school on school.id = loan.sch_id where username = '".$page_username."' order by loan.date desc";
$result = mysqli_query($conn, $query);
$output = '<table class="custom-table"><tr><thead><tr><th>Date</th><th>Book Title</th><th>Borrowed From</th><th>Action</th><th>Verified</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  $output .= '<tr>';
  $output .= '<td>' . $tr[0] . '</td>';
  $output .= '<td>' . $tr[1] . '</td>';
  $output .= '<td>' . $tr[2] . ' of ' . $tr[3] . '</td>';
  $output .= '<td>' . $tr[4] . '</td>';
  if($tr[5] == 0 && $tr[4] == 'borrowed'){
    if($is_valid_handler || $is_admin){
      $output .= '<td><form action=""method="post">
        <button class="button" name="submit_verify" type="submit">
          <span class="button_lg">
            <span class="button_sl"></span>
            <span class="button_text">verify</span>
          </span>
        </button>
        <button class="button" name="submit_cancel_borrow1" type="submit">
          <span class="button_lg">
            <span class="button_sl"></span>
            <span class="button_text">cancel</span>
          </span>
        </button>
        <input type="text" value="'.$tr[0].'" name="date" style="display:none;"/>
        <input type="text" value="'.$tr[6].'" name="isbn" style="display:none;"/>
      </form></td>';
    }
    else{
      $output .= '<td><form action=""method="post">
      <button class="button" name="submit_cancel_borrow1" type="submit">
        <span class="button_lg">
          <span class="button_sl"></span>
          <span class="button_text">cancel</span>
        </span>
      </button>
        <input type="text" value="'.$tr[0].'" name="date" style="display:none;"/>
        <input type="text" value="'.$tr[6].'" name="isbn" style="display:none;"/>
      </form></td>';
    }
  }
  else if($tr[5] == 1 && $tr[4] == 'borrowed'){
    if($is_valid_handler || $is_admin){
      $output .= '<td><form action=""method="post">
        <button class="button" name="submit_return" type="submit">
          <span class="button_lg">
            <span class="button_sl"></span>
            <span class="button_text">return</span>
          </span>
        </button>
        <input type="text" value="'.$tr[0].'" name="date" style="display:none;"/>
        <input type="text" value="'.$tr[6].'" name="isbn" style="display:none;"/>
        <input type="text" value="'.$tr[7].'" name="sch_id" style="display:none;"/>
      </form></td>';
    }
    else{
      $output .= "<td>verified</td>";
    }
  }
  else{
    $output .= "<td>completed</td>";
  }
  $output .= '</tr>';
}
$output .= '</tbody></table>';
echo $output;


?>

</center>
