<?php
require_once "controllers/classController.php";

if(!isset($_REQUEST["id"])){
    header('location:index.php?table=class&action=list');
    exit();
}

$classId = $_REQUEST["id"];

$controller = new ClassController();
$controller->delete($classId);