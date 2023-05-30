<p>average rating (stars) per category (verified user reviews only) ordered by rating</p>
<center>
<?php

$query = "SELECT avg(stars) as average, category from ratings join category on category.isbn = ratings.isbn where rating_verified = 1 group by category order by average desc";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Average Rating</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>' . $tr[1] . '</td><td>' . $tr[0] . '</td></form></td></tr>';
}
echo '</tbody></table>';


?>

</center>
