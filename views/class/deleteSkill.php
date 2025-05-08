<?php
require_once "controllers/classController.php";

if(!isset($_REQUEST["id"], $_REQUEST["skillId"])){
    header('location:index.php?table=class&action=list');
    exit();
}

$classId = $_REQUEST["id"];
$skillId = $_REQUEST["skillId"];

$controller = new ClassController();
$controller->removeSkill($classId, $skillId);