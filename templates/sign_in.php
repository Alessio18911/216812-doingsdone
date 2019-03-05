<h2 class="content__main-heading">Вход на сайт</h2>

<form class="form" action="authentification.php?identify" method="post">
    <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>
    <input class="form__input <?= isset($errors['email'])? 'form__input--error' :''; ?>" type="text" name="email" id="email" value="<?= isset($errors['email']) ? '': $email; ?>" placeholder="Введите e-mail">
    <p class="form__message"><?= isset($errors['email']) ? $errors['email'] :''; ?></p>
    </div>

    <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>
    <input class="form__input <?= isset($errors['password'])? 'form__input--error' :''; ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">
    <p class="form__message"><?= isset($errors['password']) ? $errors['password'] :''; ?></p>
    </div>

    <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Войти">
    </div>
</form>
