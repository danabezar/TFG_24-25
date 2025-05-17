<?php
require_once "controllers/skillController.php";

if (!isset($_REQUEST["id"])) {
    header('location:index.php?table=skill&action=list');
    exit();
}

$skillId = $_REQUEST["id"];
$imgRoute = "assets\img\skill\\" . $skillId;

$controller = new skillController();
$skillData = $controller->read($skillId);

$messageText = "";
$messageVisibility = "hidden";
$messageClass = "alert alert-primary";

if (isset($_REQUEST["event"], $_SESSION["uploadResults"]) && $_REQUEST["event"] == "upload") {
    $uploadResults = $_SESSION["uploadResults"];
    foreach($uploadResults as $result){
        $messageText.= $result[0] . "<br>";
    }
    if($messageText != ""){
        $messageVisibility = "visible";
    }
}
?>

<style>
    img{
        width: 150px;
        height: 150px;
    }
</style>

<link rel="stylesheet" href="views/digimon/css/images.css">
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Modifying <?= $skillData->name?>'s icon</h1>
    </div>
    <form class="formImages" action="index.php?table=skill&action=storeImages" enctype="multipart/form-data" method="POST">
        <div class="form-content">
            <input type="hidden" name="id" id="id" value="<?= $skillData->id ?>">
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\icon.png" title="Skill Icon">
                </div>
                <div class="imgSelector">
                    <label for="skill_icon">Icon</label>
                    <input type="file" name="skill_icon" id="skill_icon">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success btnImages">Edit image</button>
        <a class="btn btn-danger btnImages" href="index.php?table=skill&action=list">Back to list</a>
    </form>
    <div id="imgUploadMessage" name="imgUploadMessage" class="<?= $messageClass ?> imgUploadMessage" <?= $messageVisibility ?>> <?= $messageText ?></div>
    <?php 
    unset($_SESSION["uploadResults"]);
    ?>
</main>