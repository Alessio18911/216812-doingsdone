<?php
require_once('init.php');

$category_list = getCategories($connection, 1);
$task_list = getTasks($connection, 1);
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_field = empty($_POST['name']) ? '' : $_POST['name'];
    $errors = validateCategoryForm($connection, 1, $required_field, $errors);

    if(!count($errors)) {
        addCategory($connection, 1, $required_field);
        header("Location: /");
        exit();
    }
}

$content = include_template('add_project.php', [
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'task_list' => $task_list,
    'isSignInOrRegister' => false,
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'user' => $user
]);

print($layout_content);
