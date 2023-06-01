<?php

  # $isbn variable is defined in index.php !!

  include 'html/top.html';
  include 'php/html_disp.php';
  include 'php/connect.php';
  include 'php/nav_buttons.php';
  include 'php/gen_functions.php';
  include 'php/session_auth.php';

  $conn = OpenCon();
  $is_auth = getAuth($conn);
  if(!$is_auth){
    header("Location: /login");
    exit;
  }

  profile_option();
  logout_option($conn);

  $username = $_COOKIE['username'];
?>

<?php
  #get book details
  $query = "select title, publisher, pages, language, keywords, summary, cover_image, avg(stars) from book
  left outer join verified_ratings on verified_ratings.isbn = book.isbn where book.isbn = '".$isbn."'";
  $result = mysqli_query($conn, $query);
  $tr=mysqli_fetch_row($result);
  if(!mysqli_num_rows($result) || $tr[0]==""){
    header("Location: /404");
    exit;
  }

  $title = $tr[0];
  $publisher = $tr[1];
  $pages = $tr[2];
  $language = $tr[3];
  $keywords = $tr[4];
  $summary = $tr[5];
  $cover = "data:image/jpeg;base64," . blobToSrc($tr[6]);
  $rating = $tr[7];

  $query = "select name from author where isbn = '".$isbn."'";
  $result = mysqli_query($conn, $query);
  $tr = mysqli_fetch_row($result);
  $authors = $tr[0];
  while($tr = mysqli_fetch_row($result)){
    $authors .= ', '.$tr[0];
  }

  $query = "select category from category where isbn = '".$isbn."'";
  $result = mysqli_query($conn, $query);
  $tr = mysqli_fetch_row($result);
  $category = $tr[0];
  while($tr = mysqli_fetch_row($result)){
    $category .= ', '.$tr[0];
  }

?>



<title><?php echo $title; ?> | BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/btn-nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/rating-stars.css">
<link rel="stylesheet" type="text/css" href="../static/css/book.css">

