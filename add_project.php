<?php
require_once('init.php');

$user = isset($_SESSION['user']) ? $_SESSION['user'] :'';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$category_list = getCategories($connection, $user_id);
$term = isset($_GET['term']) ? $_GET['term'] : 'all';
$task_list = getTasks($connection, $user_id);
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_field = empty($_POST['name']) ? '' : trim($_POST['name']);
    $errors = validateCategoryForm($connection, $user_id, $required_field, $errors);

    if(!count($errors)) {
        addCategory($connection, $user_id, $required_field);
        header("Location: /");
        exit();
    }
}

if($user) {
    $content = include_template('add_project.php', [
        'errors' => $errors
    ]);
}

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'term' => $term,
    'task_list' => $task_list,
    'isSignInOrRegister' => false,
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'user' => $user,
    'user_id' => $user_id
]);

print($layout_content);
