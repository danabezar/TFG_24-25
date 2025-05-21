<?php
require_once "controllers/userController.php";
require_once "controllers/userUnitController.php";
require_once "controllers/skillController.php";

if(!isset($_REQUEST["userId"], $_REQUEST["userUnitId"], $_REQUEST["skillId"])){
    header('location:index.php?table=user&action=list');
    exit();
}

$userId = $_REQUEST["userId"];
$userController = new UserController();
$user = $userController->read($userId);

$userUnitId = $_REQUEST["userUnitId"];
$userUnitController = new UserUnitController();
$userUnit = $userUnitController->read($userUnitId);

$skillId = $_REQUEST["skillId"];
$skillController = new SkillController();
$skill = $skillController->read($skillId);

if($user == null || $userUnit == null || $skill == null){
    header("location:index.php?table=user&action=list");
    exit();
}

$userUnitSkillDataArray = [
    "userId" => $userId,
    "userUnitId" => $userUnitId,
    "skillId" => $skillId
];

$userUnitController->removeSkill($userUnitSkillDataArray);