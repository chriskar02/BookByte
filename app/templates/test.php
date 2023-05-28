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

  $query = "select * from user left join session_tokens on user.username = session_tokens.username order by user.username limit 10";
  echo tableResults($conn, $query, ['username', 'password', 'token']);

  $query = "SELECT * FROM school_storage WHERE isbn = '1551303345';";
  echo tableResults($conn, $query, ['school_storage for 1551303345']);


  $query = "SELECT * from reservation where username = 'andrewholt'";

  echo tableResults($conn, $query, ['my reservations']);
  $query = "SELECT * from loan where username = 'andrewholt'";
  echo tableResults($conn, $query, ['my loans']);
  $query = "SELECT username
FROM (
    SELECT username FROM loan WHERE username = 'andrewholt'
    UNION ALL
    SELECT username FROM reservation WHERE username = 'andrewholt'
) AS combined_results";
  echo tableResults($conn, $query, ['my loans and reservations']);


?>

</body></html>
