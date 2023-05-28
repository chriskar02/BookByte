<?php
  include 'html/top.html';
  include 'php/connect.php';
  include 'php/session_auth.php';
  include 'php/nav_buttons.php';
  include 'php/html_disp.php';

  $conn = OpenCon();
  $is_auth = getAuth($conn);
  if(!$is_auth){
    header("Location: /login");
    exit;
  }

  profile_option();
  book_option();
  logout_option($conn);
?>
<title>BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/btn-nav.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/dropdown.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/book-card.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/rating-stars.css">
<link rel="stylesheet" type="text/css" href="../static/css/home.css">

</head><body>

  <?php
    include 'php/header.php';
  ?>


  <main>
    <div class="content">
      <div class="search">
        <form class="form__group field srchinp"action=""method="POST">
    				<input type="text" class="form__field"name="search-terms"placeholder="Sreach terms">
    				<label for="name" class="form__label">Serach for Title, ISBN, Author...</label>
            <br>
            <br>
            <button class="button" name="submit_search" type="submit">
              <span class="button_lg">
                <span class="button_sl"></span>
                <span class="button_text">SEARCH</span>
              </span>
            </button>
    		</form>
        <br class="half-br">
        sort by
        <form action=""method="POST"style="display:inline-block;">
        <button class="btn-simple-blue smaller"name="submit_sortby_pop">
      		popuparity
  			</button>,
        </form>
        <form action=""method="POST"style="display:inline-block;">
        <button class="btn-simple-blue smaller"name="submit_sortby_rating">
      		rating
  			</button>,
        </form>
        or select category:
        <form class="dropdown"action=""method="POST">
    		  <select class="dropdown-select smaller"name="submit_select_category"onchange="this.form.submit()">
    		    <option value="default" disabled selected hidden>all categories</option>
            <?php

            $query = "select distinct category from category";
            $result = mysqli_query($conn, $query);
            while($tr = mysqli_fetch_row($result)){
              echo '<option value="'.$tr[0].'">'. $tr[0] .'</option>';
            }

            ?>
    		  </select>
        </form>
      </div>
      <div class="results">
        <?php
          if(isset($_POST['submit_select_category'])){
            $category = $_POST['submit_select_category'];

            $query = "SELECT book.title, AVG(verified_ratings.stars), book.summary, book.cover_image, book.isbn FROM book LEFT JOIN verified_ratings ON book.isbn = verified_ratings.isbn JOIN category ON book.isbn = category.isbn WHERE category.category = '".$category."' GROUP BY book.isbn, book.title, book.summary, book.cover_image;";
            $result = mysqli_query($conn, $query);

            $tr = mysqli_fetch_row($result);
            echo '<div class="inner">showing '.mysqli_num_rows($result).' results filtered by category: '. $category .'</div><br><div class="inner">';
            while($tr){
              echo generateBookItem($tr[0], "data:image/jpeg;base64," . blobToSrc($tr[3]), $tr[1]*20, $tr[2], $tr[4]);
              $tr = mysqli_fetch_row($result);
            }

            echo '</div>';
          }

          if(isset($_POST['submit_sortby_rating'])){

            $query = "SELECT book.title, AVG(verified_ratings.stars) AS rating, book.summary, book.cover_image, count_table.total_count, book.isbn
FROM book
LEFT JOIN verified_ratings ON book.isbn = verified_ratings.isbn
JOIN (SELECT COUNT(*) AS total_count FROM book) AS count_table
GROUP BY book.isbn, book.title, book.summary, book.cover_image, count_table.total_count
ORDER BY rating DESC;
";
            $result = mysqli_query($conn, $query);

            $tr = mysqli_fetch_row($result);
            echo '<div class="inner">showing '.$tr[4].' results sorted by rating</div><br><div class="inner">';

            while($tr){
              echo generateBookItem($tr[0], "data:image/jpeg;base64," . blobToSrc($tr[3]), $tr[1]*20, $tr[2], $tr[5]);
              $tr = mysqli_fetch_row($result);
            }

            echo '</div>';
          }

          if(isset($_POST['submit_sortby_pop'])){

            $query = $query = "select b.title, avg(stars), b.summary, b.cover_image, total_count, total_borrowed_tb.total_borrowed as tot_bor, b.isbn
          from book as b left outer join verified_ratings on b.isbn = verified_ratings.isbn
          left join (
            select isbn, count(*) as total_borrowed
            from book
            natural join loan
            where in_out = 'borrowed'
            group by isbn
          ) as total_borrowed_tb on b.isbn = total_borrowed_tb.isbn
          join (select count(*) as total_count from book) as count_table
          group by b.isbn, b.title
          order by tot_bor desc
          ";
          $result = mysqli_query($conn, $query);

            $tr = mysqli_fetch_row($result);
            echo '<div class="inner">showing '.$tr[4].' results sorted by popularity</div><br><div class="inner">';

            while($tr){
              echo generateBookItem($tr[0], "data:image/jpeg;base64," . blobToSrc($tr[3]), $tr[1]*20, $tr[2], $tr[6]);
              $tr = mysqli_fetch_row($result);
            }

            echo '</div>';
          }

          if(isset($_POST['submit_search'])){
            $terms = $_POST['search-terms'];
            $query1 = "select book.isbn, title, avg(stars) as rating, summary, cover_image from book left join verified_ratings on book.isbn = verified_ratings.isbn where title like '%".$terms."%' group by book.isbn";
            $query2 = "select book.isbn, title, avg(stars) as rating, summary, cover_image from book left join verified_ratings on book.isbn = verified_ratings.isbn where book.isbn like '%".$terms."%' group by book.isbn";
            $query3 = "select book.isbn, title, avg(stars) as rating, summary, cover_image from book left join verified_ratings on book.isbn = verified_ratings.isbn join author on book.isbn = author.isbn where name like '%".$terms."%' group by book.isbn";
            $query = "select merged.title, merged.rating, summary, cover_image, isbn from ((".$query1.") UNION (".$query2.") UNION (".$query3.")) as merged";
            $result = mysqli_query($conn, $query);

            $tr = mysqli_fetch_row($result);
            echo '<div class="inner">found '.mysqli_num_rows($result).' results for "'.$terms.'"</div><br><div class="inner">';

            while($tr){
              echo generateBookItem($tr[0], "data:image/jpeg;base64," . blobToSrc($tr[3]), $tr[1]*20, $tr[2], $tr[4]);
              $tr = mysqli_fetch_row($result);
            }

            echo '</div>';
          }
        ?>

      </div>
    </div>
  </main>


</body></html>
