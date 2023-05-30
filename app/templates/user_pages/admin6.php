<p>Top 3 pairs of categories that are common in books and have appeared in loans.</p>
<center>
<?php

$query = "SELECT category1, category2, count(distinct isbn) as count
from (
select
case when c1.category < c2.category then c1.category else c2.category end as category1,
case when c1.category < c2.category then c2.category else c1.category end as category2,
l.isbn
from loan l
join category c1 on l.isbn = c1.isbn
join category c2 on l.isbn = c2.isbn and c1.category <> c2.category
) as pairs
group by category1, category2
order by count desc
limit 3;";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Categories</th><th>Number of Loans</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>'. $tr[0] . ' & '.$tr[1].'</td><td>'. $tr[2] . '</td></tr>';
}
echo '</tbody></table>';

?>

</center>
