<?php
require_once "controllers/classController.php";

if(isset($_REQUEST["id"]) && filter_var($_REQUEST["id"], FILTER_VALIDATE_INT)){
    $classId = $_REQUEST["id"];
}else{
    header("location:index.php?table=class&action=list");
}

$controller = new ClassController();
$classData = $controller->read($classId);

$allowedExtensions = ["png"];
$uploadedImgs = [
    "class_portrait" => $_FILES["class_portrait"] ?? null,
    "class_sprites" => $_FILES["class_sprites"] ?? null
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
            $tempFileName = $currentImg["tmp_name"];
            $uploadRoute = $imgRoute .  "\\" . $imgType . ".png";

            $uploadResult = move_uploaded_file($tempFileName, $uploadRoute);

            if($uploadResult){
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($imgType) . " succesfully updated";
            }else{
                $uploadResults[$key][] = ucfirst($classType) . " " . ucfirst($imgType) . " couldn't be updated";
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