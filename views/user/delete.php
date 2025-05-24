<?php
require_once "controllers/userController.php";

if(!isset($_REQUEST["id"])){
    header('Location:index.php?table=user&action=list');
    exit();
}

$userId = $_REQUEST["id"];
$userController = new UserController();
$user = $userController->read($userId);

if($user == null){
    header("location:index.php?table=user&action=list");
    exit();
}

$userController->delete($userId);