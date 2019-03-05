<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="main.php?main&addproject" method="post">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>
        <p class="form__message"><?= isset($errors['name']) ? $errors['name'] :''; ?></p>
        <input class="form__input <?= isset($errors['name']) ? 'form__input--error' :''; ?>" type="text" name="name" id="project_name" value="" placeholder="Введите название проекта">
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
