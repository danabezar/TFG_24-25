<?php
require_once "controllers/classController.php";

//ID retrieval in case an update is issued
if(!isset($_REQUEST["starterId"], $_REQUEST["promotedId"])){
    header("location:index.php?table=class&action=list");
    exit();
}

$starterId = $_REQUEST["starterId"];
$promotedId = $_REQUEST["promotedId"];
$classController = new ClassController();
$starterClass = $classController->read($starterId);
$promotedClass = $classController->read($promotedId);

if($starterClass == null || $promotedClass == null){
    header("location:index.php?table=class&action=list");
    exit();
}

$classController->addPromotion($starterId, $promotedId);
header("location:index.php?table=class&action=show&id=" . $starterId);
exit();