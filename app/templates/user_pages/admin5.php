<p>Handlers that have borrowed the same number of books, with more than 20 loans, within a one-year period.</p>
<center>
<?php

$query = "SELECT l.username, COUNT(DISTINCT l.isbn) AS num_books_borrowed
FROM loan AS l
WHERE l.in_out = 'borrowed'
  AND l.transaction_verified <> 0
  AND l.date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
GROUP BY l.username
HAVING COUNT(DISTINCT l.isbn) > 20;
";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Number of Loans</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>'. $tr[0] . '</td><td>'. $tr[1] . '</td></tr>';
}
echo '</tbody></table>';

?>

</center>
