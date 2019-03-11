<?php
require_once('init.php');

$user = isset($_SESSION['user']) ? $_SESSION['user'] :'';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$category_list = getCategories($connection, $user_id);
$term = isset($_GET['term']) ? $_GET['term'] : 'all';
$task_list = getTasks($connection, $user_id);
$required_field = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_field = trim($_POST['name']);
    $category_id = empty($_POST['project']) ? 0 : (int)$_POST['project'];
    $expires_at = empty($_POST['date']) ? null : date_format(date_create($_POST['date']), 'Y-m-d');
    $errors = validateTaskForm($required_field, $expires_at, $errors);

    if(!count($errors)) {
        $destination = savePostedFile($_FILES['preview']) ?? '';
        addTask($connection, $user_id, $category_id, $required_field, $expires_at, $destination);
        header("Location: /");
        exit();
    }
}

if($user) {
    $content = include_template('add.php', [
        'category_list' => $category_list,
        'required_field' => $required_field,
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
