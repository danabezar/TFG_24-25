<?php
require_once "controllers/userUnitController.php";

if(!isset($_REQUEST["userId"], $_REQUEST["userUnitId"])){
    header('location:index.php?table=user&action=list');
    exit();
}

$userId = $_REQUEST["userId"];
$userUnitId = $_REQUEST["userUnitId"];

$controller = new UserUnitController();
$controller->delete($userId, $userUnitId);