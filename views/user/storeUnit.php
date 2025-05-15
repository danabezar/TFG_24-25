<?php
require_once "controllers/unitController.php";
require_once "controllers/userUnitController.php";

//ID retrieval in case an update is issued
$userId = ($_REQUEST["userId"]) ?? "";
$unitId = ($_REQUEST["unitId"]) ?? "";
$userUnitId = ($_REQUEST["userUnitId"]) ?? "";

$unitController = new UnitController();
$userUnitController = new UserUnitController();

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
    $userUnitArrayData["level"] = $unitData->level_base;
}else{
    $userUnitArrayData["level"] = $_REQUEST["level"] ?? "";
}

if ($_REQUEST["event"] == "add"){
    $userUnitController->create($userUnitArrayData);
} else if ($_REQUEST["event"] == "update"){
    $userUnitController->update($userUnitArrayData);
}

