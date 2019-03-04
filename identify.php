<?php

require_once('init.php');

if(isset($_GET['register'])) {
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
            header("Location: /index.php?main");
            exit();
        }
    }

    $content = include_template('register.php', [
        'email' => $email,
        'password' => $password,
        'user_name' => $user_name,
        'errors' => $errors
    ]);

}
else if(isset($_GET['auth'])) {
    $email = '';
    $password = '';
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errors = validateAuthForm($connection, $errors);

        if(!count($errors)) {
            header("Location: /index.php?main");
            exit();
        }
    }

    $content = include_template('auth.php', [
        'email' => $email,
        'password' => $password,
        'errors' => $errors,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'page_title' => 'Дела в порядке'
]);

print($layout_content);
