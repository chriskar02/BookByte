<p>Handlers that have borrowed the same number of books, with more than 20 loans, within a one-year period.</p>
<center>
<?php

$query = "SELECT GROUP_CONCAT(handler_username), loaned
FROM (
	SELECT handler_username, COUNT(*) AS loaned
	FROM loan
	WHERE in_out = 'borrowed'
		AND transaction_verified <> 0
		AND date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
	GROUP BY handler_username
	HAVING loaned > 20
	) AS subquery
GROUP BY loaned
ORDER BY loaned DESC;
";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Number of Loans</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>'. $tr[0] . '</td><td>'. $tr[1] . '</td></tr>';
}
echo '</tbody></table>';

?>

</center>
