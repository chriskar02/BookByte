<p>total number of loans per school (verifed borrows) by date. (πόσες φορές δανείστηκαν από την βιβλιοθήκη του κάθε σχολείου)</p>
<center>
<form action="" method="post" style="max-width:700px;">

  <div class="form__group field">
    <input type="number" name="yyyy" class="form__field"  required maxlength="4">
    <label for="username" class="form__label">Year</label>
  </div>
select month
  <div class="dropdown">
    <select class="dropdown-select"name="mm-select" required="">
      <option value="01">JAN</option>
      <option value="02">FEB</option>
      <option value="03">MAR</option>
      <option value="04">APR</option>
      <option value="05">MAY</option>
      <option value="06">JUN</option>
      <option value="07">JUL</option>
      <option value="08">AUG</option>
      <option value="09">SEP</option>
      <option value="10">OCT</option>
      <option value="11">NOV</option>
      <option value="12">DEC</option>
    </select>
  </div>
  <br>
<br>
  <button class="button"type="submit"name="submit_find_tot_loans_p_sch">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">Find</span>
    </span>
  </button>

</form>

<?php

if(isset($_POST['submit_find_tot_loans_p_sch'])){
  $yyyy = $_POST['yyyy'];
  $mm = $_POST['mm-select'];
  if($yyyy < 1900 || $yyyy > date('Y')){
    echo "<p class='feedback red'>Invalid year.</p>";
  }
  else{
    $query = "SELECT school.name, school.city, count(loan.sch_id) as total from school join loan on id = sch_id where in_out = 'borrowed' and date like '".$yyyy."-".$mm."%' group by sch_id order by total desc;";
    $result = mysqli_query($conn, $query);
    echo '<table class="custom-table"><tr><thead><tr><th>School</th><th>Number of Loans</th></tr></thead><tbody>';
    while($tr = mysqli_fetch_row($result)){
      echo '<tr><td>' . $tr[0] . ' of '.$tr[1].'</td><td>' . $tr[2] . '</td></form></td></tr>';
    }
    echo '</tbody></table>';
  }
}
?>

</center>
