<?php
require_once "controllers/skillController.php";

if(!isset($_REQUEST["id"])){
    header('location:index.php?table=skill&action=list');
    exit();
}

$skillId = $_REQUEST["id"];

$controller = new SkillController();
$controller->delete($skillId);