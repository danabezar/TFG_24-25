<?php
require_once "controllers/classController.php";
require_once "controllers/skillController.php";

if(!isset($_REQUEST["id"])){
    header('location:index.php?table=class&action=list');
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

$classId = $_REQUEST["id"];
$classController = new ClassController();
$skillController = new SkillController();
$class = $classController->read($classId);
$availableSkills = $skillController->getAvailableForClass($classId);
$tableVisibility = "hidden";
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Available skills for <?= $class->name ?></h1>
        <a class="btn btn-primary" href="index.php?table=class&action=show&id=<?= $classId ?>" >Return to class</a>
    </div>
    <div id="content">
        <?php
        if (count($availableSkills) <= 0) {
            echo " No data to show";
        }
        else { ?>
            <div class="alert alert-danger" style="display: <?= $errorVisibility ?>;"> <?= $errorString ?> </div>
            <form action="index.php?table=class&action=store&event=addSkill" method="POST">
                <input type="hidden" name="id" value="<?= $classId ?>">
                <label for="selectedSkill">Skill</label>
                <select id="selectedSkill" name="selectedSkill">
                    <?php 
                    foreach($availableSkills as $skill){
                        echo '<option value="'. $skill->id . '">' . $skill->name . '</option>';
                    }
                    ?>
                </select>
                <?= isset($errors["selectedSkill"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "selectedSkill") . '</div>' : ""; ?>

                <label for="requiredLevel">Required Level</label>
                <input type="number" name="requiredLevel" placeholder="5" value=5 required>
                <?= isset($errors["requiredLevel"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "requiredLevel") . '</div>' : ""; ?>

                <button type="submit" class="btn btn-primary">Add skill</button>
            </form>
        <?php
        }
        ?>
    </div>
</main>