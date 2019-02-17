<?php
$getConnection = getConnection('216812-doingsdone', 'root', '', 'doingsdone');
$category_list = getTaskCategories($getConnection, 1);
$task_list = getTaskList($getConnection, 1);

//Установить соединение с БД
function getConnection($dB_localhost, $dB_user, $dB_root, $dB_title) {
    $link = mysqli_connect($dB_localhost, $dB_user, $dB_root, $dB_title);

    if(!$link) {
        print("Ошибка подключения: " . mysqli_connect_error());
        die();
    } else {
        mysqli_set_charset($link, 'utf8');
        return $link;
    }
}

//Получить список категорий
function getTaskCategories ($link, int $user_id): array {
    $sql_cat = "SELECT categories.name FROM categories
                JOIN users ON categories.user_id = users.id
                WHERE users.id = $user_id";

    $sql_cat = mysqli_query($link, sprintf($sql_cat, $user_id));

    if(!$sql_cat) {
        print("Ошибка MySQL: " . mysqli_error($link));
        die();
    } else {
        $category_list = mysqli_fetch_all($sql_cat, MYSQLI_ASSOC);
        return $category_list;
    }
}

//Получить список всех задач
function getTaskList($link, int $user_id): array {
    $sql_tasks = "SELECT tasks.name, tasks.created_at, tasks.expires_at, categories.name AS categories_name, status FROM tasks
        JOIN categories ON tasks.category_id = categories.id
        JOIN users ON categories.user_id = users.id
        WHERE users.id = $user_id";

    $sql_tasks = mysqli_query($link, sprintf($sql_tasks, $user_id));
    if(!$sql_tasks) {
        print("Ошибка MySQL: " . mysqli_error($link));
        die();
    } else {
        $task_list = mysqli_fetch_all($sql_tasks, MYSQLI_ASSOC);
        return $task_list;
    }
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}