</head><body>

  <?php
    include 'php/header.php';
  ?>

  <?php

    $is_teacher = getStatus($conn, $username)[0];
    if($is_teacher){
      $max_loan_or_rsv = 1;
    }
    else{
      $max_loan_or_rsv = 2;
    }

  ?>



  <main>
    <div class="card">
      <div class="left">
        <img src="<?php echo $cover; ?>"
        alt="cover image couldnt load" />
      </div>
      <div class="right">
        <div class="title"><?php echo $title; ?></div>
        <div class="authors"><?php echo $authors; ?></div>
        <div class="category"><?php echo $category; ?></div>
        <div class="rating"style="--rating:<?php echo $rating*20; ?>"></div>
        <div class="publisher tinydetails">pub: <?php echo $publisher; ?></div>
        <div class="pages tinydetails"><?php echo $pages; ?> pages</div>
        <div class="isbn tinydetails">ISBN: <?php echo $isbn; ?></div>
        <div class="language tinydetails">language: <?php echo $language; ?></div>
        <div class="tags tinydetails">keywords: <?php echo substr($keywords, 0, -1); ?></div>
        <div class="summary"><?php echo $summary; ?></div>
      </div>
    </div>
    <div class="card extras">
      <div class="control">
        <button class="btn-simple-blue"onclick="selectThisBtn(this);">Borrow</button>
        <button id="selected-left"class="btn-simple-blue"onclick="selectThisBtn(this);">Reviews</button>
      </div>
      <br>
      <hr>
      <br>
      <div id="storage"class="storage extend">

        <?php
          if(isset($_POST['submit_reserve'])){
            $sch_id = $_POST['sch-id'];

            $query = "SELECT username
            FROM (
              SELECT username FROM loan WHERE username = '".$username."' and isbn = '".$isbn."' and transaction_verified <> 2 and in_out <> 'returned'
              UNION ALL
              SELECT username FROM reservation WHERE username = '".$username."' and isbn = '".$isbn."'
            ) AS combined_results";

            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result)){
              echo "<div class='feedback red'>You already have this book!</div>";
            }
            else{
              $query = "insert into reservation (username, isbn, sch_id) values ('".$username."', '".$isbn."', '".$sch_id."')";
              if($result = mysqli_query($conn, $query)){
                echo "<div class='feedback green'>success</div>";
                echo '<script>window.location.href = window.location.href;</script>';
              }
              else{
                echo "<div class='feedback red'>[database error] review submit failed, try again.</div>";
              }
            }
          }
          if(isset($_POST['submit_borrow'])){
            $sch_id = $_POST['sch-id'];
            $query = "SELECT username
            FROM (
              SELECT username FROM loan WHERE username = '".$username."' and isbn = '".$isbn."'
              UNION ALL
              SELECT username FROM reservation WHERE username = '".$username."' and isbn = '".$isbn."'
            ) AS combined_results";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result)){
              echo "<div class='feedback red'>You already have this book!</div>";
            }
            else{
              $query = "insert into loan (username, isbn, sch_id, in_out) values ('".$username."', '".$isbn."', '".$sch_id."', 'borrowed')";
              if($result = mysqli_query($conn, $query)){
                echo "<div class='feedback green'>success</div>";
                echo '<script>window.location.href = window.location.href;</script>';
              }
              else{
                echo "<div class='feedback red'>[database error] review submit failed, try again.</div>";
              }
            }
          }
        ?>
        <?php
          #print schools
          $query = "select count(*) from loan where username = '".$username."' and in_out = 'borrowed' and datediff(now(),date) < 7";
          $result = mysqli_query($conn, $query);
          $tr = mysqli_fetch_row($result);
          $borrowed7 = $tr[0];
          echo "<p>borrowed last 7 days: ".$borrowed7."</p>";

          $query = "select count(*) from loan where username = '".$username."' and transaction_verified = 1 and datediff(now(),date) > 7";
          $result = mysqli_query($conn, $query);
          $tr = mysqli_fetch_row($result);
          echo "<p>overdue: ".$tr[0]."</p>";

          $rem_loans = 0;
          $rem_rsv = 0;
          if($tr[0] > 0){
            $rem_loans = 0;
            $rem_rsv = 0;
            echo "<p>you dont have availiable loans or reservations because you have overdue loans.</p>";
          }
          else{
            $rem_loans = $max_loan_or_rsv - $borrowed7;

            $query = "select count(*) from reservation where username = '".$username."' and datediff(now(),date) < 7";
            $result = mysqli_query($conn, $query);
            $tr = mysqli_fetch_row($result);
            echo "<p>reservations last 7 days: ".$tr[0]."</p>";
            $rem_rsv = $max_loan_or_rsv - $tr[0];
            $rem_loans_or_rsv = $rem_loans - $tr[0];
            if($rem_loans_or_rsv <= 0){
              echo "<p>you don't have any availiable loans or reservations.</p>";
            }
            else{
              echo "<p>your availiable loans or reservations: ".($rem_loans_or_rsv)."</p>";
            }
          }

          $query = "SELECT s.name AS school_name, s.city AS school_city,
    ss.copies AS total_copies,
    COALESCE(loans.total_loans, 0) AS total_loans,
    COALESCE(reservations.total_reservations, 0) AS total_reservations,
    (ss.copies - COALESCE(loans.total_loans, 0) - COALESCE(reservations.total_reservations, 0)) AS available_copies,
    s.id
