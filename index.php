<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('functions.php');
require_once('mysql_helper.php');

$connection = getConnection('php8-doingsdone-alexey_mysql', 'root', 'password', 'doingsdone');
$category_list = getTaskCategories($connection, 1);
$task_list = getTaskList($connection, 1);

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

if (null !== $category_id && !isCategoryExists($connection, 1, $category_id)) {
    die(http_response_code(400));
}

$tasks_for_category = getTasksForCategory($connection, 1, $category_id);

$page_content = include_template('index.php', [
    'tasks_for_category' => $tasks_for_category,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'task_list' => $task_list,
    'content' => $page_content,
    'page_title' => 'Дела в порядке',
    'user' => 'Глупый король',

]);

print($layout_content);
