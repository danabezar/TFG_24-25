<?php
require_once "controllers/userUnitController.php";

if(
    !isset($_REQUEST["userId"], $_REQUEST["userUnitId"], $_REQUEST["skill"]) || 
    !filter_var($_REQUEST["userId"], FILTER_VALIDATE_INT) || 
    !filter_var($_REQUEST["userUnitId"], FILTER_VALIDATE_INT) || 
    !filter_var($_REQUEST["skill"], FILTER_VALIDATE_INT)){
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