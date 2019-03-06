<?php
require_once('init.php');

$user = '';
$user_id = 0;
$show_complete_tasks = rand(0, 1);
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$tasks_for_category = getTasksForCategory($connection, $user_id, $category_id);
$category_list = getCategories($connection, $user_id);
$task_list = getTasks($connection, $user_id);

if(isset($_GET['task_id']) && isset($_GET['check'])) {
    $task_id = (int)$_GET['task_id'];
    toggleTaskStatus($connection, $task_id, $user);
    header('Location: /');
    exit();
}

if(!$user) {
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
    'isSignInOrRegister' => false,
    'user' => $user,
    'user_id' => $user_id,
    'category_list' => $category_list,
    'task_list' => $task_list
]);

print($layout_content);
