<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item <?= $all_tasks ? 'tasks-switch__item--active' :''; ?>">Все задачи</a>
        <a href="/?term=today" class="tasks-switch__item <?= $today_tasks ? 'tasks-switch__item--active' :''; ?>">Повестка дня</a>
        <a href="/?term=tomorrow" class="tasks-switch__item <?= $tomorrow_tasks ? 'tasks-switch__item--active' :''; ?>">Завтра</a>
        <a href="/?term=overdue" class="tasks-switch__item <?= $overdue_tasks ? 'tasks-switch__item--active' :''; ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?=$show_completed ? 'checked':''; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach($tasks_for_category as $task): ?>
    <?php if(!$task['status']): ?>
    <tr class="tasks__item task <?=isTaskExpired($task['expires_at']) ? 'task--important':''; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?= $task['id']; ?>">
                <span class="checkbox__text"><?=htmlspecialchars($task['name']); ?></span>
            </label>
        </td>

        <td class="task__file">
            <?php if(!empty($task['file_path'])): ?>
                <a class="download-link" href="<?= $task['file_path']; ?>"><?= $task['file_path']; ?></a>
            <?php endif; ?>
        </td>

        <td class="task__date"><?=formatDate($task['expires_at']); ?></td>
    </tr>

    <?php elseif($show_completed && $task['status']): ?>
    <tr class="tasks__item task <?=$task['status'] ? 'task--completed':''; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?= $task['id']; ?>" checked>
                <span class="checkbox__text"><?=htmlspecialchars($task['name']); ?></span>
            </label>
        </td>

        <td class="task__file">
            <?php if(isset($task['file_path'])): ?>
                <a class="download-link" href="<?= $task['file_path']; ?>"><?= $task['file_path']; ?></a>
            <?php endif; ?>
        </td>

        <td class="task__date"><?=formatDate($task['expires_at']); ?></td>
    </tr>
    <? endif ?>
    <? endforeach; ?>
</table>
