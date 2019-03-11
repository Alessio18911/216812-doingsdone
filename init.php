<?php
date_default_timezone_set('Europe/Minsk');

require_once('functions.php');
require_once('mysql_helper.php');
session_start();
signOut();

$connection = getConnection('216812-doingsdone', 'root', '', 'doingsdone');
$content = '';
$isSignInOrRegister = false;
