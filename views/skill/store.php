<?php
require_once "controllers/skillController.php";

//ID retrieval in case an update is issued
if(isset($_REQUEST["id"]) && filter_var($_REQUEST["id"], FILTER_VALIDATE_INT)){
    $id = $_REQUEST["id"];
}else{
    $id = "";
}

$skillArrayData = [
    "id" => $id, 
    "name" => htmlspecialchars($_REQUEST["name"]),
    "previousName" => isset($_REQUEST["previousName"]) ? htmlspecialchars($_REQUEST["previousName"]) : "",
    "type" => htmlspecialchars($_REQUEST["type"]),
    "attribute" => htmlspecialchars($_REQUEST["attribute"]),
    "value" => $_REQUEST["value"] 
];

$controller = new SkillController();

if ($_REQUEST["event"] == "create"){
    $controller->create($skillArrayData);
} else if($_REQUEST["event"] == "update"){
    $controller->update($id, $skillArrayData);
}