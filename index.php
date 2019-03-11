<?php
require_once('init.php');

$show_completed = $_GET['show_completed'] ?? '';
$user = isset($_SESSION['user']) ? $_SESSION['user'] :'';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$term = isset($_GET['term']) ? $_GET['term'] : 'all';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$tasks_for_category = getAllTasksForCategory($connection, $user_id, $category_id, $term);
$category_list = getCategories($connection, $user_id);
$task_list = getTasks($connection, $user_id);


if(isset($_GET['task_id']) && isset($_GET['check'])) {
    $task_id = (int)$_GET['task_id'];
    toggleTaskStatus($connection, $task_id, $user_id);
    header('Location: /');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = empty($_GET['search']) ? '' : $_GET['search'];
    $tasks_for_category = getTasksBySearch($connection, $user_id, $search);
}

if(!$user) {
    $content = include_template('guest.php', []);
} else {
    $content = include_template('index.php', [
        'show_completed'=> $show_completed,
        'tasks_for_category' => $tasks_for_category,
        'category_id' => $category_id,
        'term' => $term
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'isSignInOrRegister' => false,
    'user' => $user,
    'user_id' => $user_id,
    'category_list' => $category_list,
    'term' => $term,
    'task_list' => $task_list
]);

print($layout_content);
