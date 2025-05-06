<?php
require_once "controllers/skillController.php";

//ID retrieval in case an update is issued
$id = ($_REQUEST["id"]) ?? "";

$skillArrayData = [
    "id" => $id, 
    "name" => $_REQUEST["name"],
    "previousName" => $_REQUEST["previousName"] ?? "",
    "type" => $_REQUEST["type"],
    "attribute" => $_REQUEST["attribute"],
    "value" => $_REQUEST["value"]
];

$controller = new SkillController();

if ($_REQUEST["event"] == "create"){
    $controller->create($skillArrayData);
} else if($_REQUEST["event"] == "update"){
    $controller->update($id, $skillArrayData);
}