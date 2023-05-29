<details id="search-det" open>
  <summary>search bar</summary>
<form class="form__group field srchinp"action=""method="POST" style="max-width:500px;margin:auto;">
    <input type="text" class="form__field"name="user-search-terms"placeholder="Search terms">
    <label for="name" class="form__label">Serach for username, full name, email,...</label>
    <br>
    <br>
    <button class="button" name="submit_user_search_by_terms" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">SEARCH by terms</span>
      </span>
    </button>
</form>
<br class="half-br">
<hr class="custom-hr">
<form action="" method="post">

<br>
<div class="dropdown"action=""method="POST">
  <select class="dropdown-select smaller"name="user-category-select-user-search">
    <option value="any" selected>any</option>
    <option value="student">student</option>
    <option value="teacher">teacher</option>
    <option value="handler">handler</option>
    <option value="admin">admin</option>
  </select>
</div>
<div class="dropdown"action=""method="POST">
  <select class="dropdown-select smaller"name="sch-select-user-search">
    <option value="any" selected>any</option>
    <?php

    $query = "select name, id, city from school";
    $result = mysqli_query($conn, $query);
    while($tr = mysqli_fetch_row($result)){
      echo '<option value="'.$tr[1].'">'. $tr[0] .' of '.$tr[2].'</option>';
    }

    ?>
  </select>
</div>
<br>
<br>
<button class="button" name="submit_search_by_filters" type="submit">
  <span class="button_lg">
    <span class="button_sl"></span>
    <span class="button_text">SEARCH by filters</span>
  </span>
</button>
</form>
</details>

<?php

if(isset($_POST['submit_user_search_by_terms'])){
  $terms = $_POST['user-search-terms'];
  $query1 = "select username from user where username like '%".$terms."%'";
  $query2 = "select username from user where email like '%".$terms."%'";
  $query3 = "select username from user where name like '%".$terms."%'";
  $query = "select merged.username from ((".$query1.") UNION (".$query2.") UNION (".$query3.")) as merged";

  $result = mysqli_query($conn, $query);

  while($tr = mysqli_fetch_row($result)){
    echo "<p><a class='user-link'href='/user/".$tr[0]."'>".$tr[0]."</a></p>";
  }

  echo "<script>document.getElementById('search-det').open = false;</script>";
}
if(isset($_POST['submit_search_by_filters'])){
  $user_select = $_POST['user-category-select-user-search'];
  $sch_select = $_POST['sch-select-user-search'];

  if($user_select == 'student'){
    echo "showing only students (not teachers or admins)<br>";
    $query1 = "select username from user where username not in (select username from teacher) and username not in (select username from admin)";
    if($sch_select != 'any'){$query1 .= " and sch_id = '".$sch_select."'";}
  }
  else if($user_select == 'teacher'){
    $query1 = "select teacher.username from teacher join user on teacher.username = user.username";
    if($sch_select != 'any'){$query1 .= " where sch_id = '".$sch_select."'";}
  }
  else if($user_select == 'handler'){
    $query1 = "select verified_handler.username from verified_handler join user on verified_handler.username = user.username";
    if($sch_select != 'any'){$query1 .= " where sch_id = '".$sch_select."'";}
  }
  else if($user_select == 'admin'){
    $query1 = "select admin.username from admin join user on admin.username = user.username";
    if($sch_select != 'any'){$query1 .= " where sch_id = '".$sch_select."'";}
  }
  else{
    $query1 = "select username from user";
    if($sch_select != 'any'){$query1 .= " where sch_id = '".$sch_select."'";}
  }
  echo "<div style='font-size: 0.8em;'>DEBUG: user: ".$user_select.", sch_id: ".$sch_select."</div>";

  $query = $query1 . ' ' . $query2 . ' order by username';
  $result = mysqli_query($conn, $query);

  while($tr = mysqli_fetch_row($result)){
    echo "<p><a class='user-link'href='/user/".$tr[0]."'>".$tr[0]."</a></p>";
  }

  echo "<script>document.getElementById('search-det').open = false;</script>";
}

?>
