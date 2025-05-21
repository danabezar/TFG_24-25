<?php
require_once "controllers/userController.php";
require_once "controllers/unitController.php";
require_once "controllers/userUnitController.php";

//ID retrieval in case an update is issued
$userId = ($_REQUEST["userId"]) ?? "";
$unitId = ($_REQUEST["unitId"]) ?? "";
$userUnitId = ($_REQUEST["userUnitId"]) ?? "";

if($userId != null && $userId != ""){
    $userController = new UserController();
    $user = $userController->read($userId);

    if($user == null){
        header("location:index.php?table=user&action=list");
        exit();
    }
}

if($unitId != null && $unitId != ""){
    $unitController = new UnitController();
    $unit = $unitController->read($unitId);

    if($unit == null){
        header("location:index.php?table=user&action=list");
        exit();
    }
}

if($userUnitId != null && $userUnitId != ""){
    $userUnitController = new UserUnitController();
    $userUnit = $userUnitController->read($userUnitId);

    if($userUnit == null){
        header("location:index.php?table=user&action=list");
        exit();
    }
}

$userUnitArrayData = [
    "userId" => $userId,
    "unitId" => $unitId, 
    "userUnitId" => $userUnitId,
    "experience" => $_REQUEST["experience"] ?? 0, 
    "health_gains" => $_REQUEST["health_gains"] ?? 0, 
    "strength_gains" => $_REQUEST["strength_gains"] ?? 0, 
    "magic_gains" => $_REQUEST["magic_gains"] ?? 0, 
    "skill_gains" => $_REQUEST["skill_gains"] ?? 0, 
    "speed_gains" => $_REQUEST["speed_gains"] ?? 0, 
    "luck_gains" => $_REQUEST["luck_gains"] ?? 0, 
    "defense_gains" => $_REQUEST["defense_gains"] ?? 0, 
    "resistance_gains" => $_REQUEST["resistance_gains"] ?? 0
];

if($unitId != null && $unitId != ""){
    $unitData = $unitController->read($unitId);
    $userUnitArrayData["class"] = $unitData->classId;
    $userUnitArrayData["level"] = $unitData->level_base;
}else{
    $userUnitArrayData["class"] = $_REQUEST["class"];
    $userUnitArrayData["level"] = $_REQUEST["level"] ?? "";
}

if ($_REQUEST["event"] == "add"){
    $userUnitController->create($userUnitArrayData);
} else if ($_REQUEST["event"] == "update"){
    $userUnitController->update($userUnitArrayData);
}

