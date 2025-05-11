<?php
require_once "controllers/unitController.php";

//ID retrieval in case an update is issued
$id = ($_REQUEST["id"]) ?? "";

$classArrayData = [
    "id" => $id, 
    "name" => $_REQUEST["name"], 
    "previousName" => $_REQUEST["previousName"], 
    "class" => $_REQUEST["class"], 
    "level_base" => $_REQUEST["level_base"], 
    "health_base" => $_REQUEST["health_base"], 
    "strength_base" => $_REQUEST["strength_base"], 
    "magic_base" => $_REQUEST["magic_base"], 
    "skill_base" => $_REQUEST["skill_base"], 
    "speed_base" => $_REQUEST["speed_base"], 
    "luck_base" => $_REQUEST["luck_base"], 
    "defense_base" => $_REQUEST["defense_base"], 
    "resistance_base" => $_REQUEST["resistance_base"], 
    "health_growth" => $_REQUEST["health_growth"], 
    "strength_growth" => $_REQUEST["strength_growth"], 
    "magic_growth" => $_REQUEST["magic_growth"], 
    "skill_growth" => $_REQUEST["skill_growth"], 
    "speed_growth" => $_REQUEST["speed_growth"], 
    "luck_growth" => $_REQUEST["luck_growth"], 
    "defense_growth" => $_REQUEST["defense_growth"], 
    "resistance_growth" => $_REQUEST["resistance_growth"]
];

$controller = new UnitController();

if ($_REQUEST["event"] == "create"){
    $controller->create($classArrayData);
} else if ($_REQUEST["event"] == "update"){
    $controller->update($id, $classArrayData);
}