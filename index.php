<?php
	include_once('./includes/functions.php');
	$posts = get_posts();
	$title = 'Главная страница';
	$error = get_error_message();
	$success = get_success_message();


	include_once('./includes/header.php');
	if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
		include_once('./includes/twitt_form.php');
	}
	include_once('./includes/posts.php');
	include_once('./includes/footer.php');
?>
