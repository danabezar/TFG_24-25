<?php
require_once "controllers/classController.php";

//ID retrieval in case an update is issued
$id = ($_REQUEST["id"]) ?? "";

$classArrayData = [
    "id" => $id, 
    "name" => $_REQUEST["name"],
    "previousName" => $_REQUEST["previousName"] ?? "",
    "type" => $_REQUEST["type"],
    "health_growth" => $_REQUEST["health_growth"],
    "strength_growth" => $_REQUEST["strength_growth"],
    "magic_growth" => $_REQUEST["magic_growth"],
    "skill_growth" => $_REQUEST["skill_growth"],
    "speed_growth" => $_REQUEST["speed_growth"],
    "luck_growth" => $_REQUEST["luck_growth"],
    "defense_growth" => $_REQUEST["defense_growth"],
    "resistance_growth" => $_REQUEST["resistance_growth"]
];

$controller = new ClassController();

if ($_REQUEST["event"] == "create"){
    $controller->create($classArrayData);
} else if($_REQUEST["event"] == "update"){
    $controller->update($id, $classArrayData);
}