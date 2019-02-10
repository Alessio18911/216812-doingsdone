<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$category_list = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$task_list = [
    [
        'title' => 'Собеседование в IT компании',
        'expiry_date' => '01.12.2019',
        'category' => 'Работа',
        'status' => false
    ],
    [
        'title' => 'Выполнить тестовое задание',
        'expiry_date' => '25.12.2019',
        'category' => 'Работа',
        'status' => false
    ],
    [
        'title' => 'Сделать задание первого раздела',
        'expiry_date' => '21.12.2019',
        'category' => 'Учеба',
        'status' => true
    ],
    [
        'title' => 'Встреча с другом',
        'expiry_date' => '22.12.2019',
        'category' => 'Входящие',
        'status' => false
    ],
    [
        'title' => 'Купить корм для кота',
        'expiry_date' => '',
        'category' => 'Домашние дела',
        'status' => false
    ],
    [
        'title' => 'Заказать пиццу',
        'expiry_date' => '',
        'category' => 'Домашние дела',
        'status' => false
    ]
];

function countTasks(string $category, array $task_list): int {
    $tasks_sum = 0;

    foreach($task_list as $task) {
        if($category === $task['category']) {
            $tasks_sum++;
        }
    }
    return $tasks_sum;
}

require_once('functions.php');

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
