<?php
require_once "controllers/unitController.php";

if(!isset($_REQUEST["id"])){
    header('location:index.php?table=unit&action=list');
    exit();
}

$unitId = $_REQUEST["id"];
$unitController = new UnitController();
$unit = $unitController->read($unitId);

if($unit == null){
    header("location:index.php?table=unit&action=list");
    exit();
}

$unitController->delete($unitId);