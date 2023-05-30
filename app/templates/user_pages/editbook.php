<p>Edit a book.</p>
<p>note: ISBN cannot change, if you want to 'change' it add new book.</p>
<center>

<?php


if(isset($_POST['submit_update_book'])){
  $fisbn = $_POST['isbn'];
  $query = "select isbn from book where isbn = '".$fisbn."'";
  $result = mysqli_query($conn, $query);
  if(!mysqli_num_rows($result) > 0){
    echo "<p class='feedback red'>book with this isbn does not exist!</p>";
  }
  else{
    $title = $_POST['title'];
    $pub = $_POST['pub'];
    $pages = $_POST['pages'];
    $sum = $_POST['sum'];
    $lang = $_POST['lang'];
    $key = $_POST['key'];
    $copies = $_POST['copies'];
    $authors = $_POST['authors'];
    $categories = $_POST['categories'];

    $author_array = explode(",", $authors);
    $category_array = explode(",", $categories);

    $errorflag = 0;
    foreach ($author_array as $value) {
      if(strlen(trim($value)) > 50){
        $errorflag = 1;
        echo "<p class='feedback red'>Error: author name too long: ".trim($value)." (max length: 50)</p>";
        break;
      }
    }
    foreach ($category_array as $value) {
      if(strlen(trim($value)) > 30){
        $errorflag = 1;
        echo "<p class='feedback red'>Error: category name too long: ".trim($value)." (max length: 30)</p>";
        break;
      }
    }
    $fileContent = file_get_contents($_FILES['file']['tmp_name']);
    $base64Data = base64_encode($fileContent);
    $imgurlfull = "b'".$base64Data."'";
    if(strlen($imgurlfull)/1024 > 60){
        $errorflag = 1;
        echo "<p class='feedback red'>Error: image too big, max size: 60KB.</p>";
    }
    if(!$errorflag){

      #remove previous book, author and category
      $query = 'delete from book where isbn = '.$fisbn;
      if(!mysqli_query($conn, $query)){echo "<p class='feedback red'>FAILED: 1.</p>";}
      $query = 'delete from author where isbn = '.$fisbn;
      if(!mysqli_query($conn, $query)){echo "<p class='feedback red'>FAILED: 2.</p>";}
      $query = 'delete from category where isbn = '.$fisbn;
      if(!mysqli_query($conn, $query)){echo "<p class='feedback red'>FAILED: 3.</p>";}

      #add new
      $query = 'insert into book (title, publisher, isbn,	pages, summary, cover_image, language,	keywords) values ("'.$title.'", "'.$pub.'", "'.$fisbn.'", "'.$pages.'", "'.$sum.'", "'.$imgurlfull.'", "'.$lang.'", "'.$key.'")';
      #echo $query;

      $result = mysqli_query($conn, $query);
      if($result){
        echo "<p class='feedback green'>added book!</p>";
        foreach ($author_array as $value) {
          $val = str_replace(',', '', trim($value));
          $query = "insert into author (isbn, name) values ('".$fisbn."','".$val."')";
          $result = mysqli_query($conn, $query);
          if($result){
            echo "<p class='feedback green'>added author ".$val."</p>";
          }
          else{
            echo "<p class='feedback red'>[database error] author insert failed: ".$val."</p>";
          }
        }
        foreach ($category_array as $value) {
          $val = str_replace(',', '', trim($value));
          $query = "insert into category (isbn, category) values ('".$fisbn."','".$val."')";
          #echo $query;
          $result = mysqli_query($conn, $query);
          if($result){
            echo "<p class='feedback green'>added category ".$val."</p>";
          }
          else{
            echo "<p class='feedback red'>[database error] author insert failed: ".$val."</p>";
          }
        }
      }
      else{
        echo "<p class='feedback red'>[database error] possible errors: too many pages, too many copies, author name too long, category name too long.</p>";
      }
    }
    else{
      echo "<p class='feedback red'>Error: invalid input.</p>";
    }
  }
}
?>

<form action="" method="post" enctype="multipart/form-data" style="max-width: 700px;">
  <div class="form__group field">
    <input type="input" name="title" class="form__field"  required maxlength="80">
    <label for="username" class="form__label">Title</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="isbn" class="form__field" required maxlength="10">
    <label for="username" class="form__label">ISBN 10</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="pub" class="form__field"  required maxlength="40">
    <label for="username" class="form__label">Publisher</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="number" name="pages" class="form__field"  required max="30000">
    <label for="username" class="form__label">Pages</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="sum" class="form__field"  required>
    <label for="username" class="form__label">Summary</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="file" name="file" class="form__field"  required>
    <label for="file" class="form__label">Cover Image</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="lang" class="form__field"  required maxlength="15">
    <label for="username" class="form__label">Language</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="key" class="form__field"  required>
    <label for="username" class="form__label">Keywords</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="number" name="copies" class="form__field"  required>
    <label for="username" class="form__label">Number of copies</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="authors" class="form__field"  required>
    <label for="username" class="form__label">Authors (comma separated, max length per author: 50)</label>
  </div>
  <br class="half-br">
  <div class="form__group field">
    <input type="input" name="categories" class="form__field"  required>
    <label for="username" class="form__label">Categories (comma separated, max length per category: 30)</label>
  </div>
  <br class="half-br">
  <br class="half-br">
  <button class="button" name="submit_update_book" type="submit">
      <span class="button_lg">
        <span class="button_sl"></span>
        <span class="button_text">submit</span>
      </span>
    </button>
</form>







</center>
