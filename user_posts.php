<?php
	include_once('./includes/functions.php');
  $id = 0;
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
  } else {
    header('Location: index.php');
  }

	$posts = get_posts($id);
	include_once('./includes/header.php');
	include_once('./includes/posts.php');
	include_once('./includes/footer.php');
?>
