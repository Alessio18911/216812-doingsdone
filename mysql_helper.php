<?php
/**
 * Подключение к базе данных
 *
 * @param $host string адрес сервера
 * @param $user string имя пользователя для подключения
 * @param $password string пароль пользователя
 * @param $database string имя базы данных для работы
 *
 * @return $link mysqli_connect ресурс соединения
 */

function getConnection(string $host, string $user, string $password, string $database) {
    $link = mysqli_connect($host, $user, $password, $database);

    if(!$link) {
        die("Ошибка подключения: " . mysqli_connect_error());
    }

    mysqli_set_charset($link, 'utf8');
    mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    return $link;
}

/**
 * Получение ассоциативного массива из запроса в БД
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $stmt mysqli_stmt подготовленное выражение
 *
 * @return array ассоциативный массив с результатами запроса
 */

function fetchData($link, $stmt): array {
    $result = mysqli_stmt_execute($stmt);

    if(!$result) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

/**
 * Получение списка проектов для 1 пользователя
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 *
 * @return array ассоциативный массив с проектами
 */

function getCategories($link, int $user_id): array {
    $sql_cat = "SELECT categories.id, categories.name FROM categories
                JOIN users ON categories.user_id = users.id
                WHERE users.id = ?";

    $stmt = db_get_prepare_stmt($link, $sql_cat, [$user_id]);

    return fetchData($link, $stmt);
}

/**
 * Получение списка задач для данного пользователя
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 *
 * @return array ассоциативный массив со списком задач
 */

function getTasks($link, int $user_id): array {
    $sql_tasks = "SELECT tasks.name, tasks.created_at, tasks.expires_at, categories.name AS categories_name, status FROM tasks
        JOIN categories ON tasks.category_id = categories.id
        JOIN users ON categories.user_id = users.id
        WHERE users.id = ?";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id]);

    return fetchData($link, $stmt);
}

/**
 * Проверяет существование у данного пользователя проекта с указанным id
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $category_id int идентификатор проекта
 *
 * @return true в случае пустого массива
 */

function isCategoryExists($link, int $user_id, int $category_id): bool {
    $sql = "SELECT name FROM categories WHERE user_id = ? AND id = ?";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $category_id]);

    $result = fetchData($link, $stmt);
    return !empty($result);
}

/**
 * При попытке создать новый проект проверяет существование проекта с таким же именем у данного пользователя
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $category_to_insert string имя нового проекта
 *
 * @return mysqli_num_rows количество строк полученного массива
 */
function isCategory($link, int $user_id, string $category_to_insert): int {
    $sql = "SELECT id FROM categories WHERE user_id = ? AND name = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $category_to_insert]);

    if(!mysqli_stmt_execute($stmt)) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_num_rows($result);
}

/**
 * Получает список задач для категории с учётом фильтров по срокам
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $$category_id идентификатор проекта
 * @param $term срок закрытия задач
 *
 * @return array массив из задач
 */
function getAllTasksForCategory($link, int $user_id, int $category_id, string $term): array {
    if(!$category_id) {
        if($term === "all") {
            $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, status FROM tasks
                JOIN users ON tasks.user_id = users.id
                WHERE users.id = ?
                ORDER BY tasks.created_at DESC";

            $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id]);
            return fetchData($link, $stmt);

        } else if($term === "today") {
            $term = date('Y-m-d');
            return getTasksForUser($link, '=', $user_id, $term);

        } else if($term === "tomorrow") {
            $term = date('Y-m-d', strtotime('+1 day'));
            return getTasksForUser($link, '=', $user_id, $term);

        } else if($term === "overdue") {
            $term = date('Y-m-d');
            return getTasksForUser($link, '<', $user_id, $term);
        }
    } else {
        if($term === "all") {
            $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, categories.name AS categories_name, status FROM tasks
                JOIN categories ON tasks.category_id = categories.id
                JOIN users ON categories.user_id = users.id
                WHERE users.id = ? AND categories.id = ?
                ORDER BY tasks.created_at DESC";
            $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $category_id]);
            return fetchData($link, $stmt);

        } else if($term === "today") {
            $term = date('Y-m-d');
            return getTasksForCategory($link, '=', $user_id, $category_id, $term);

        } else if($term === "tomorrow") {
            $term = date('Y-m-d', strtotime('+1 day'));
            return getTasksForCategory($link, '=', $user_id, $category_id, $term);

        } else if($term === "overdue") {
            $term = date('Y-m-d');
            return getTasksForCategory($link, '<', $user_id, $category_id, $term);
        }
    }
}

