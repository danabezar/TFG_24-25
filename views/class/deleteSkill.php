<?php
require_once "controllers/classController.php";
require_once "controllers/skillController.php";

if(!isset($_REQUEST["id"], $_REQUEST["skillId"])){
    header('location:index.php?table=class&action=list');
    exit();
}

$classId = $_REQUEST["id"];
$classController = new ClassController();
$class = $classController->read($classId);

$skillId = $_REQUEST["skillId"];
$skillController = new SkillController();
$skill = $skillController->read($skillId);

if($class == null || $skill == null){
    header("location:index.php?table=class&action=list");
    exit();
}

$classController->removeSkill($classId, $skillId);