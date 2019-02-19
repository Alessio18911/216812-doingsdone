<?php
function getConnection(string $host, string $user, string $password, string $database) {
    $link = mysqli_connect($host, $user, $password, $database);

    if(!$link) {
        die("Ошибка подключения: " . mysqli_connect_error());
    }

    mysqli_set_charset($link, 'utf8');
    mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    return $link;
}

function getTaskCategories ($link, int $user_id): array {
    $sql_cat = "SELECT categories.name FROM categories
                JOIN users ON categories.user_id = users.id
                WHERE users.id = $user_id";

    $result = mysqli_query($link, sprintf($sql_cat, $user_id));

    if(!$result) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getTaskList($link, int $user_id): array {
    $sql_tasks = "SELECT tasks.name, tasks.created_at, tasks.expires_at, categories.name AS categories_name, status FROM tasks
        JOIN categories ON tasks.category_id = categories.id
        JOIN users ON categories.user_id = users.id
        WHERE users.id = $user_id";

    $result = mysqli_query($link, sprintf($sql_tasks, $user_id));
    if(!$result) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
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
