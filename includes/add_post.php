<?php
  include_once('./functions.php');

  if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . getUrl());
  };

  error_reporting(E_ALL);
  ini_set('display_errors', 'on');
  mb_internal_encoding('UTF-8');

  if (isset($_POST['text']) && !empty($_POST['text'])) {
    if (!add_post($_POST)) {
      $_SESSION['alert']['error'] = 'Во время добавления поста что-то пошло не так';
    } else {
      header('Location: '. getUrl());
    };
  }
?>
