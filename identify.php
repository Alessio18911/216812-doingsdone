<?php

require_once('init.php');

if(isset($_GET['register'])) {
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

    $email = empty($post['email']) ? '' : $post['email'];
    $password = empty($post['password']) ? '' : $post['password'];
    $user_name = empty($post['name']) ? '' : $post['name'];

    $content = include_template('register.php', [
        'errors' => $errors,
        'email' => $email,
        'password' => $password,
        'user_name' => $user_name
    ]);

}
else if(isset($_GET['auth'])) {
    $content = include_template('auth.php', []);
}

$layout_content = include_template('identify_layout.php', [
    'content' => $content,
    'page_title' => "Дела в порядке: регистрация"
]);

print($layout_content);
