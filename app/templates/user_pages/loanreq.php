<p>Loan Requests</p>
<center>
<?php

if(isset($_POST['submit_loan_req_accept'])){
  $fusername = $_POST['fusername'];
  $fdate = $_POST['date'];
  $fisbn = $_POST['fisbn'];
  $query = "update loan set transaction_verified = '1' where username = '".$fusername."' and isbn = '".$fisbn."' and date = '".$fdate."' and in_out = 'borrowed'";

  echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>accepted loan request</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_loan_req_decline'])){
  $fusername = $_POST['fusername'];
  $fdate = $_POST['date'];
  $fisbn = $_POST['fisbn'];
  $query = "delete from loan where username = '".$fusername."' and isbn = '".$fisbn."' and date = '".$fdate."' and in_out = 'borrowed'";

  echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>declined loan request</label>";
    echo '<script>window.location.href = window.location.href;</script>';
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_loan_req_return'])){
  $fusername = $_POST['fusername'];
  $fdate = $_POST['date'];
  $fisbn = $_POST['fisbn'];
  $query = "update loan set transaction_verified = '2' where username = '".$fusername."' and isbn = '".$fisbn."' and date = '".$fdate."' and in_out = 'borrowed'";

  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>successfully closed borrow transaction </label>";
    $query = "insert into loan (transaction_verified, username, isbn, handler_username, in_out, sch_id) values ('2', '".$fusername."', '".$fisbn."', '".$page_username."', 'returned', '".$sch_id."')";
    $result = mysqli_query($conn, $query);
    if($result){
      echo "<label class='feedback green'>returned loan</label>";
      #echo '<script>window.location.href = window.location.href;</script>';
    }
    else{
      echo "<label class='feedback red'>[database error] failed, try again.</label>";
    }
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}






$query = "SELECT loan.date, transaction_verified, loan.username, loan.isbn, school.name, city, in_out FROM loan JOIN school ON loan.sch_id = school.id join user on user.username = loan.username WHERE transaction_verified <> '2' and in_out <> 'returned' and user.sch_id = '".$sch_id."'";
$result = mysqli_query($conn, $query);
$output = '<table class="custom-table"><tr><thead><tr><th>Date</th><th>User</th><th>Borrow From</th><th>ISBN</th><th>Action</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  $output .= '<tr>';
  $output .= '<td>' . $tr[0] . '</td>';
  $output .= '<td>' . $tr[2] . '</td>';
  $output .= '<td>' . $tr[4] . ' of ' . $tr[5] . '</td>';
  $output .= '<td><a href="/book/'.$tr[3].'">' . $tr[3] . '</a></td><td>';
  if($is_admin || $is_valid_handler){
    $output .= '<form action=""method="post">';
    if($tr[1] == 0){
      $output .= '<button class="button" name="submit_loan_req_accept" type="submit">
        <span class="button_lg">
          <span class="button_sl"></span>
          <span class="button_text">accept borrow</span>
        </span>
      </button>
      <button class="button" name="submit_loan_req_decline" type="submit">
          <span class="button_lg">
            <span class="button_sl"></span>
            <span class="button_text">decline borrow</span>
          </span>
        </button>';
    }
    else{
      $output .= '<button class="button" name="submit_loan_req_return" type="submit">
          <span class="button_lg">
            <span class="button_sl"></span>
            <span class="button_text">return</span>
          </span>
        </button>';
    }
    $output .= '<input type="text" value="'.$tr[0].'" name="date" style="display:none;"/>
    <input type="text" value="'.$tr[3].'" name="fisbn" style="display:none;"/>
    <input type="text" value="'.$tr[2].'" name="fusername" style="display:none;"/>
    </form></td></tr>';
  }
}
$output .= '</tbody></table>';
echo $output;


?>

</center>
