<?php

require_once('init.php');

if(isset($_GET['register'])) {
    $email = '';
    $password = '';
    $user_name = '';
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_fields = ['email', 'password', 'name'];
        $email = $post['email'];
        $password = $post['password'];
        $user_name = $post['name'];
        $errors = validateRegisterForm($connection, $required_fields, $post, $errors);

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

}
else if(isset($_GET['auth'])) {
    $email = '';
    $password = '';
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $post['email'];
        $password = $post['password'];
        $errors = validateAuthForm($connection, $post, $errors);

        if(!count($errors)) {
            //enter();
            header("Location: /");
            exit();
        }
    }

    $content = include_template('auth.php', [
        'email' => $email,
        'password' => $password,
        'errors' => $errors,
    ]);
}

$layout_content = include_template('identify_layout.php', [
    'content' => $content,
    'page_title' => "Дела в порядке: регистрация"
]);

print($layout_content);
