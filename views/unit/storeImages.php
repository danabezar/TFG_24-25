<?php
require_once "controllers/unitController.php";

if(isset($_REQUEST["id"]) && filter_var($_REQUEST["id"], FILTER_VALIDATE_INT)){
    $unitId = $_REQUEST["id"];
}else{
    header("location:index.php?table=class&action=list");
}

$unitController = new UnitController();
$unitData = $unitController->read($unitId);

if($unitData == null){
    header("location:index.php?table=unit&action=list");
    exit();
}

$allowedExtensions = ["png"];
$uploadedImgs = [
    "starter_default" => $_FILES["starter_default"] ?? null,
    "starter_victory" => $_FILES["starter_victory"] ?? null,
    "starter_defeat" => $_FILES["starter_defeat"] ?? null,
    "promoted_default" => $_FILES["promoted_default"] ?? null,
    "promoted_victory" => $_FILES["promoted_victory"] ?? null,
    "promoted_defeat" => $_FILES["promoted_defeat"] ?? null,
];

$imgRoute = "assets\img\unit\\" . $unitId;
$uploadResults = [];
if(isset($_SESSION["uploadResults"])){
    unset($_SESSION["uploadResults"]);
}

if(!file_exists($imgRoute)){
    mkdir($imgRoute);
}

foreach($uploadedImgs as $key => $fileInfo){
    $currentImg = $uploadedImgs[$key];

    if($currentImg != null && $currentImg["size"] > 0){
        $classType = explode("_", $key)[0];
        $portraitType = explode("_", $key)[1];
        
        if(in_array(pathinfo($currentImg["name"], PATHINFO_EXTENSION), $allowedExtensions)){
            $tempFileName = $currentImg["tmp_name"];
            $uploadRoute = $imgRoute .  "\\" . $classType . "\\" . $portraitType . ".png";

            $uploadResult = move_uploaded_file($tempFileName, $uploadRoute);

            if($uploadResult){
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($portraitType) . " portrait succesfully updated";
            }else{
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($portraitType) . " portrait couldn't be updated";
            }
        }else{
            $uploadResults[$key][] = "
                The format of the uploaded file for " . ucfirst($classType) . 
                " ". ucfirst($portraitType) . " is not supported, try again";
        }
    }
}

$_SESSION["uploadResults"] = $uploadResults;

header("location:index.php?table=unit&action=editImages&id={$unitId}&event=upload");
exit();
?>