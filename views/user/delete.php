<?php
require_once "controllers/userController.php";

if(!isset($_REQUEST["id"])){
    header('Location:index.php?table=user&action=list');
    exit();
}

$userId = $_REQUEST["id"];

$controller = new UserController();
$controller->delete($userId);