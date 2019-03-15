<?php
/**
 * Формирует контент из шаблона для вставки на страницу
 *
 * @param $name string имя файла шаблона
 * @param $data array необходимые для заполнения шаблона переменные
 *
 * @return string $result контент
 */
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

/**
 * Подсчитывает количество задач в 1 проекте
 *
 * @param $category_name string имя проекта
 * @param $task_list array список задач
 *
 * @return int $tasks_sum количество задач
 */
function countTasks(string $category_name, array $task_list): int {
    $tasks_sum = 0;

    foreach($task_list as $task) {
        if($category_name === $task['categories_name']) {
            $tasks_sum++;
        }
    }
    return $tasks_sum;
}

/**
 * Определяет время до закрытия задачи
 *
 * @param $end_date string установленный пользователем срок закрытия задачи
 *
 * @return bool
 */

function isTaskExpired(string $end_date = null): bool {
    if(!$end_date) {
        return false;
    }

    $current_date = time();
    $expiry_date = strtotime($end_date) + 86400;
    $time_to_expiry = floor(($expiry_date - $current_date)/3600);

    return $time_to_expiry <= 48 && $time_to_expiry > 0;
}

/**
 * Форматирует дату после получения из БД перед вставкой её в контент
 *
 * @param $date string установленная пользователем дата
 *
 * @return string дата в формате д.м.гггг
 */
function formatDate(string $date = null): string {
    if (null === $date) {
        return '';
    }

    $date = date_create($date);
    return date_format($date, 'd.m.Y');
}

/**
 * Сохраняет загруженный при добавлении задачи файл на сервер
 *
 * @param $file array сведения о загружаемом файле
 *
 * @return string $destination путь доступа к загруженному файлу
 */
function savePostedFile(array $file): ?string {
    if(!$file['name']) {
        return null;
    }

    $destination = 'img/' . $file['name'];
    move_uploaded_file($file['tmp_name'], $destination);
    return $destination;
}

/**
 * Валидирует форму добавления задачи
 *
 * @param $task_name string название задачи
 * @param $expires_at string срок закрытия задачи
 * @param $errors array массив для сохранения ошибок
 *
 * @return array $errors массив ошибок
 */
function validateTaskForm(string $task_name, ?string $expires_at, array $errors): array {
    if(!$task_name) {
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

/**
 * Валидирует форму добавления проекта
 *
 * @param $link mysqli_connect ресурс соединения с БД
 * @param $user_id int идентификатор пользователя
 * @param $category_name string название проекта
 * @param $errors array массив для сохранения ошибок
 *
 * @return array $errors массив ошибок
 */
function validateCategoryForm($link, int $user_id, string $category_name, array $errors): array {
    if(!$category_name) {
        $errors['name'] =  'Это поле нужно заполнить!';
        return $errors;
    }

    $new_category = isCategory($link, $user_id, $category_name);

    if($new_category) {
        $errors['name'] = 'Проект с данным именем уже существует! Выберите другое имя';
    }

    return $errors;
}

/**
 * Валидирует форму регистрации нового пользователя
 *
 * @param $link mysqli_connect ресурс соединения с БД
 * @param $email string email пользователя
 * @param $password string пароль пользователя
 * @param $user_name string имя пользователя
 * @param $errors array массив для сохранения ошибок
 *
 * @return array $errors массив ошибок
 */
function validateRegisterForm($link, string $email, string $password, string $user_name, array $errors): array {
    if(!$email) {
        $errors['email'] = "Это поле должно быть заполнено!";
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email введён некорректно";
        } else if(getUserByEmail($link, $email)) {
            $errors['email'] = "Данный email уже занят. Введите другой email";
        }
    }

    if(!$password) {
        $errors['password'] = "Это поле должно быть заполнено!";
    }

    if(!$user_name) {
        $errors['name'] = "Это поле должно быть заполнено!";
    }

    if(count($errors)) {
        $errors['main'] = 'Пожалуйста, исправьте ошибки в форме';
    }

    return $errors;
}

/**
 * Валидирует форму аутентификации пользователя
 *
 * @param $link mysqli_connect ресурс соединения с БД
 * @param $email string email пользователя
 * @param $password string пароль пользователя
 * @param $errors array массив для сохранения ошибок
 *
 * @return array $errors массив ошибок
 */
function validateAuthForm($link, string $email, string $password, array $errors): array {
    if($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email введён некорректно";
        } else if(null === getUserByEmail($link, $email)) {
            $errors['email'] = "Пользователь с таким email отсутствует";
        }
    } else {
        $errors['email'] = "Это поле нужно заполнить!";
    }

    if($password && $email) {
        $user_password = getUserPassword($link, $email);
        $result = password_verify($password, $user_password);

        if(!$result) {
            $errors['password'] = "Пароль указан неверно!";
        }

    } else {
        $errors['password'] = "Это поле нужно заполнить!";
    }

    return $errors;
}

/**
 * Осуществляет выход из аккаунта
 *
 * @return undefined
 */
function signOut() {
    if(isset($_GET['exit'])) {
        $_SESSION = [];
        header("Location: /");
        exit();
    }
}
