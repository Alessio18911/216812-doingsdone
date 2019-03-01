<?php

function include_template(string $name, array $data): string {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function countTasks(string $category_name, array $task_list): int {
    $tasks_sum = 0;

    foreach($task_list as $task) {
        if($category_name === $task['categories_name']) {
            $tasks_sum++;
        }
    }
    return $tasks_sum;
}

function isTaskExpired(string $end_date = null): bool {
    if(!$end_date) {
        return false;
    }

    $current_date = time();
    $expiry_date = strtotime($end_date) + 86400;
    $time_to_expiry = floor(($expiry_date - $current_date)/3600);

    return $time_to_expiry <= 48 && $time_to_expiry > 0;
}

function formatDate(string $date = null): string {
    if (null === $date) {
        return '';
    }

    $date = date_create($date);
    return date_format($date, 'd.m.Y');
}

function savePostedFile(array $file): ?string {
    if(!$file['name']) {
        return null;
    }

    $destination = 'img/' . $file['name'];
    move_uploaded_file($file['tmp_name'], $destination);
    return $destination;
}

function validateTaskForm(string $required_field, ?string $expires_at, array $errors): array {
    if(!$required_field) {
        $errors['name'] =  'Это поле нужно заполнить!';
    }

    if($expires_at) {
        $now = time();
        $expiry_date = strtotime($expires_at) + 86400;

        if($expiry_date < $now) {
            $errors['date']=  'Установите правильную дату!';
        }
    }

    return $errors;
}

function validateCategoryForm($link, int $user_id, string $required_field, array $errors): array {
    if(!$required_field) {
        $errors['name'] =  'Это поле нужно заполнить!';
        return $errors;
    }

    $new_category = isCategory($link, $user_id, $required_field);

    if($new_category) {
        $errors['name'] = 'Выберите другое имя!';
    }

    return $errors;
}

function validateRegisterForm($link, array $required_fields, array $post, array $errors): array {
    foreach($required_fields as $field) {
        if(!$post[$field]) {
            $errors[$field] = "Это поле должно быть заполнено!";
        }

        if($post[$field] && $field === 'email') {
            if(!filter_var($post[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "Email введён некорректно";
            } elseif(isEmailExists($link, $post[$field])) {
                $errors[$field] = "Данный email уже занят. Введите другой email";
            }
        }
    }

    if(count($errors)) {
        $errors['main'] = 'Пожалуйста, исправьте ошибки в форме';
    }

    return $errors;
}

function validateAuthForm($link, array $post, array $errors): array {
    if($post['email']) {
        if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email введён некорректно";
        } elseif(!isEmailExists($link, $post['email'])) {
            $errors['email'] = "Пользователь с введённым email отсутствует";
        }
    } else {
        $errors['email'] = "Это поле нужно заполнить!";
    }

    if($post['password'] && $post['email']) {
        $password = isPasswordExists($link, $post['email'], $password);
        if(!$password) {
            $errors['password'] = "Пароль неверный";
        }
    } elseif($post['password'] && !$post['email']) {
        $errors['password'] = "Вы забыли заполнить поле email";
    } else {
        $errors['password'] = "Это поле нужно заполнить!";
    }

    return $errors;
}
