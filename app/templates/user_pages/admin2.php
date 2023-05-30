<p>authors per category, teachers that borrowed from this category last year.</p>
<center>
<form action="" method="post" style="max-width:700px;">

<select class="dropdown-select smaller"name="submit_select_category_for_stats"onchange="this.form.submit()">
  <option value="default" disabled selected hidden>select category</option>
  <?php

  $query = "select distinct category from category";
  $result = mysqli_query($conn, $query);
  while($tr = mysqli_fetch_row($result)){
    echo '<option value="'.$tr[0].'">'. $tr[0] .'</option>';
  }

  ?>
</select>

</form>

<?php

if(isset($_POST['submit_select_category_for_stats'])){
  $category = $_POST['submit_select_category_for_stats'];
  if($category == 'default'){
    echo "<p class='feedback red'>You must select a category.</p>";
  }
  else{
    echo "<hr class='custom-hr'><p>authors in category ".$category.":</p><hr class='custom-hr'>";
    $query = "SELECT distinct name from author join category on category.isbn = author.isbn where category = '".$category."'";
    $result = mysqli_query($conn, $query);
    while($tr = mysqli_fetch_row($result)){
      echo $tr[0].", ";
    }

    echo "<hr class='custom-hr'><p>teachers that borrowed from category ".$category." last year:</p><hr class='custom-hr'>";
    $query = "SELECT distinct user.username from user join loan on user.username = loan.username join category on category.isbn = loan.isbn where loan.in_out = 'borrowed' and loan.transaction_verified <> 0 and category = '".$category."' and datediff(now(), date) < 365";
    $result = mysqli_query($conn, $query);
    while($tr = mysqli_fetch_row($result)){
      echo "<a href='/user/".$tr[0]."'>".$tr[0]."</a>, ";
    }
  }
}
?>

</center>
