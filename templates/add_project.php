<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="index.php?addproject" method="post">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>
        <p class="form__message"><?= !empty($errors['name']) ? $errors['name'] :''; ?></p>
        <input class="form__input <?= !empty($errors['name']) ? 'form__input--errors' :''; ?>" type="text" name="name" id="project_name" value="<?= $add_category; ?>" placeholder="Введите название проекта">
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
