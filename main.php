<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('init.php');

$category_list = getCategories($connection, 1);
$task_list = getTasks($connection, 1);

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

if (null !== $category_id && !isCategoryExists($connection, 1, $category_id)) {
    die(http_response_code(400));
}

$tasks_for_category = getTasksForCategory($connection, 1, $category_id);

// if(isset($_GET['task_id']) && isset($_GET['check'])) {

// }


if(isset($_GET['addtask'])) {
    $required_field = '';
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_field = $_POST['name'];
        $category_id = $_POST['project'];
        $expires_at = empty($_POST['date']) ? null : date_format(date_create($_POST['date']), 'Y-m-d');
        $destination = savePostedFile($_FILES['preview']) ?? '';
        $errors = validateTaskForm($required_field, $expires_at, $errors);

        if(!count($errors)) {
            addTask($connection, 1, $category_id, $required_field, $expires_at, $destination);
            header("Location: /main.php?main");
            exit();
        }
    }

    $content = include_template('add.php', [
        'category_list' => $category_list,
        'required_field' => $required_field,
        'errors' => $errors
    ]);
}
elseif(isset($_GET['addproject'])) {
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $required_field = empty($_POST['name']) ? '' : $_POST['name'];
        $errors = validateCategoryForm($connection, 1, $required_field, $errors);

        if(!count($errors)) {
            addCategory($connection, 1, $required_field);
            header("Location: /main.php?main");
            exit();
        }
    }

    $content = include_template('add_project.php', [
        'errors' => $errors
    ]);
}
else if(isset($_GET['main'])) {
    $content = include_template('index.php', [
        'show_complete_tasks'=> $show_complete_tasks,
        'tasks_for_category' => $tasks_for_category
    ]);
}

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'task_list' => $task_list,
    'content' => $content,
    'page_title' => 'Дела в порядке',
    'user' => 'Глупый король'
]);

print($layout_content);
