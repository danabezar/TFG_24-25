<?php
require_once "controllers/classController.php";

if(isset($_REQUEST["id"]) && filter_var($_REQUEST["id"], FILTER_VALIDATE_INT)){
    $classId = $_REQUEST["id"];
}else{
    header("location:index.php?table=class&action=list");
}

$classController = new ClassController();
$classData = $classController->read($classId);

if($classData == null){
    header("location:index.php?table=class&action=list");
    exit();
}

$allowedExtensions = ["png", "gif"];
$uploadedImgs = [
    "class_portrait" => $_FILES["class_portrait"] ?? null,
    "class_sprite" => $_FILES["class_sprite"] ?? null,
    "class_attack" => $_FILES["class_attack"] ?? null,
    "class_critical" => $_FILES["class_critical"] ?? null
];

$imgRoute = "assets\img\class\\" . $classId;
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
        $imgType = explode("_", $key)[1];
        
        if(in_array(pathinfo($currentImg["name"], PATHINFO_EXTENSION), $allowedExtensions)){
            $fileExtension = explode(".", $currentImg["name"])[1];
            $tempFileName = $currentImg["tmp_name"];
            $uploadRoute = $imgRoute .  "\\" . $imgType . "." . $fileExtension;

            $uploadResult = move_uploaded_file($tempFileName, $uploadRoute);

            if($uploadResult){
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($imgType) . " succesfully updated";
            }else{
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($imgType) . " couldn't be updated";
            }

            if($imgType === "attack"){
                copy($imgRoute . "\\" . $imgType . ".gif", $imgRoute . "\\idle.png");
            }
        }else{
            $uploadResults[$key][] = "
                The format of the uploaded file for " . ucfirst($classType) . 
                " ". ucfirst($imgType) . " is not supported, try again";
        }
    }
}

$_SESSION["uploadResults"] = $uploadResults;

header("location:index.php?table=class&action=editImages&id={$classId}&event=upload");
exit();
?>