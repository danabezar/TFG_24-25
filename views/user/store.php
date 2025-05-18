<?php
require_once "controllers/userController.php";

$userArrayData = [    
    "username" => htmlspecialchars($_REQUEST["username"]),
    "password" => htmlspecialchars($_REQUEST["password"])
];

$controller = new UserController();

if ($_REQUEST["event"] == "create"){
    $controller->create($userArrayData);
}