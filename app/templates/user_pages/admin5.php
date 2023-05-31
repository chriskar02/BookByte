<p>Handlers that have lent the same number of books, with more than 20 loans, within a one-year period.</p>
<center>
<?php

$query = "SELECT name, COUNT(isbn) AS num_books
FROM author
GROUP BY name
HAVING count(isbn) <= (
	SELECT MAX(book_count) - 5
	FROM (
		SELECT COUNT(isbn) as book_count
		FROM author
		GROUP BY name
	) AS book_count
)
ORDER BY count(isbn) DESC
";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Handler Username</th><th>Number of Loans</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>'. $tr[0] . '</td><td>'. $tr[1] . '</td></tr>';
}
echo '</tbody></table>';

?>

</center>
