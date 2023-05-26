<?php

function generateUserRating($username, $rating, $description, $date) {
  $output = '<div class="user-rating"><div class="row1"><label class="username">' . $username .'</label><label class="date">' . $date . '</label><div class="rating"style="--rating:' . $rating . '"></div></div><div class="row2">' . $description . '</div></div>';
  return $output;
}

function generateSchoolAvail($sch_name, $sch_city, $free_copies) {
  $output = '<div class="user-rating"><div class="row1"><label class="username">' . $sch_name .' of ' . $sch_city . '</label><label class="date">free copies: ' . $free_copies . '</label>';
  $output .= '</div><div class="row2">

  <button class="button">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">BORROW</span>
    </span>
  </button>

  <button class="button">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">RESERVE</span>
    </span>
  </button>

  </div></div>';
  return $output;
}

?>

<?php
  include 'html/top.html';
?>
<?php
  include 'php/connect.php';
  include 'php/session_auth.php';

  $conn = OpenCon();
  $is_auth = getAuth($conn);
  if(!$is_auth){
    header("Location: login");
    exit;
  }
?>

<title>[Book Title Here] | BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/btn-nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/rating-stars.css">
<link rel="stylesheet" type="text/css" href="../static/css/book.css">

</head><body>

  <?php
    include 'html/header.html';
  ?>


  <main>
    <div class="card">
      <div class="left">
        <img src="data:image/jpeg;base64,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"
        alt="cover image couldnt load" />
      </div>
      <div class="right">
        <div class="title">Database System Concepts</div>
        <div class="authors">Abraham Silberschatz, Henry F. Korth, S. Sudarshan</div>
        <div class="category">Computer Science, Programming, Nonfiction, Reference, Computers, Technology</div>
        <div class="rating"style="--rating:75.4"></div>
        <div class="publisher tinydetails">pub: McGraw Hill</div>
        <div class="pages tinydetails">1376 pages</div>
        <div class="isbn tinydetails">ISBN: 9780078022159</div>
        <div class="language tinydetails">language: en-us</div>
        <div class="tags tinydetails"></div>
        <div class="summary">Database System Concepts by Silberschatz, Korth and Sudarshan is now in its 7th edition and is one of the cornerstone texts of database education. It presents the fundamental concepts of database management in an intuitive manner geared toward allowing students to begin working with databases as quickly as possible. The text is designed for a first course in databases at the junior/senior undergraduate level or the first year graduate level. It also contains additional material that can be used as supplements or as introductory material for an advanced course. Because the authors present concepts as intuitive descriptions, a familiarity with basic data structures, computer organization, and a high-level programming language are the only prerequisites. Important theoretical results are covered, but formal proofs are omitted. In place of proofs, figures and examples are used to suggest why a result is true.</div>
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
        your remaining loans: 1<br>
        your remaining reservations: 0
        <?php
          echo generateSchoolAvail("5o lykeio", "kilkis", 76);
          echo generateSchoolAvail("3o lykeio", "koropi", 2);
          echo generateSchoolAvail("10 dikotiko", "zografou", 1);
        ?>
      </div>
      <div id="reviews"class="reviews extend">

        <?php
          echo generateUserRating("tester3", 100, "bla bla", "2023-05-25 21:39");
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
