<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('functions.php');
require_once('mysql_helper.php');

$connection = getConnection('216812-doingsdone', 'root', '', 'doingsdone');
$category_list = getCategories($connection, 1);
$task_list = getTasks($connection, 1);

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

if (null !== $category_id && !isCategoryExists($connection, 1, $category_id)) {
    die(http_response_code(400));
}

$tasks_for_category = getTasksForCategory($connection, 1, $category_id);
$post = $_POST;
$files = $_FILES;

$content = '';

if(isset($_GET['addtask'])) {
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_field = empty($post['name']) ? '' : $post['name'];
        $category_id = $post['project'];
        $expires_at = empty($post['date']) ? null : date_format(date_create($post['date']), 'Y-m-d');
        $destination = save_posted_file($files['preview']) ? save_posted_file($files['preview']) : '';
        $errors = validateTaskForm($required_field, $expires_at, $errors);

        if(!count($errors)) {
            add_task($connection, 1, $category_id, $required_field, $expires_at, $destination);
            header("Location: /");
            exit();
        }
    }

    $new_task = isset($required_field) ? $required_field : '';
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
            add_category($connection, 1, $required_field);
            header("Location: /");
            exit();
        }
    }

    $new_category = isset($required_field) ? $required_field : '';
    $content = include_template('add_project.php', [
        'new_category' => $new_category,
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
