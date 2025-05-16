<?php
require_once "controllers/userUnitController.php";

if(!isset($_REQUEST["userId"], $_REQUEST["userUnitId"], )){
    header("location:index.php?table=user&action=list");
    exit();
}

$userId = $_REQUEST["userId"];
$userUnitId = $_REQUEST["userUnitId"];
$skillId = $_REQUEST["skill"];

$userUnitSkillDataArray = [
    "userId" => $userId,
    "userUnitId" => $userUnitId,
    "skill" => $skillId
];

$userUnitController = new UserUnitController();
$userUnitController->addSkill($userUnitSkillDataArray);