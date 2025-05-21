<?php
require_once "controllers/skillController.php";

if(isset($_REQUEST["id"]) && filter_var($_REQUEST["id"], FILTER_VALIDATE_INT)){
    $skillId = $_REQUEST["id"];
}else{
    header("location:index.php?table=skill&action=list");
}

$skillController = new SkillController();
$skillData = $skillController->read($skillId);

if($skillData == null){
    header("location:index.php?table=skill&action=list");
    exit();
}

$allowedExtensions = ["png"];
$uploadedImgs = [
    "skill_icon" => $_FILES["skill_icon"] ?? null
];

$imgRoute = "assets\img\skill\\" . $skillId;
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
            $tempFileName = $currentImg["tmp_name"];
            $uploadRoute = $imgRoute .  "\\" . $imgType . ".png";

            $uploadResult = move_uploaded_file($tempFileName, $uploadRoute);

            if($uploadResult){
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($portraitType) . " succesfully updated";
            }else{
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($portraitType) . " couldn't be updated";
            }
        }else{
            $uploadResults[$key][] = "
                The format of the uploaded file for " . ucfirst($classType) . 
                " ". ucfirst($portraitType) . " is not supported, try again";
        }
    }
}

$_SESSION["uploadResults"] = $uploadResults;

header("location:index.php?table=skill&action=editImages&id={$skillId}&event=upload");
exit();
?>