<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

//Подключение файлов на страницу
require_once('functions.php');
require_once('mysql_helper.php');

//Сборка главной страницы
$page_content = include_template('index.php', [
    'task_list' => $task_list,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'task_list' => $task_list,
    'content' => $page_content,
    'page_title' => 'Дела в порядке',
    'user' => 'Константин',

]);

print($layout_content);

?>
