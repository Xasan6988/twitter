<?php
  include_once('config.php');

  function debug($data, $stop = false) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($stop) die;
  }

  function get_title($title = '') {
    if (empty($title)) return SITE_NAME;
    return SITE_NAME . "- $title";
  }

  function getUrl($url = '') {
    return HOST . "/$url";
  };

  function db() {
    try {
      return new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS,
      [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $e) {
      die($e -> getMessage());
    }
  };

  function db_query($sql, $exec = false) {
    if (empty($sql)) return false;

    if ($exec) return db()->exec($sql);

    return db()->query($sql);
  };

  function get_posts($user_id = 0) {
    if ($user_id > 0) return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id WHERE posts.user_id = $user_id;")->fetchAll();
    return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id;")->fetchAll();
  }

  function get_user_info($login) {
    if (empty($login)) return false;

    return db_query("SELECT * FROM `users` WHERE `login` = '$login';")->fetch();
  };

  function add_user($login, $pass) {
    if (empty($login) || empty($pass)) return false;

    $login = trim($login);
    $name = ucfirst($login);
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    return db_query("INSERT INTO `users` (`id`, `login`, `pass`, `name`) VALUES (NULL, '$login', '$hashed_pass', '$name');", true);
  };

  function register_user($auth_data) {
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || !isset($auth_data['pass']) || empty($auth_data['pass']) || !isset($auth_data['pass2']) || empty($auth_data['pass2'])) return false;


    $user = get_user_info($auth_data['login']);

    if (!empty($user)) {
      $_SESSION['alert']['error'] = "Пользователь с логином " . $auth_data['login'] . " уже существует";
      header('Location: '. getUrl('register.php'));
      die;
    }

    if ($auth_data['pass'] !== $auth_data['pass2']) {
      $_SESSION['alert']['error'] = "Введенные пароли не совпадают";
      header('Location: ' . getUrl('register.php'));
      die;
    }

    if (add_user($auth_data['login'], $auth_data['pass'])) {
      header('Location: ' . getUrl());
      die;
    } else {
      $_SESSION['alert']['error'] = 'Саси';
      header('Location: register.php');
      die;
    }
  };

  function login($auth_data) {
    // debug($auth_data);
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || !isset($auth_data['pass']) || empty($auth_data['pass'])) return false;

    $user = get_user_info($auth_data['login']);

    if (empty($user)) {
      $_SESSION['alert']['error'] = 'Неверный логин или пароль';
      header('Location: '. getUrl());
      die;
    }

    if (password_verify($auth_data['pass'], $user['pass'])) {
      $_SESSION['user'] = $user;
      header('Location: '. getUrl());
      die;
    } else {
      $_SESSION['alert']['error'] = 'Неверный логин или пароль';
      header('Location: '. getUrl());
      die;
    }
  };

  function get_error_message() {
    $error = '';
    if (isset($_SESSION['alert']['error']) && !empty($_SESSION['alert']['error'])) {
      $error = $_SESSION['alert']['error'];
      $_SESSION['alert']['error'] = '';
      return $error;
    } else {
      return '';
    }
  }

  function get_success_message() {
    $success = '';
    if (isset($_SESSION['alert']['success']) && !empty($_SESSION['alert']['success'])) {
      $success = $_SESSION['alert']['success'];
      $_SESSION['alert']['success'] = '';
      return $success;
    } else {
      return '';
    }
  }

?>
