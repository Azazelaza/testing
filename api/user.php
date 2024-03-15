<?php
include_once '../db/connection.php';
include_once '../controller/User.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        User::checkLogin($_GET);
        break;
    case 'POST':
        User::login($_POST);
        break;
}

