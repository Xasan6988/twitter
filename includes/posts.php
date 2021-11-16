<?php if ($posts) {?>
<section class="wrapper">
	<ul class="tweet-list">
    <?php foreach($posts as $post) {?>
      <li>
        <article class="tweet">
          <div class="row">
            <img class="avatar" src="<?php echo $post['avatar']?>" alt="Аватар пользователя <?php echo $post['name']?>">
            <div class="tweet__wrapper">
              <header class="tweet__header">
                <h3 class="tweet-author"><?php echo $post['name']?>
                  <a href="<?php echo getUrl('user_posts.php?id='.$post['user_id'])?>" class="tweet-author__add tweet-author__nickname">@<?php echo $post['login']?></a>
                  <time class="tweet-author__add tweet__date"><?php echo date('d.m.y в H:i', strtotime($post['date']))?></time>
                </h3>
                <?php if ($_SESSION['user']['id'] === $post['user_id']) {?>
                <a href="<?php echo getUrl("./includes/delete_post.php?id=".$post['id'])?>" class="tweet__delete-button chest-icon"></a>
                <?php }?>
              </header>
              <div class="tweet-post">
                <p class="tweet-post__text"><?php echo $post['text']?></p>
                <?php if ($post['image']) {?>
                <figure class="tweet-post__image">
                  <img src="<?php echo $post['image']?>" alt="Сообщение <?php echo $post['name']?>">
                </figure>
                <?php }?>
              </div>
            </div>
          </div>
          <footer>
            <a style="width: 15px; margin-rigth: 10px;" href="<?php echo getUrl("./includes/like_handler.php?postId=".$post['id']."&userId=".$_SESSION['user']['id'])?>" class="tweet__like some_class <?php if (!empty(user_is_like($post['id'], $_SESSION['user']['id']))) echo 'tweet__like_active'?>"><?php echo count(get_likes_count($post['id']));?></a>
          </footer>
        </article>
      </li>
    <?php } ?>
	</ul>
</section>
<?php } else {
  echo "<h2 class='tweet-form__title'>Здесь пока нет твитов ...</h2>";
}?>
