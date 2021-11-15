<?php
  include_once('./functions.php');

  if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . getUrl());
  };
  error_reporting(E_ALL);
  ini_set('display_errors', 'on');
  mb_internal_encoding('UTF-8');
  // debug($_GET, true);
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    if (!delete_post($_GET['id'])) {
      $_SESSION['alert']['error'] = 'Во время удаления поста что-то пошло не так';
      header('Location: '. getUrl());
    } else {
      header('Location: '. getUrl());
    };
  }
?>


<!--  -->
