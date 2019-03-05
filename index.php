<?php
require_once('init.php');

$content = include_template('guest.php', []);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'page_title' => 'Дела в порядке'
]);

print($layout_content);
