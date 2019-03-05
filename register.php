<?php
require_once('init.php');

$email = '';
$password = '';
$user_name = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['email', 'password', 'name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_name = $_POST['name'];
    $errors = validateRegisterForm($connection, $required_fields, $errors);

    if(!count($errors)) {
        addUser($connection, $user_name, $password, $email);
        header("Location: /");
        exit();
    }
}

$content = include_template('register.php', [
    'email' => $email,
    'password' => $password,
    'user_name' => $user_name,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'isGuest' => !$isAuth,
    'isSignInOrRegister' => true,
]);

print($layout_content);
