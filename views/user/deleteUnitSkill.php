<?php
require_once "controllers/userUnitController.php";

if(!isset($_REQUEST["userId"], $_REQUEST["userUnitId"], $_REQUEST["skillId"])){
    header('location:index.php?table=user&action=list');
    exit();
}

$userId = $_REQUEST["userId"];
$userUnitId = $_REQUEST["userUnitId"];
$skillId = $_REQUEST["skillId"];

$controller = new UserUnitController();
$controller->removeSkill($userUnitId, $skillId);

//TODO: THIS REDIRECT IS PENDING TO BE REMOVED
header("location:index.php?table=user&action=showUnit&userId=" . $userId . "&userUnitId=" . $userUnitId);
exit();