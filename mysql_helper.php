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

function fetchData($link, $stmt): array {
    $result = mysqli_stmt_execute($stmt);

    if(!$result) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

function getCategories($link, int $user_id): array {
    $sql_cat = "SELECT categories.id, categories.name FROM categories
                JOIN users ON categories.user_id = users.id
                WHERE users.id = ?";

    $stmt = db_get_prepare_stmt($link, $sql_cat, [$user_id]);

    return fetchData($link, $stmt);
}

function getTasks($link, int $user_id): array {
    $sql_tasks = "SELECT tasks.name, tasks.created_at, tasks.expires_at, categories.name AS categories_name, status FROM tasks
        JOIN categories ON tasks.category_id = categories.id
        JOIN users ON categories.user_id = users.id
        WHERE users.id = ?";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id]);

    return fetchData($link, $stmt);
}

function isCategoryExists($link, int $user_id, int $category_id): bool {
    $sql = "SELECT name FROM categories WHERE user_id = ? AND id = ?";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $category_id]);

    $result = fetchData($link, $stmt);
    return !empty($result);
}

function isCategory($link, int $user_id, string $category_to_insert): int {
    $sql = "SELECT id FROM categories WHERE user_id = ? AND name = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $category_to_insert]);

    if(!mysqli_stmt_execute($stmt)) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_num_rows($result);
}

function getAllTasksForCategory($link, int $user_id, int $category_id, string $term): array {
    if(!$category_id && $term === "all") {
        $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, status FROM tasks
            JOIN users ON tasks.user_id = users.id
            WHERE users.id = ?
            ORDER BY tasks.created_at DESC";

        $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id]);
        return fetchData($link, $stmt);

    } else if(!$category_id && $term === "today") {
        $term = date('Y-m-d');
        return getTasksForUser($link, '=', $user_id, $term);

    } else if(!$category_id && $term === "tomorrow") {
        $term = date('Y-m-d', strtotime('+1 day'));
        return getTasksForUser($link, '=', $user_id, $term);

    } else if(!$category_id && $term === "overdue") {
        $term = date('Y-m-d');
        return getTasksForUser($link, '<', $user_id, $term);
    } else if($category_id && $term === "all") {
        $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, categories.name AS categories_name, status FROM tasks
            JOIN categories ON tasks.category_id = categories.id
            JOIN users ON categories.user_id = users.id
            WHERE users.id = ? AND categories.id = ?
            ORDER BY tasks.created_at DESC";
        $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $category_id]);
        return fetchData($link, $stmt);

    } else if($category_id && $term === "today") {
        $term = date('Y-m-d');
        return getTasksForCategory($link, '=', $user_id, $category_id, $term);

    } else if($category_id && $term === "tomorrow") {
        $term = date('Y-m-d', strtotime('+1 day'));
        return getTasksForCategory($link, '=', $user_id, $category_id, $term);

    } else if($category_id && $term === "overdue") {
        $term = date('Y-m-d');
        return getTasksForCategory($link, '<', $user_id, $category_id, $term);
    }
}

function getTasksForCategory($link, string $expiresAtOperator, int $user_id, int $category_id, string $term): array {
    $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, status FROM tasks
        JOIN categories ON tasks.category_id = categories.id
        JOIN users ON categories.user_id = users.id
        WHERE users.id = ? AND categories.id = ? AND tasks.expires_at $expiresAtOperator ?
        ORDER BY tasks.created_at DESC";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $category_id, $term]);
    return fetchData($link, $stmt);
}

function getTasksForUser($link, string $expiresAtOperator, int $user_id, string $term): array {
    $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, status FROM tasks
            JOIN users ON tasks.user_id = users.id
            WHERE users.id = ? AND tasks.expires_at $expiresAtOperator ?
            ORDER BY tasks.created_at DESC";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $term]);
    return fetchData($link, $stmt);
}

function addTask($link, int $user_id, string $task_name, ?int $category_id, ?string $expires_at, string $destination) {
    if(null !== $category_id) {
        $sql = "INSERT INTO tasks(user_id, name, category_id, expires_at, file_path) VALUES(?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $task_name, $category_id, $expires_at, $destination]);
        $result = mysqli_stmt_execute($stmt);

        if(!$result) {
            die("Ошибка MySQL: " . mysqli_error($link));
        }
    } else {
        $sql = "INSERT INTO tasks(user_id, name, expires_at, file_path) VALUES(?, ?, ?, ?)";

        $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $task_name, $expires_at, $destination]);
        $result = mysqli_stmt_execute($stmt);

        if(!$result) {
            die("Ошибка MySQL: " . mysqli_error($link));
        }
    }
}

function addCategory($link, int $user_id, string $category_name) {
    $sql = "INSERT INTO categories(user_id, name) VALUES(?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $category_name]);
    $result = mysqli_stmt_execute($stmt);

    if(!$result) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }
}

function getUserByEmail($link, string $email): ?array {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);

    $result = fetchData($link, $stmt);

    if(count($result)) {
        return $result;
    }

    return null;
}

function addUser($link, string $user_name, string $password, string $email) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(name, password, email) VALUES(?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_name, $password, $email]);
    mysqli_stmt_execute($stmt);
}

function getUserPassword($link, string $email): ?string {
    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);

    if(!mysqli_stmt_execute($stmt)) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return $result['password'];
}

function toggleTaskStatus($link, int $task_id, int $user_id) {
    $sql = "SELECT status FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$task_id, $user_id]);
    mysqli_stmt_execute($stmt);
    $status = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['status'];

    if(!$status) {
        $sql = "UPDATE tasks SET status = 1 WHERE id = ? AND user_id = ?";
    } else {
        $sql = "UPDATE tasks SET status = 0 WHERE id = ? AND user_id = ?";
    }

    $stmt = db_get_prepare_stmt($link, $sql, [$task_id, $user_id]);
    mysqli_stmt_execute($stmt);
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
            else if (null === $value) {
                $type = 's';
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
