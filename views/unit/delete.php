<?php
require_once "controllers/unitController.php";

if(!isset($_REQUEST["id"])){
    header('location:index.php?table=unit&action=list');
    exit();
}

$unitId = $_REQUEST["id"];

$controller = new UnitController();
$controller->delete($unitId);