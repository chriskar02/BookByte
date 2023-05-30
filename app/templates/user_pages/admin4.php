<p>Authors whose books have not been borrowed.</p>
<center>
<?php

$query = "SELECT author.name from author where author.isbn not in (
    select loan.isbn from loan where in_out = 'borrowed' and transaction_verified <> 0
)";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Author Name</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>'. $tr[0] . '</td></tr>';
}
echo '</tbody></table>';

?>

</center>
