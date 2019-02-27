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
$errors = [];

$content = '';

if(isset($_GET['addtask'])) {
    $required_fields = ['name'];
    $add_task = !empty($post['name']) ? $post['name'] : '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validateFields($required_fields, $post, $errors);

        if(!count($errors)) {
            add_task($connection, $post, $files, 1);
            header("Location: /");
            exit();
        }
    }

    $content = include_template('add.php', [
        'category_list' => $category_list,
        'add_task' => $add_task,
        'errors' => $errors,
        'files' => $files
    ]);
} elseif(isset($_GET['addproject'])) {
    $required_fields = ['name'];
    $add_category = !empty($post['name']) ? $post['name'] : '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validateFields($required_fields, $post, $errors);

        if(!count($errors)) {
            add_category($connection, $post, 1);
            header("Location: /");
            exit();
        }
    }

    $content = include_template('add_project.php', [
        'add_category' => $add_category,
        'errors' => $errors
    ]);
} else {
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
