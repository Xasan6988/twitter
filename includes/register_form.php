<section class="wrapper">
				<h2 class="tweet-form__title"><?php echo $title?></h2>
				<?php if ($error) {?>
				<div class="tweet-form__error"><?php echo $error?></div>
				<?php }?>
				<form class="tweet-form" action="<?php echo getUrl('./includes/sign_up.php');?>" method="post">
					<div class="tweet-form__wrapper_inputs">
						<input type="text" class="tweet-form__input" placeholder="Логин" required name="login">
						<input type="password" class="tweet-form__input" placeholder="Пароль" required name="pass">
						<input type="password" class="tweet-form__input" placeholder="Пароль повторно" required name="pass2">
					</div>
					<div class="tweet-form__btns_center">
						<button class="tweet-form__btn_center" type="submit">Зарегистрироваться</button>
					</div>
				</form>
			</section>
