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

function generateBookItem($title, $imageurl, $rating, $summary, $isbn="") {
  $output = '<form action=""method="POST"class="item-card"><button type="submit"name="submit_book"class="flip-card"style="border: none;"><div class="flip-card-inner"><div class="flip-card-front">';
  $output .= '<img src="' . $imageurl .'"class="book-img"alt="book cover couldnt load">';
  $output .= '<p class="book-title">' . $title .'</p>';
  $output .= '<input type="text"style="display:none;"name="book-isbn"value="' . $isbn .'"/>';
  $output .= '<div class="rating"style="--rating:' . $rating . '"></div></div>';
  $output .= '<div class="flip-card-back">' . $summary . '</div></div></button></form>';
  return $output;
}

function blobToSrc($blob) {
	return substr($blob, 2, -1);
}

function generateUserRating($username, $rating, $description, $date) {
  $output = '<div class="user-rating"><div class="row1"><label class="username">' . $username .'</label><label class="date">' . $date . '</label><div class="rating"style="--rating:' . $rating . '"></div></div><div class="row2">' . $description . '</div></div>';
  return $output;
}

function generateSchoolAvail($sch_name, $sch_city, $free_copies, $rem_loans, $rem_rsv, $sch_id) {
  $output = '<form class="user-rating"action=""method="post"><div class="row1"><label class="username">' . $sch_name .' of ' . $sch_city . '</label><label class="date">free copies: ' . $free_copies . '</label>';
  $output .= '</div><div class="row2">';
  $output .= '<input type="text"style="display:none"value="'.$sch_id.'"name="sch-id"/>';
  if($rem_loans > 0) $output .= '
  <button class="button"type="submit"name="submit_borrow">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">BORROW</span>
    </span>
  </button>';

  if($rem_rsv > 0) $output .= '<button class="button"type="submit"name="submit_reserve">
    <span class="button_lg">
      <span class="button_sl"></span>
      <span class="button_text">RESERVE</span>
    </span>
  </button>';

  $output .= '</div></form>';
  return $output;
}

?>
