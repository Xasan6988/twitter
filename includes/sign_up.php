<?php
  include_once('./functions.php');
  error_reporting(E_ALL);
  ini_set('display_errors', 'on');
  mb_internal_encoding('UTF-8');
  if (isset($_POST['login']) && !empty($_POST['login'])) {
    register_user($_POST);
  }
?>
