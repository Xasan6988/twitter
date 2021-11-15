<?php
	include_once('./includes/functions.php');
	$title = 'Регистрация';
	$error = get_error_message();
	$success = get_success_message();
	if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
		header('Location: '.getUrl());
	}
	include_once("./includes/header.php");
	include_once("./includes/register_form.php");
	include_once("./includes/footer.php");
?>
