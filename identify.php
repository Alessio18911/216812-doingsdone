<?php

require_once('functions.php');
require_once('mysql_helper.php');

$connection = getConnection('216812-doingsdone', 'root', '', 'doingsdone');

$content = include_template('register.php', []);

$layout_content = include_template('identify_layout.php', [
    'content' => $content,
    'page_title' => "Дела в порядке: регистрация"
]);

print($layout_content);
