<?php
require_once('init.php');

$show_complete_tasks = rand(0, 1);
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$tasks_for_category = getTasksForCategory($connection, 1, $category_id);
$category_list = getCategories($connection, 1);
$task_list = getTasks($connection, 1);

// if(isset($_GET['task_id']) && isset($_GET['check'])) {

// }

if(!$isAuth) {
    $content = include_template('guest.php', []);
} else {
    $content = include_template('index.php', [
        'show_complete_tasks'=> $show_complete_tasks,
        'tasks_for_category' => $tasks_for_category
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'isGuest' => !$isAuth,
    'isSignInOrRegister' => false,
    'user' => 'Глупый король',
    'category_list' => $category_list,
    'task_list' => $task_list
]);

print($layout_content);
