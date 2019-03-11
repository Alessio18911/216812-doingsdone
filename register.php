<?php
require_once('init.php');

$user = '';
$user_id = 0;
$email = '';
$password = '';
$user_name = '';
$errors = [];

if(!empty($_SESSION)) {
    header("Location: /");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : $email;
    $password = isset($_POST['password']) ? trim($_POST['password']) : $password;
    $user_name = isset($_POST['name']) ? trim($_POST['name']) : $user_name;
    $errors = validateRegisterForm($connection, $email, $password, $user_name, $errors);

    if(!count($errors)) {
        $user = $_SESSION;
        addUser($connection, $user_name, $password, $email);
        $_SESSION['user'] = getUserByEmail($connection, $email)[0]['name'];
        $_SESSION['user_id'] = getUserByEmail($connection, $email)[0]['id'];
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
    'isSignInOrRegister' => true,
    'user' => $user,
    'user_id' => $user_id
]);

print($layout_content);
