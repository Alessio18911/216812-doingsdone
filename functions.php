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

function countTasks(string $category, array $task_list): int {
    $tasks_sum = 0;

    foreach($task_list as $task) {
        if($category === $task['category']) {
            $tasks_sum++;
        }
    }
    return $tasks_sum;
}
?>
