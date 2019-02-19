<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('functions.php');
require_once('mysql_helper.php');

$сonnection = getConnection('216812-doingsdone', 'root', '', 'doingsdone');
$category_list = getTaskCategories($сonnection, 1);
$task_list = getTaskList($сonnection, 1);
$tasks_for_category = switchCategory($сonnection, 1);

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

?>
