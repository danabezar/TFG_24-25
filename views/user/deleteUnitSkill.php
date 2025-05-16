<?php
require_once "controllers/userUnitController.php";

if(!isset($_REQUEST["userId"], $_REQUEST["userUnitId"], $_REQUEST["skillId"])){
    header('location:index.php?table=user&action=list');
    exit();
}

$userId = $_REQUEST["userId"];
$userUnitId = $_REQUEST["userUnitId"];
$skillId = $_REQUEST["skillId"];

$userUnitSkillDataArray = [
    "userId" => $userId,
    "userUnitId" => $userUnitId,
    "skillId" => $skillId
];

$controller = new UserUnitController();
$controller->removeSkill($userUnitSkillDataArray);