<?php
require_once "controllers/skillController.php";

if(!isset($_REQUEST["id"])){
    header('location:index.php?table=skill&action=list');
    exit();
}

$skillId = $_REQUEST["id"];
$skillController = new SkillController();
$skill = $skillController->read($skillId);

if($skill == null){
    header("location:index.php?table=skill&action=list");
    exit();
}

$skillController->delete($skillId);