<?php
  include 'html/top.html';
?>

<title>Testing | BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/login.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">

</head><body>
here we test features
<?php

  include 'php/connect.php';
	include 'php/html_disp.php';
	$conn = OpenCon();

  $query = "select username, name, password, email from user where user_verified = 1";
  $query = "select username, birth, isbn, category, name from user natural join loan natural join book natural join teacher natural join category";
  $query = "select * from user order by username asc limit 5";
  echo tableResults($conn, $query, ['username', 'password']);
  $query = "select username, token from session_tokens";
  echo tableResults($conn, $query, ['username', 'token']);
  $query = "desc session_tokens";
  echo tableResults($conn, $query, []);
  $query = "desc user";
  echo tableResults($conn, $query, []);

/*bu


  $query1 = "select isbn, title, avg(stars) as rating from book natural join ratings where title like '%164239%' group by isbn";
  $query2 = "select isbn, title, avg(stars) as rating from book natural join ratings where isbn like '%1456349%' group by isbn";
  $terms = "da";
  $query3 = "select isbn, title, avg(stars) as rating, name from book natural join ratings natural join author where name like '%".$terms."%' group by isbn";
  $query = "select distinct merged.title, merged.rating from ((".$query1.") UNION (".$query2.")) as merged";


*/

  $query1 = "select isbn, title, avg(stars) as rating from book natural join ratings where title like '%164239%' group by isbn";
  $query2 = "select isbn, title, avg(stars) as rating from book natural join ratings where isbn like '%1456349%' group by isbn";
  $terms = "da";
  $query3 = "select isbn, title, avg(stars) as rating from book natural join ratings natural join author where name like '%".$terms."%' group by isbn";
  $query = "select distinct merged.title, merged.rating from ((".$query1.") UNION (".$query2.") UNION (".$query3.")) as merged";

  echo tableResults($conn, $query3, ['by author']);
  echo tableResults($conn, $query, ['merge']);

?>

</body></html>
