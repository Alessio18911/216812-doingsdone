<?php

require_once('functions.php');
require_once('mysql_helper.php');

$content = '';

if(isset($_GET['register'])) {
    $content = include_template('templates/register', []);
} else if(isset($_GET['authorize'])) {
    $content = include_template('template/authorize', []);
}

$layout_content = include_template('identification_layout.php', [
    'content' => $content
])

print($layout_сontent);
