<?php
	include_once('./includes/functions.php');
  $id = 0;
  if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    $id = $_SESSION['user']['id'];
  } else if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
  } else {
    header('Location: index.php');
  }

  $error = get_error_message();
  $success = get_success_message();

  $posts = get_posts($id);
  if (!empty($posts)) {
    $title = $posts[0]['login'];
  } else {
    $title = $_SESSION['user']['name'];
  }
	include_once('./includes/header.php');
	include_once('./includes/posts.php');
	include_once('./includes/footer.php');
?>
