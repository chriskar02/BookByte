<p>Authors who have written at least 5 books less than the author with the most books.</p>
<center>
<?php

$query = "SELECT a.name, COUNT(*) AS num_books
FROM author AS a
GROUP BY a.name
HAVING num_books >= (
  SELECT COUNT(*) - 5
  FROM author
  GROUP BY name
  ORDER BY COUNT(*) DESC
  LIMIT 1
)";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Name</th><th>Number of Books</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>'. $tr[0] . '</td><td>'. $tr[1] . '</td></tr>';
}
echo '</tbody></table>';

?>

</center>
