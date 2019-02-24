<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('functions.php');
require_once('mysql_helper.php');

$connection = getConnection('216812-doingsdone', 'root', '', 'doingsdone');
$category_list = getTaskCategories($connection, 1);
$task_list = getTaskList($connection, 1);

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

if (null !== $category_id && !isCategoryExists($connection, 1, $category_id)) {
    die(http_response_code(400));
}

$tasks_for_category = getTasksForCategory($connection, 1, $category_id);

$field = $_POST['name'] ?? '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(empty($field)) {
        $errors['name'] = 'Это поле нужно заполнить';
    }

    if(count($errors)) {
        header("Location: /index.php?task=add");
    } else {
        header("Location: /");
    }
}

$add_task = include_template('add.php', [
    'category_list' => $category_list,
    'errors' => $errors
]);

$page_content = include_template('index.php', [
    'tasks_for_category' => $tasks_for_category,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'task_list' => $task_list,
    'content' => $page_content,
    'add_task' => $add_task,
    'page_title' => 'Дела в порядке',
    'user' => 'Глупый король',

]);

print($layout_content);
