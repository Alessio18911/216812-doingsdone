<?php
require_once('init.php');

$user = isset($_SESSION['user']) ? $_SESSION['user'] :'';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$category_list = getCategories($connection, $user_id);
$term = isset($_GET['term']) ? $_GET['term'] : 'all';
$task_list = getTasks($connection, $user_id);
$task_name = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = trim($_POST['name']);
    $category_id = isset($_POST['project']) ? $_POST['project'] : '';
    $expires_at = empty($_POST['date']) ? null : date_format(date_create($_POST['date']), 'Y-m-d');
    $errors = validateTaskForm($task_name, $category_id, $expires_at, $errors);

    if(!count($errors)) {
        $destination = savePostedFile($_FILES['preview']) ?? '';
        addTask($connection, $user_id, $category_id, $task_name, $expires_at, $destination);
        header("Location: /");
        exit();
    }
}

if($user) {
    $content = include_template('add.php', [
        'category_list' => $category_list,
        'task_name' => $task_name,
        'errors' => $errors
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
