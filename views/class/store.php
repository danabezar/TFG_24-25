<?php
require_once "controllers/classController.php";

//ID retrieval in case an update is issued
if(isset($_REQUEST["id"]) && filter_var($_REQUEST["id"], FILTER_VALIDATE_INT)){
    $id = $_REQUEST["id"];
}else{
    $id = "";
}

if($id != null && $id != ""){
    $classController = new ClassController();
    $class = $classController->read($id);

    if($class == null){
        header("location:index.php?table=class&action=list");
        exit();
    }
}

$classArrayData = [
    "id" => $id, 
    "name" => isset($_REQUEST["name"]) ? htmlspecialchars($_REQUEST["name"]) : "",
    "previousName" => isset($_REQUEST["previousName"]) ? htmlspecialchars($_REQUEST["previousName"]) : "",
    "type" => isset($_REQUEST["type"]) ? htmlspecialchars($_REQUEST["type"]) : "",
    "dmgType" => isset($_REQUEST["dmgType"]) ? ($_REQUEST["dmgType"]) : "",
    "health_growth" => $_REQUEST["health_growth"] ?? "",
    "strength_growth" => $_REQUEST["strength_growth"] ?? "",
    "magic_growth" => $_REQUEST["magic_growth"] ?? "",
    "skill_growth" => $_REQUEST["skill_growth"] ?? "",
    "speed_growth" => $_REQUEST["speed_growth"] ?? "",
    "luck_growth" => $_REQUEST["luck_growth"] ?? "",
    "defense_growth" => $_REQUEST["defense_growth"] ?? "",
    "resistance_growth" => $_REQUEST["resistance_growth"] ?? "",
    "selectedSkill" => $_REQUEST["selectedSkill"] ?? "",
    "requiredLevel" => $_REQUEST["requiredLevel"] ?? ""
];

$controller = new ClassController();

if ($_REQUEST["event"] == "create"){
    $controller->create($classArrayData);
} else if ($_REQUEST["event"] == "update"){
    $controller->update($id, $classArrayData);
} else if ($_REQUEST["event"] == "addSkill"){
    $controller->addSkill($classArrayData);
}