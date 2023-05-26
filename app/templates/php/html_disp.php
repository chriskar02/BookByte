<?php

	function tableResults($conn, $query, $cols) {
    $result = mysqli_query($conn, $query);

    $output = '<table><tr><thead><tr>';
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

?>
