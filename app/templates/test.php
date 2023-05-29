<?php
  include 'html/top.html';
?>

<title>Testing | BookByte</title>
<link rel="stylesheet" type="text/css" href="../static/css/login.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/button.css">
<link rel="stylesheet" type="text/css" href="../static/css/imports/input.css">

</head><body>
<?php

  include 'php/connect.php';
	include 'php/html_disp.php';
	$conn = OpenCon();

  $query = "select user.username, user.password, school.name, school.city, user.user_verified
  from user join school on user.sch_id = school.id
  where user.username not in (select username from teacher) and user.username not in (select username from admin) order by user.username limit 5";
  echo tableResults($conn, $query, ['only student username', 'password', 'sch name', 'sch city', 'USER verified']);

  $query = "SELECT v.username, user.password, school.name, school.city, user.user_verified FROM verified_handler as v join user on v.username = user.username join school on user.sch_id = school.id order by v.username";
  echo tableResults($conn, $query, ['verif handlers usrnm', 'password', 'sch name', 'sch city', 'USER verified']);
  $query = "SELECT v.username, user.password, school.name, school.city, user.user_verified FROM teacher as v join user on v.username = user.username join school on user.sch_id = school.id where handler_verified = 0 and handler_request = 1 order by v.username  limit 5";
  echo tableResults($conn, $query, ['NOT verif handlers usrnm', 'password', 'sch name', 'sch city', 'USER verified']);
  $query = "SELECT user.username, password, user.user_verified FROM admin join user on admin.username = user.username order by user.username  limit 5";
  echo tableResults($conn, $query, ['admin usrnm', 'password', 'USER verified']);

  $query = "SELECT * from reservation order by username";
  echo tableResults($conn, $query, ['']);


?>

</body></html>