/**
 * Формирует запрос в БД для получения задач для категорий для функции getAllTasksForCategory()
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $category_id идентификатор проекта
 * @param $term срок закрытия задач
 * @param $expiresAtOperator оператор равно/меньше указанного срока
 *
 * @return array массив из задач
 */
function getTasksForCategory($link, string $expiresAtOperator, int $user_id, int $category_id, string $term): array {
    $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, status FROM tasks
        JOIN categories ON tasks.category_id = categories.id
        JOIN users ON categories.user_id = users.id
        WHERE users.id = ? AND categories.id = ? AND tasks.expires_at $expiresAtOperator ?
        ORDER BY tasks.created_at DESC";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $category_id, $term]);
    return fetchData($link, $stmt);
}

/**
 * Получает все задачи для данного пользователя с учётом указанного срока исполнения, нужна для работы функции getAllTasksForCategory()
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $expiresAtOperator string оператор сравнения
 * @param $term string срок исполнения задачи
 *
 * @return array ассоциативный массив с задачами
 */

function getTasksForUser($link, string $expiresAtOperator, int $user_id, string $term): array {
    $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, tasks.expires_at, tasks.file_path, status FROM tasks
            JOIN users ON tasks.user_id = users.id
            WHERE users.id = ? AND tasks.expires_at $expiresAtOperator ?
            ORDER BY tasks.created_at DESC";

    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $term]);
    return fetchData($link, $stmt);
}

/**
 * Получает задачи для данного пользователя по словам в форме поиска
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $search string искомые слова
 *
 * @return array ассоциативный массив с задачами
 */

function getTasksBySearch($link, int $user_id, string $search): array {
    if($search) {
        $sql_tasks = "SELECT tasks.id, tasks.name, tasks.created_at, expires_at, file_path, status FROM tasks
            JOIN users ON tasks.user_id = users.id
            WHERE users.id = ? AND MATCH(tasks.name) AGAINST (?)
            ORDER BY tasks.created_at DESC";

        $stmt = db_get_prepare_stmt($link, $sql_tasks, [$user_id, $search]);
        return fetchData($link, $stmt);
    }

    return [];
}

/**
 * Добавляет новую задачу для данного пользователя
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $task_name string название задачи
 * @param $category_id int идентификатор проекта
 * @param $expires_at string срок выполнения задачи
 * @param $destination string путь к загруженному файлу
 *
 * @return undefined
 */

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

/**
 * Добавляет новый проект для данного пользователя
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_id int идентификатор пользователя
 * @param $category_name string название проекта
 *
 * @return undefined
 */
function addCategory($link, int $user_id, string $category_name) {
    $sql = "INSERT INTO categories(user_id, name) VALUES(?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $category_name]);
    $result = mysqli_stmt_execute($stmt);

    if(!$result) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }
}

/**
 * Проверяет существование пользователя в БД
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $email string email пользователя
 *
 * @return array ассоциативный массив с данными пользователя
 */
function getUserByEmail($link, string $email): ?array {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);

    $result = fetchData($link, $stmt);

    if(count($result)) {
        return $result;
    }

    return null;
}

/**
 * Добавляет нового пользователя в БД
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $user_name string имя пользователя
 * @param $password string пароль пользователя
 * @param $email string email пользователя
 *
 * @return undefined
 */
function addUser($link, string $user_name, string $password, string $email) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(name, password, email) VALUES(?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$user_name, $password, $email]);
    mysqli_stmt_execute($stmt);
}

/**
 * Получает пароль пользователя по email
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $email string email пользователя
 *
 * @return string пароль пользователя
 */
function getUserPassword($link, string $email): ?string {
    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);

    if(!mysqli_stmt_execute($stmt)) {
        die("Ошибка MySQL: " . mysqli_error($link));
    }

    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return $result['password'];
}

/**
 * Переключает статус задачи
 *
 * @param $link mysqli_connect ресурс соединения
 * @param $task_id int идентификатор задачи
 * @param $user_id int идентификатор пользователя
 *
 * @return undefined
 */
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
