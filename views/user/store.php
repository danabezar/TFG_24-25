<?php
require_once "controllers/userController.php";

$userArrayData = [    
    "username" => $_REQUEST["username"],
    "password" => $_REQUEST["password"]
];

$controller = new UserController();

if ($_REQUEST["event"] == "create"){
    $controller->create($userArrayData);
}