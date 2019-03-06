<?php
require_once('init.php');

$category_list = getCategories($connection, $user_id);
$task_list = getTasks($connection, $user_id);
$required_field = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_field = $_POST['name'];
    $category_id = $_POST['project'];
    $expires_at = empty($_POST['date']) ? null : date_format(date_create($_POST['date']), 'Y-m-d');
    $destination = savePostedFile($_FILES['preview']) ?? '';
    $errors = validateTaskForm($required_field, $expires_at, $errors);

    if(!count($errors)) {
        addTask($connection, $user, $category_id, $required_field, $expires_at, $destination);
        header("Location: /");
        exit();
    }
}

$content = include_template('add.php', [
    'category_list' => $category_list,
    'required_field' => $required_field,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'isSignInOrRegister' => false,
    'user' => $user,
    'category_list' => $category_list,
    'task_list' => $task_list
]);

print($layout_content);
