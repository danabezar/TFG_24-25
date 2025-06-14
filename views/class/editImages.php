<?php
require_once "controllers/classController.php";

if (!isset($_REQUEST["id"])) {
    header('location:index.php?table=class&action=list');
    exit();
}

$classId = $_REQUEST["id"];
$classController = new ClassController();
$classData = $classController->read($classId);

if($classData == null){
    header("location:index.php?table=class&action=list");
    exit();
}

$imgRoute = "assets\img\class\\" . $classId;

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

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Modifying <?= $classData->name?>'s images</h1>
    </div>
    <form class="formImages" action="index.php?table=class&action=storeImages" enctype="multipart/form-data" method="POST">
        <div class="form-content">
            <input type="hidden" name="id" id="id" value="<?= $classData->id ?>">
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\portrait.png" title="Class Portrait">
                </div>
                <div class="imgSelector">
                    <label for="class_portrait">Portrait</label>
                    <input type="file" name="class_portrait" id="class_portrait">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\attack.gif" title="Class Attack">
                </div>
                <div class="imgSelector">
                    <label for="class_attack">Attack</label>
                    <input type="file" name="class_attack" id="class_attack">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\critical.gif" title="Class Critical">
                </div>
                <div class="imgSelector">
                    <label for="class_critical">Critical</label>
                    <input type="file" name="class_critical" id="class_critical">
                </div>
            </div>
            <div class="imgSelectorWrapper">
                <div class="imgWrapper">
                    <img src="<?= $imgRoute ?>\sprite.png" title="Class Sprite">
                </div>
                <div class="imgSelector">
                    <label for="class_sprite">Sprite</label>
                    <input type="file" name="class_sprite" id="class_sprite">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success btnImages">Edit images</button>
        <a class="btn btn-danger btnImages" href="index.php?table=class&action=list">Back to list</a>
    </form>
    <div id="imgUploadMessage" name="imgUploadMessage" class="<?= $messageClass ?> imgUploadMessage" <?= $messageVisibility ?>> <?= $messageText ?></div>
    <?php 
    unset($_SESSION["uploadResults"]);
    ?>
</main>