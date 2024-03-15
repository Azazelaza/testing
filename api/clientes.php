<?php
include_once '../db/connection.php';
include_once '../controller/Clientes.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        Clientes::select($_GET);
        break;
    case 'POST':
        Clientes::create($_POST);
        break;
    case 'PUT':
        Clientes::update(file_get_contents("php://input"));
        break;
    case 'DELETE':
        Clientes::delete(file_get_contents("php://input"));
        break;
}