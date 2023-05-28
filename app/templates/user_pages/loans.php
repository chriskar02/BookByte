<p>User Loans</p>
<?php


$query = "select username, isbn, date, name, in_out, transaction_verified from loan natural join school where username = '".$page_username."'";
$result = mysqli_query($conn, $query);
$output = '<table class="custom-table"><tr><thead><tr>';
$cols = ['Book', 'Date', 'Action'];
foreach ($cols as $value) {
  $output .= '<th>' . $value . '</th>';
}
$output .= '</tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  $output .= '<tr>';
  foreach ($tr as $value) {
    $output .= '<td>' . $value . '</td>';
  }
  $output .= '</tr>';
}
$output .= '</tbody></table>';
return $output;


$result = mysqli_query($conn, $query);
if($result){
  echo "<label class='feedback green'>changes saved! Refreshing page </label>";
  echo '<script>window.location.href = window.location.href;</script>';
}
else{
  echo "<label class='feedback red'>[database error] make sure the email is not taken (unique).</label>";
}



?>




<form class="details-card"action=""method="post">
  <div class="details-control">

    <button class="save-btn icon-btn"name="submit-new-details"type="submit"></button>
    <label id="editbtn"class="edit-btn icon-btn"onclick="editDetails()"></label>
    <label id="cancelbtn"class="cancel-btn icon-btn"style="display:none;"onclick="cancelEdit()"></label>
    <script>
      function cancelEdit(){
        document.getElementById('cancelbtn').style.display='none';
        document.getElementById('editbtn').style.display='inline-block';
        for(const i of document.getElementsByClassName('details-right-input')) i.style.display='none';
        for(const i of document.getElementsByClassName('details-right-label')) i.style.display='inline-block';
      }
      function editDetails(){
        document.getElementById('cancelbtn').style.display='inline-block';
        document.getElementById('editbtn').style.display='none';
        for(const i of document.getElementsByClassName('details-right-label')) i.style.display='none';
        for(const i of document.getElementsByClassName('details-right-input')) i.style.display='inline-block';
      }
    </script>
  </div>
  <div class="details-card-row">
    <div class="details-card-left">Username</div>
    <div class="details-card-right"><?php echo $page_username; ?></div>
  </div>
  <hr class="details-card-hr">
  <div class="details-card-row">
    <div class="details-card-left">Password</div>
    <div class="details-card-right">
      <div class="details-right-label"><?php echo $password; ?></div>
      <div class="details-right-input"><input type="text"name="password"value="<?php echo $password; ?>" required=""></div>
    </div>
  </div>
  <hr class="details-card-hr">
  <div class="details-card-row">
    <div class="details-card-left">Full Name</div>
    <div class="details-card-right">
      <div class="details-right-label"><?php echo $name; ?></div>
      <div class="details-right-input"><input type="text"name="name"value="<?php echo $name; ?>" required=""></div>
    </div>
  </div>
  <hr class="details-card-hr">
  <div class="details-card-row">
    <div class="details-card-left">Email</div>
    <div class="details-card-right">
      <div class="details-right-label"><?php echo $email; ?></div>
      <div class="details-right-input"><input type="text"name="email"value="<?php echo $email; ?>" required=""></div>
    </div>
  </div>
  <hr class="details-card-hr">
  <div class="details-card-row">
    <div class="details-card-left">Verified</div>
    <div class="details-card-right"><?php if($user_verified) echo "Yes"; else echo "No" ?></div>
  </div>
  <hr class="details-card-hr">
  <div class="details-card-row">
    <div class="details-card-left">School</div>
    <div class="details-card-right">
      <div class="details-right-label"><?php echo $sch_full_name; ?></div>
      <div class="details-right-input">
        <select class="dropdown-select"name="sch-id" required="">
  		    <option value="">select school</option>
          <?php

          $query = "select name, city, id from school";
          $result = mysqli_query($conn, $query);
          while($tr = mysqli_fetch_row($result)){
            echo '<option value="'.$tr[2].'">'. $tr[0] .' of '.$tr[1].'</option>';
          }

          ?>
  		  </select>
      </div>
    </div>
  </div>
</form>

<br>
<br>
<form action=""method="post">
  <button class="button" name="submit_clear_tokens" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">DELETE SESSION TOKENS</span>
    </span>
  </button>
  <?php
  if(isset($_POST['submit_clear_tokens'])){
    $sess_token = $_COOKIE['session_token'];
    $query = "delete from session_tokens where username = '".$page_username."' and token <> '".$sess_token."'";
    $result = mysqli_query($conn, $query);
    if($result){
      echo "<label class='feedback green'>tokens deleted!</label>";
    }
    else{
      echo "<label class='feedback red'>[database error] failed, try again.</label>";
    }
  }
  ?>
</form>
<br>
<form action=""method="post">
  <button class="button" name="submit_delete_account" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">DELETE ACCOUNT</span>
    </span>
  </button>
  <?php
  if(isset($_POST['submit_delete_account'])){
    $query = "delete from user where username = '".$page_username."'";
    $result = mysqli_query($conn, $query);
    if($result){
      echo "<label class='feedback green'>account deleted!</label>";
    }
    else{
      echo "<label class='feedback red'>[database error] failed, try again.</label>";
    }
  }
  ?>
</form>
