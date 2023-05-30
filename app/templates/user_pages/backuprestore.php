<form action="" method="post">

<button class="button" name="submit_createbu" type="submit">
  <span class="button_lg">
    <span class="button_sl"></span>
    <span class="button_text">CREATE A BACKUP</span>
  </span>
</button>

</form>

<?php
if(isset($_POST['submit_createbu'])){
  #echo "mysqldump --user=root --password='1234' BookByte > backup_".date('Ymd_His').".sql";
  $backupFile = "backup_".date('Ymd_His').".sql";

  exec("mysqldump --user=root --password='1234' BookByte > ".$backupFile);


  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
  header('Content-Length: ' . filesize($backupFile));

  echo '<a href="'.$backupFile.'">Download Backup</a>';
}

?>

<hr class="custom-hr">
restore from file:
<center>

<form action="" method="post" enctype="multipart/form-data" style="max-width: 700px;">
  <div class="form__group field">
    <input type="file" name="file1" id="fileinp" class="form__field" accept=".sql" required>
    <label for="file" class="form__label">Upload file (.sql)</label>
  </div>
  <button class="button" name="submit_restore" type="submit">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">RESTORE</span>
    </span>
  </button>
</form>



<?php

function restoreMysqlDB($filePath, $conn){
  $sql = '';
  $error = '';
  if (file_exists($filePath)){
    $lines = file($filePath);
    foreach ($lines as $line) {
      if (substr($line, 0, 2) == '--' || $line == '') {
        continue;
      }
      $sql .= $line;
      if(substr(trim($line), - 1, 1) == ';'){
        $result = mysqli_query($conn, $sql);
        if (! $result) {
          $error .= mysqli_error($conn) . "\n";
        }
        $sql = '';
      }
    }
    if ($error) {
      echo "<p class='feedback red'>Error: ".$error.".</p>";
    }
    else{
      echo "<p class='feedback green'>Successfully restored!</p>";
    }
    exec('rm ' . $filePath);
  }
}

if(isset($_POST['submit_restore'])){

  if (!empty($_FILES)) {
    if(!in_array(strtolower(pathinfo($_FILES["file1"]["name"], PATHINFO_EXTENSION)), array("sql"))){
      echo "<p class='feedback red'>Error.</p>";
    }
    else{
      #echo var_dump($_FILES["file1"]["tmp_name"]);
      if(is_uploaded_file($_FILES["file1"]["tmp_name"])){
        move_uploaded_file($_FILES["file1"]["tmp_name"], $_FILES["file1"]["name"]);
        restoreMysqlDB($_FILES["file1"]["name"], $conn);
      }
      else {
        echo "<p class='feedback red'>Error: file is not uploaded: maybe fileszise is too big.</p>";
      }
    }
  }
  else{
    echo "<p class='feedback red'>Error: empty _FILES.</p>";
  }

}

?>

</center>
