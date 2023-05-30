<p>Add a new school.</p>
<center>
<?php


if(isset($_POST['submit_up_sch'])){
  $f1 = $_POST['name'];
  $f2 = $_POST['email'];
  $f3 = $_POST['addr'];
  $f4 = $_POST['city'];
  $f5 = $_POST['pname'];
  $f6 = $_POST['phone'];

  $query = "insert into school (name, email, address, city, principal_name, phone) values ('".$f1."','".$f2."','".$f3."','".$f4."','".$f5."','".$f6."')";
  ;
  if($result = mysqli_query($conn, $query)){
    echo "<p class='feedback green'>added!</p>";
  }
  else{
    echo "<p class='feedback red'>failed: make sure inputs are correct.</p>";
  }
}
?>

<form action="" method="post" enctype="multipart/form-data" style="max-width: 700px;">
  <div class="form__group field">
    <input type="input" name="name" class="form__field"  required maxlength="60">
    <label for="username" class="form__label">Name</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="email" class="form__field" required maxlength="60">
    <label for="username" class="form__label">Email</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="addr" class="form__field"  required maxlength="60">
    <label for="username" class="form__label">Address</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="text" name="city" class="form__field"  required maxlength="30">
    <label for="username" class="form__label">City</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="pname" class="form__field"  required maxlength="50">
    <label for="username" class="form__label">Principal's Name</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="number" name="phone" class="form__field"  required min="999999999" max="9999999999">
    <label for="username" class="form__label">Phone Number</label>
  </div>
  <br class="half-br">
  <br class="half-br">
  <button class="button" name="submit_up_sch" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">submit</span>
      </span>
    </button>
</form>







</center>
