<?php
  include_once('./functions.php');

  error_reporting(E_ALL);
  ini_set('display_errors', 'on');
  mb_internal_encoding('UTF-8');

  if (isset($_GET['postId']) && !empty($_GET['postId']) && isset($_GET['userId']) && !empty($_GET['userId'])) {
    if (!like_handler($_GET['postId'], $_GET['userId'])) {
      $_SESSION['alert']['error'] = 'При попытке лайкнуть произошла ошибка';
      echo $_SESSION['alert']['error'];
    } else {
      header('Location:' .getUrl());
    }
  }
?>
