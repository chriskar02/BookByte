<p>users that have borrowed at least one book and have delayed the return.</p>
<center>
<?php

$query = "SELECT username, DATEDIFF(NOW(), date) AS days_late FROM loan WHERE in_out = 'borrowed' and transaction_verified = 1 AND DATE_ADD(date, INTERVAL 7 DAY) < NOW();";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Delay (days)</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>' . $tr[0] . '</td><td>' . $tr[1] . '</td></form></td></tr>';
}
echo '</tbody></table>';

echo '<p>all current verified borrows:</p>';

$query = "SELECT username, date FROM loan WHERE in_out = 'borrowed' and transaction_verified = 1";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Verification Date</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td>' . $tr[0] . '</td><td>' . $tr[1] . '</td></form></td></tr>';
}
echo '</tbody></table>';


?>

</center>
