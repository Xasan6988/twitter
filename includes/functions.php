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

  function get_posts($user_id = 0, $sort = false) {
    if ($user_id > 0) return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id WHERE posts.user_id = $user_id;")->fetchAll();
    return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id ORDER BY posts.`date` DESC;")->fetchAll();
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

  function add_post($post_data) {
    if (empty($post_data)) return false;
    $user_id = $_SESSION['user']['id'];
    $text = trim($post_data['text']);
    $image = NULL;
    if (mb_strlen($text) > 250) {
      $text = mb_substr($text, 0, 250)." ...";
    }
    if (isset($post_data['image']) && !empty($post_data['image'])) {
      $image = $post_data['image'];
    }
    return db_query("INSERT INTO `posts` (`id`, `user_id`, `text`, `image`, `date`) VALUES (NULL, '$user_id', '$text', '$image', CURRENT_TIMESTAMP);", true);
  }


  function delete_post($id) {
    if (empty($id)) return false;
    $user_id = $_SESSION['user']['id'];
    return db_query("DELETE FROM `posts` WHERE `id`= $id AND `user_id` = '$user_id';", true);
  }

  function get_likes_count($post_id) {
    if (empty($post_id)) return false;

    return db_query("SELECT * FROM `likes` WHERE `post_id` = '$post_id'")->fetchAll();
  }

  function user_is_like($post_id, $user_id) {
    if (empty($post_id) || empty($user_id)) return false;

    return db_query("SELECT * FROM `likes` WHERE `post_id` = '$post_id' AND `user_id` = '$user_id'")->fetchColumn();
  }

  function like_handler($post_id, $user_id) {
    if (empty($post_id) || empty($user_id)) return false;

    $is_like = !empty(user_is_like($post_id, $user_id));

    if (!$is_like) {
      return db_query("INSERT INTO `likes` (`user_id`, `post_id`) VALUES ('$user_id' , '$post_id');", true);
    } else {
      return db_query("DELETE FROM `likes` WHERE `user_id` = '$user_id' AND `post_id` = '$post_id'", true);
    };
  };
?>
