<?php
date_default_timezone_set('Europe/Minsk');

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
    $expiry_date = strtotime($end_date);
    $time_to_expiry = floor(($expiry_date - $current_date)/3600);

    return $time_to_expiry <= 24 && $time_to_expiry > 0;
}

function formatDate(string $date = null): string {
    if (null === $date) {
        return '';
    }

    $date = date_create($date);
    return date_format($date, 'd.m.Y');
}

function validateFields($required_fields, $post, $errors) {
    foreach($required_fields as $field) {
        if(empty($post[$field])) {
            $errors[$field] = "Это поле нужно заполнить";
        }
    }

    return $errors;
}

function validateFiles($files, $error_files) {
    foreach($files as $key => $file) {
        if($file['name'] && $file['type'] !== 'application/octet-stream') {
            $error_files[$key] = "Файл должен иметь расширение .psd";
            continue;
        }

        if($file['name']) {
            processFiles($file);
        }
    }

    return $error_files;
}

function processFiles($file) {
    $file_name = $file['name'];
    $file_path = __DIR__ . '\\' . $file_name;
    $file_url = '\\' . $file_name;

    move_uploaded_file($file['tmp_name'], $file_path);
    return $file_path;
}
