<?php
require_once('init.php');

$user = '';
$email = '';
$password = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = validateAuthForm($connection, $errors);

    if(!count($errors)) {
        $_SESSION['user'] = $user = getUserByEmail($connection, $email)[0]['name'];
        header("Location: /");
        exit();
    }
}

$content = include_template('sign_in.php', [
    'email' => $email,
    'password' => $password,
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'isSignInOrRegister' => true,
    'user' => $user
]);

print($layout_content);
