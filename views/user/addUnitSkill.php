<?php
require_once "controllers/skillController.php";
require_once "controllers/userUnitController.php";

if(!isset($_REQUEST["userId"], $_REQUEST["userUnitId"])){
    header('location:index.php?table=user&action=list');
    exit();
}

$userId = $_REQUEST["userId"];
$userController = new UserController();
$user = $userController->read($userId);

$userUnitId = $_REQUEST["userUnitId"];
$userUnitController = new UserUnitController();
$userUnit = $userUnitController->read($userUnitId);

if($user == null || $userUnit == null){
    header("location:index.php?table=user&action=list");
    exit();
}

$errors = [];
$previousFormData = [];
$errorString = "Errors were found in the data introduced";
$errorVisibility = "none";

if (isset($_SESSION["errors"])) {
    $errors = ($_SESSION["errors"]) ?? [];
    $previousFormData = ($_SESSION["formData"]) ?? [];
    $errorVisibility = "visible";
}

$skillController = new SkillController();
$availableSkills = $userUnitController->listAvailableSkills($userUnitId);

$tableVisibility = "hidden";
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Available skills for <?= $userUnit->name ?></h1>
        <a class="btn btn-primary" href="index.php?table=user&action=showUnit&userId=<?= $userId ?>&userUnitId=<?= $userUnit->id ?>">Return to unit</a>
    </div>
    <div id="content">
        <?php
        if (count($availableSkills) > 0) { ?>
        <div class="alert alert-danger" style="display: <?= $errorVisibility ?>;"> <?= $errorString ?> </div>
            <form action="index.php?table=user&action=storeSkill" method="POST">
                <input type="hidden" name="userId" value="<?= $userId ?>">
                <input type="hidden" name="userUnitId" value="<?= $userUnit->id ?>">
                <label for="skill">Skill</label>
                <select id="skill" name="skill">
                    <?php 
                    foreach($availableSkills as $skill){
                        echo '<option value="'. $skill->id . '">' . $skill->name . '</option>';
                    }
                    ?>
                </select>
                <?= isset($errors["skill"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "skill") . '</div>' : ""; ?>

                <button type="submit" class="btn btn-primary">Add skill</button>
            </form>
        </div>
        <?php } else { ?>
            <div>
                <p>No more skills available to add</p>
            </div>
        <?php }
        ?>
</main>