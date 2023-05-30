<p>teachers of age < 40 years and their number of loans sorted.</p>
<center>
<?php

$query = "SELECT teacher.username, DATEDIFF(NOW(), birth)/365 AS age, count(*) as n_loans FROM teacher join loan on teacher.username = loan.username WHERE in_out = 'borrowed' and transaction_verified <> 0 and DATEDIFF(NOW(), birth)/365 < 40 group by teacher.username order by n_loans desc;";
$result = mysqli_query($conn, $query);
echo '<table class="custom-table"><tr><thead><tr><th>Username</th><th>Age</th><th>Number of Loans</th></tr></thead><tbody>';
while($tr = mysqli_fetch_row($result)){
  echo '<tr><td><a href="/user/'.$tr[0].'">' . $tr[0] . '</a></td><td>' . $tr[1] . '</td><td>' . $tr[2] . '</td></td></tr>';
}
echo '</tbody></table>';

?>

</center>
