<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('init.php');

$category_list = getCategories($connection, 1);
$task_list = getTasks($connection, 1);

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

if (null !== $category_id && !isCategoryExists($connection, 1, $category_id)) {
    die(http_response_code(400));
}

$tasks_for_category = getTasksForCategory($connection, 1, $category_id);

if(isset($_GET['addtask'])) {
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_field = $post['name'];
        $category_id = $post['project'];
        $expires_at = empty($post['date']) ? null : date_format(date_create($post['date']), 'Y-m-d');
        $destination = savePostedFile($files['preview']) ? savePostedFile($files['preview']) : '';
        $errors = validateTaskForm($required_field, $expires_at, $errors);

        if(!count($errors)) {
            addTask($connection, 1, $category_id, $required_field, $expires_at, $destination);
            header("Location: /");
            exit();
        }
    }

    $new_task = isset($post['name']) ? $post['name'] : '';
    $content = include_template('add.php', [
        'category_list' => $category_list,
        'new_task' => $new_task,
        'errors' => $errors
    ]);
}
elseif(isset($_GET['addproject'])) {
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_field = empty($post['name']) ? '' : $post['name'];
        $errors = validateCategoryForm($connection, 1, $required_field, $errors);

        if(!count($errors)) {
            addCategory($connection, 1, $required_field);
            header("Location: /");
            exit();
        }
    }

    $content = include_template('add_project.php', [
        'errors' => $errors
    ]);
}
else {
    $content = include_template('index.php', [
        'tasks_for_category' => $tasks_for_category,
        'show_complete_tasks' => $show_complete_tasks
    ]);
}

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'task_list' => $task_list,
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'user' => 'Глупый король'
]);

print($layout_content);
