<h2 class="content__main-heading">Регистрация аккаунта</h2>
<form class="form" action="register.php" method="post">
    <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>
        <input class="form__input <?= isset($errors['email']) ? 'form__input--error' : ''; ?>" type="text" name="email" id="email" value="<?= isset($errors['email']) ? '' : $email; ?>" placeholder="Введите e-mail">
        <p class="form__message"><?= isset($errors['email']) ? $errors['email'] : ''; ?></p>
    </div>

    <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>
        <input class="form__input <?= isset($errors['password']) ? 'form__input--error' : ''; ?>" type="password" name="password" id="password" value="<?= $password; ?>" placeholder="Введите пароль">
        <p class="form__message"><?= isset($errors['password']) ? $errors['password'] : ''; ?></p>
    </div>

    <div class="form__row">
        <label class="form__label" for="name">Имя <sup>*</sup></label>
        <input class="form__input <?= isset($errors['name']) ? 'form__input--error' : ''; ?>" type="text" name="name" id="name" value="<?= $user_name; ?>" placeholder="Введите имя">
        <p class="form__message"><?= isset($errors['name']) ? $errors['name'] : ''; ?></p>
    </div>

    <div class="form__row form__row--controls">
        <p class="error-message"><?= isset($errors['main']) ? $errors['main'] : ''; ?></p>
        <input class="button" type="submit" name="" value="Зарегистрироваться">
    </div>
</form>