FROM school s
JOIN school_storage ss ON s.id = ss.sch_id
LEFT JOIN (
    SELECT sch_id, isbn, COUNT(*) AS total_loans
    FROM loan
    WHERE isbn = '".$isbn."' and in_out = 'borrowed' and transaction_verified <> 2
    GROUP BY sch_id, isbn
) loans ON s.id = loans.sch_id
LEFT JOIN (
    SELECT sch_id, isbn, COUNT(*) AS total_reservations
    FROM reservation
    WHERE isbn = '".$isbn."'
    GROUP BY sch_id, isbn
) reservations ON s.id = reservations.sch_id
WHERE ss.isbn = '".$isbn."'
order by ss.copies desc
;";
          $result = mysqli_query($conn, $query);
          while($tr = mysqli_fetch_row($result)){
            echo generateSchoolAvail($tr[0], $tr[1], $tr[5], ($tr[3]+$tr[4]), $rem_loans_or_rsv, $tr[6]);
          }

        ?>

      </div>
      <div id="reviews"class="reviews extend">

        <?php
        $query = "select username, date, stars, description, rating_verified from ratings where username = '" . $username . "' and isbn = '".$isbn."'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
          #if user already has review, print it
          $tr = mysqli_fetch_row($result);
          $myreview = $tr;
          if($tr[4]){
            echo generateUserRating("ME (".$tr[0].")", $tr[2]*20, $tr[3], $tr[1]);
          }
          else{
            echo "<div class='feedback red'>your review is not verified yet</div>";
          }
          $onclick = "document.getElementById('new-review').style.display='block';";
          echo '<button class="button" onclick="'.$onclick.'">
            <span class="button_lg">
              <span class="button_sl"></span>
              <span class="button_text">EDIT REVIEW</span>
            </span>
          </button>
          <form action=""method="post"><button class="button"type="submit" name="submit_delete_rev" >
            <span class="button_lg">
              <span class="button_sl"></span>
              <span class="button_text" >DELETE REVIEW</span>
            </span>
          </button></form>';
        }
        else{
          #else create button for new review
          $onclick = "document.getElementById('new-review').style.display='block';";
          echo '<button class="button" onclick="'.$onclick.'">
            <span class="button_lg">
              <span class="button_sl"></span>
              <span class="button_text">ADD REVIEW</span>
            </span>
          </button>';
        }
        ?>
        <?php
          if(isset($_POST['submit_delete_rev'])){
            $query = "delete from ratings where username = '".$username."' and isbn = '".$isbn."'";
            if($result = mysqli_query($conn, $query)){
              echo "<div class='feedback green'>deleted review</div>";
              echo '<script>window.location.href = window.location.href;</script>';
            }
            else{
              echo "<div class='feedback red'>[database error] review submit failed, try again.</div>";
            }
          }
        ?>
        <br>
        <div id="new-review">
            <form action=""method="post">

              <div class="form__group field">
                <input type="number" name="rating" class="form__field" value="<?php echo $myreview[2] ?>"placeholder="" max="5" min="0" required>
                <label for="rating" class="form__label">Rate (1-5)</label>
              </div>
              <textarea name="description"placeholder="Write something..." required><?php echo $myreview[3] ?></textarea>

              <button class="button" name="submit_review" type="submit">
                <span class="button_lg">
                  <span class="button_sl"></span>
                  <span class="button_text">submit review</span>
                </span>
              </button>
              <?php
                if(isset($_POST['submit_review'])){
                  $rating = $_POST['rating'];
                  $description = $_POST['description'];
                  if(!$myreview){
                    $query = "insert into ratings (username, isbn, stars, description) values ('".$username."','".$isbn."','".$rating."','".$description."')";
                  }
                  else{
                    $query = "update ratings set stars = '".$rating."', description = '".$description."', rating_verified = 0 where username = '".$username."' and isbn = '".$isbn."'";
                  }
                  if($result = mysqli_query($conn, $query)){
                    echo "<div class='feedback green'>success</div>";
                    echo '<script>window.location.href = window.location.href;</script>';
                  }
                  else{
                    echo "<div class='feedback red'>[database error] review submit failed, try again.</div>";
                  }
                }
              ?>
            </form>
            <button class="button"onclick="document.getElementById('new-review').style.display='none';">
              <span class="button_lg">
                <span class="button_sl"></span>
                <span class="button_text">cancel</span>
              </span>
            </button>

        </div>

        <br>
        <?php
          #print reviews
          $query = "select username, date, stars, description from book left outer join verified_ratings on book.isbn = verified_ratings.isbn where book.isbn = '".$isbn."' order by date desc";
          $result = mysqli_query($conn, $query);
          while($tr = mysqli_fetch_row($result)){
            echo generateUserRating($tr[0], $tr[2]*20, $tr[3], $tr[1]);
          }
        ?>
      </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
  </main>



<script type="text/javascript">
	function selectThisBtn(d){
		if(document.getElementById('selected-left')) document.getElementById('selected-left').id="";
		d.id="selected-left";
    if(d.innerHTML === 'Borrow'){
      document.getElementById('storage').style.display="block";
      document.getElementById('reviews').style.display="none";
    }
    else{
      document.getElementById('storage').style.display="none";
      document.getElementById('reviews').style.display="block";
    }
	}
</script>
</body></html>
