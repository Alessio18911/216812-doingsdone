<?php

require_once('functions.php');
require_once('mysql_helper.php');

$connection = getConnection('216812-doingsdone', 'root', '', 'doingsdone');

$content = include_template('register.php', []);;

if(isset($_GET['register'])) {
    $content = include_template('register.php', []);
} else if(isset($_GET['authorize'])) {
    $content = include_template('authorize.php', []);
}

$layout_content = include_template('identify_layout.php', [
    'content' => $content
]);

print($layout_content);
