<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
// $category_list = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
// $task_list = [
//     [
//         'title' => 'Собеседование в IT компании',
//         'expiry_date' => '10.02.2019',
//         'category' => 'Работа',
//         'status' => false
//     ],
//     [
//         'title' => 'Выполнить тестовое задание',
//         'expiry_date' => '25.12.2019',
//         'category' => 'Работа',
//         'status' => false
//     ],
//     [
//         'title' => 'Сделать задание первого раздела',
//         'expiry_date' => '21.12.2019',
//         'category' => 'Учеба',
//         'status' => true
//     ],
//     [
//         'title' => 'Встреча с другом',
//         'expiry_date' => '22.12.2019',
//         'category' => 'Входящие',
//         'status' => false
//     ],
//     [
//         'title' => 'Купить корм для кота',
//         'expiry_date' => '',
//         'category' => 'Домашние дела',
//         'status' => false
//     ],
//     [
//         'title' => 'Заказать пиццу',
//         'expiry_date' => '',
//         'category' => 'Домашние дела',
//         'status' => false
//     ]
// ];

require_once('functions.php');

$link = mysqli_connect('216812-doingsdone', 'root', '', 'doingsdone');
mysqli_set_charset($link, 'utf8');

$user_id = '1';
$sql_proj = 'SELECT projects.title FROM projects
                JOIN users ON projects.user_id = users.id
                WHERE users.id = %s ';

$sql_proj = mysqli_query($link, sprintf($sql_proj, $user_id));

if($sql_proj) {
    $category_list = mysqli_fetch_all($sql_proj, MYSQLI_ASSOC);
} else {
    print("Ошибка MySQL: " . mysqli_error($link));
}

$sql_tasks = 'SELECT tasks.name, tasks.created_at, tasks.expires_at, projects.title AS project_title, status FROM tasks
                JOIN projects ON tasks.project_id = projects.id
                JOIN users ON projects.user_id = users.id
                WHERE users.id = %s ';

$sql_tasks = mysqli_query($link, sprintf($sql_tasks, $user_id));

if($sql_tasks) {
    $task_list = mysqli_fetch_all($sql_tasks, MYSQLI_ASSOC);
} else {
    print("Ошибка MySQL: " . mysqli_error($link));
}

$page_content = include_template('index.php', [
    'task_list' => $task_list,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'category_list' => $category_list,
    'task_list' => $task_list,
    'content' => $page_content,
    'page_title' => 'Дела в порядке',
    'user' => 'Константин',

]);

print($layout_content);

?>
