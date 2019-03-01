<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form" action="index.php?addtask" method="post" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>
        <p class="form__message"><?= isset($errors['name']) ? $errors['name'] :''; ?></p>
        <input class="form__input <?= isset($errors['name']) ? 'form__input--error': ''; ?>" type="text" name="name" id="name" value="<?= $new_task; ?>" placeholder="Введите название">
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект</label>
        <select class="form__input form__input--select" name="project" id="project">
            <?php foreach($category_list as $category): ?>
                <option value="<?= $category['id']?>"><?=htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>
        <p class="form__message"><?= isset($errors['date']) ? $errors['date'] :''; ?></p>
        <input class="form__input form__input--date <?= isset($errors['date']) ? 'form__input--error': ''; ?>" type="date" name="date" id="date" value="" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
    </div>

    <div class="form__row">
        <label class="form__label" for="preview">Файл</label>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="preview" id="preview" value="">
            <label class="button button--transparent" for="preview">
                <span>Выберите файл</span>
            </label>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
