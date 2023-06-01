<p>User Reviews</p>
<center>
<?php

if(isset($_POST['submit_rating_verify'])){
  $form_isbn = $_POST['isbn'];

  $query = "update ratings set rating_verified = 1 where username = '".$page_username."' and isbn = '".$form_isbn."'";
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>verified rating!</label>";
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}
if(isset($_POST['submit_rating_remove'])){
  $form_isbn = $_POST['isbn'];

  $query = "delete from ratings where username = '".$page_username."' and isbn = '".$form_isbn."'";
  #echo $query;
  $result = mysqli_query($conn, $query);
  if($result){
    echo "<label class='feedback green'>removed rating!</label>";
  }
  else{
    echo "<label class='feedback red'>[database error] failed, try again.</label>";
  }
}

getReviewResults("iuwerrugbesyoube", $conn, $is_admin, $is_valid_handler, $is_my_profile, $page_username);

function getReviewResults($divID, $conn, $is_admin, $is_valid_handler, $is_my_profile, $page_username){
  #reset the output area
  #echo "<script>if(document.getElementByID('".$divID."'))document.getElementByID('".$divID."').innerHTML='';</script>";

  $query = "select date, ratings.isbn, title, rating_verified from ratings join book on ratings.isbn = book.isbn where username = '".$page_username."' order by date desc";
  $result = mysqli_query($conn, $query);
  $output = '<div id="'.$divID.'"><table class="custom-table"><tr><thead><tr><th>Date</th><th>Book Title</th><th>Verified</th><th>Action</th></tr></thead><tbody>';
  while($tr = mysqli_fetch_row($result)){
    $output .= '<tr>';
    $output .= '<td>' . $tr[0] . '</td>';
    $output .= '<td>' . $tr[2] . '</td>';
    $output .= '<td>' . $tr[3] . '</td><td>';
    if(($is_valid_handler)){
      if($tr[3] != 1){
        $output .= '<form action=""method="post">
          <button class="button" name="submit_rating_verify" type="submit">
            <span class="button_lg">
              <span class="button_sl"></span>
              <span class="button_text">verify</span>
            </span>
          </button>
          <button class="button" name="submit_rating_remove" type="submit">
            <span class="button_lg">
              <span class="button_sl"></span>
              <span class="button_text">remove</span>
            </span>
          </button>
          <input type="text" value="'.$tr[1].'" name="isbn" style="display:none;"/>
        </form>';
      }
      else{
        $output .= '<form action=""method="post">
          <button class="button" name="submit_rating_remove" type="submit">
            <span class="button_lg">
              <span class="button_sl"></span>
              <span class="button_text">remove</span>
            </span>
          </button>
          <input type="text" value="'.$tr[1].'" name="isbn" style="display:none;"/>
        </form>';
      }

    }
    if($is_my_profile){
      $output .= '<a href="/book/'.$tr[1].'">manage</a>';
    }
    $output .= '</td></tr>';
  }
  $output .= '</tbody></table></div>';
  echo $output;
}




?>

</center>
