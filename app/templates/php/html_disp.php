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

function generateBookItem($title, $imageurl, $rating, $summary) {
  $output = '<form action=""method="POST"class="item-card"><button type="submit"name="submit_book"class="flip-card"style="border: none;"><div class="flip-card-inner"><div class="flip-card-front">';
  $output .= '<img src="' . $imageurl .'"class="book-img"alt="book cover couldnt load">';
  $output .= '<p class="book-title">' . $title .'</p>';
  $output .= '<input type="text"style="display:none;"name="book-title"value="' . $title .'"/>';
  $output .= '<div class="rating"style="--rating:' . $rating . '"></div></div>';
  $output .= '<div class="flip-card-back">' . $summary . '</div></div></button></form>';
  return $output;
}

function blobToSrc($blob) {
	return substr($blob, 2, -1);
}

?>
