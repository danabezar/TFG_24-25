<?php
require_once "controllers/unitController.php";

if (!isset($_REQUEST["id"])) {
    header('location:index.php?table=unit&action=list');
    exit();
}

$unitId = $_REQUEST["id"];
$imgRoute = "assets\img\unit\\" . $unitId ;

$controller = new unitController();
$unitData = $controller->read($unitId);

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
        <h1 class="h3">Modifying <?= $unitData->name?>'s portraits</h1>
    </div>
    <form class="formImages" action="index.php?table=unit&action=storeImages" enctype="multipart/form-data" method="POST">
        <div class="form-content">
            <input type="hidden" name="id" id="id" value="<?= $unitData->id ?>">
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\starter\default.png" title="Starter Default">
                </div>
                <div class="imgSelector">
                    <label for="starter_default">Default</label>
                    <input type="file" name="starter_default" id="starter_default">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\starter\victory.png" title="Starter Victory">
                </div>
                <div class="imgSelector">
                    <label for="starter_victory">Victory</label>
                    <input type="file" name="starter_victory" id="starter_victory">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\starter\defeat.png" title="Starter Defeat">
                </div>
                <div class="imgSelector">
                    <label for="starter_defeat">Defeat</label>
                    <input type="file" name="starter_defeat" id="starter_defeat">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\promoted\default.png" title="Promoted Default">
                </div>
                <div class="imgSelector">
                    <label for="promoted_default">Default</label>
                    <input type="file" name="promoted_default" id="promoted_default">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\promoted\victory.png" title="Promoted Victory">
                </div>
                <div class="imgSelector">
                    <label for="promoted_victory">Victory</label>
                    <input type="file" name="promoted_victory" id="promoted_victory">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\promoted\defeat.png" title="Promoted Defeat">
                </div>
                <div class="imgSelector">
                    <label for="promoted_defeat">Defeat</label>
                    <input type="file" name="promoted_defeat" id="promoted_defeat">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success btnImages">Edit images</button>
        <a class="btn btn-danger btnImages" href="index.php?table=unit&action=list">Back to list</a>
    </form>
    <div id="imgUploadMessage" name="imgUploadMessage" class="<?= $messageClass ?> imgUploadMessage" <?= $messageVisibility ?>> <?= $messageText ?></div>
    <?php 
    unset($_SESSION["uploadResults"]);
    ?>
</main>