<?php
require_once "controllers/classController.php";

if(!isset($_REQUEST["id"])){
    header('location:index.php?table=class&action=list');
    exit();
}

$classId = $_REQUEST["id"];
$classController = new ClassController();
$class = $classController->read($classId);

if($class == null){
    header("location:index.php?table=class&action=list");
    exit();
}

$classController->delete($classId);