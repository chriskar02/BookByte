<?php
// Retrieve the requested URL
$url = $_GET['url'];

// Perform routing based on the URL
switch ($url) {

	case '': include 'templates/home.php';break;
	case 'login': include 'templates/login.php';break;
	case 'register': include 'templates/register.php';break;
	case 'book': include 'templates/book.php';break;
	case 'user': include 'templates/user.php';break;
	default: include 'templates/404.php';break;

}
?>
