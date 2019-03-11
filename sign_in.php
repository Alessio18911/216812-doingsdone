<?php
require_once('init.php');

$user = '';
$user_id = 0;
$email = '';
$password = '';
$errors = [];

if(!empty($_SESSION)) {
    header("Location: /");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : $email;
    $password = isset($_POST['password']) ? $_POST['password'] : $password;
    $errors = validateAuthForm($connection, $email, $password, $errors);

    if(!count($errors)) {
        $_SESSION['user'] = getUserByEmail($connection, $email)[0]['name'];
        $_SESSION['user_id'] = getUserByEmail($connection, $email)[0]['id'];
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
    'user' => $user,
    'user_id' => $user_id
]);

print($layout_content);
