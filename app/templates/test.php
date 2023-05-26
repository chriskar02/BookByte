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
	$conn = OpenCon();

	function tableResults($conn, $query, $cols) {
    $result = mysqli_query($conn, $query);

    $output = '<table><tr>';
    echo '<thead><tr>';
    foreach ($cols as $value) {
      $output .= '<th>' . $value . '</th>';
    }
    $output .= '</tr></thead><tbody>';

    while($tr = mysqli_fetch_row($result)){
      $output .= '<tr>';
  		foreach ($tr as $value) {
  			$output .= '<td>' . $value . '</td>';
  		}
  		$output .= '</tr>';
    }
    $output .= '</tbody></table>';

		return $output;
	}

	$query = "select username, name, password, email from user where user_verified = 1";
  $query = "select username, birth, isbn, category, name
	from user natural join loan natural join book natural join teacher
		  natural join category";
  echo tableResults($conn, $query, []);

?>

</body></html>
