<?php

$url = $_GET['url'];

if (preg_match("/book\/(.*)/", $url, $matches)){
  $isbn = $matches[1];
	$url = "book";
}
if (preg_match("/user\/(.*)/", $url, $matches)){
  $page_username = $matches[1];
	$url = "user";
}

switch ($url) {

	case '': include 'templates/home.php';break;
	case 'home': include 'templates/home.php';break;
	case 'login': include 'templates/login.php';break;
	case 'register': include 'templates/register.php';break;
	case 'book': include 'templates/book.php';break;
	case 'user': include 'templates/user.php';break;
	case 'test': include 'templates/test.php';break;
	case 'info': include 'templates/info.php';break;
	default: include 'templates/404.php';break;

}

?>
