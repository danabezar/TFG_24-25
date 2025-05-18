<?php
require_once "assets/php/functions.php";
require_once "controllers/userController.php";
require_once "controllers/classController.php";
require_once "controllers/unitController.php";
require_once "controllers/userUnitController.php";

if(!isset($_REQUEST["userId"], $_REQUEST["userUnitId"])){
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

$userController = new UserController();
$classController = new ClassController();
$unitController = new UnitController();
$userUnitController = new UserUnitController();

$user = $userController->read($_REQUEST["userId"]);
$userUnit = $userUnitController->read($_REQUEST["userUnitId"]);
$statGains = $userUnitController->getStatGainsById($userUnit->id);
$classes = $classController->list();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Editing <?= $user->username ?>'s Unit "<?= $userUnit->name ?>"</h3>
    </div>
    <div id="content">
        <div class="alert alert-danger" style="display: <?= $errorVisibility ?>;"> <?= $errorString ?> </div>
        <form id="insertion-form" action="index.php?table=user&action=storeUnit&event=update" method="POST">
            <input type="hidden" id="userId" name="userId" value="<?= $user->id ?>">
            <input type="hidden" id="userUnitId" name="userUnitId" value="<?= $userUnit->id ?>">
            <div class="form-group">
                <label for="class">Class</label>
                <select id="class" name="class">
                    <?php 
                    foreach($classes as $class){
                        $isSelected = "";

                        if (isset($_SESSION["formData"]["class"])){
                            if($_SESSION["formData"]["class"] == $class->id){
                                $isSelected = "selected";
                            }
                        }else if($userUnit->class_id == $class->id){
                            $isSelected = "selected";
                        }
                        echo "<option value=" . $class->id . " " . $isSelected .">" . $class->name . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="level">Level</label>
                <input type="number" class="form-control" id="level" name="level" placeholder="Level" value="<?= $_SESSION["formData"]["level"] ?? $userUnit->level ?>" required>
            </div>
            <?= isset($errors["level"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "level") . '</div>' : ""; ?>
            <div class="form-group">
                <label for="experience">Experience points (0 to 99)</label>
                <input type="number" class="form-control" id="experience" name="experience" placeholder="Experience" value="<?= $_SESSION["formData"]["experience"] ?? $userUnit->experience ?>" required>
            </div>
            <?= isset($errors["experience"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "experience") . '</div>' : ""; ?>
            <br>
            <label for="unit-gains">Stat Gains (from Lvl Ups)</label>
            <div id="unit-gains>">
                <div class="form-group">
                    <label for="health_gains">Health</label>
                    <input type="number" class="form-control" id="health_gains" name="health_gains" placeholder="HP Gains" value="<?= $_SESSION["formData"]["health_gains"] ?? $statGains->health ?>" required>
                </div>
                <?= isset($errors["health_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "health_gains") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="strength_gains">Strength</label>
                    <input type="number" class="form-control" id="strength_gains" name="strength_gains" placeholder="STR Gains" value="<?= $_SESSION["formData"]["strength_gains"] ?? $statGains->strength ?>" required>
                </div>
                <?= isset($errors["strength_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "strength_gains") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="magic_gains">Magic</label>
                    <input type="number" class="form-control" id="magic_gains" name="magic_gains" placeholder="MAG Gains" value="<?= $_SESSION["formData"]["magic_gains"] ?? $statGains->magic ?>" required>
                </div>
                <?= isset($errors["magic_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "magic_gains") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="skill_gains">Skill</label>
                    <input type="number" class="form-control" id="skill_gains" name="skill_gains" placeholder="SKL Gains" value="<?= $_SESSION["formData"]["skill_gains"] ?? $statGains->skill ?>" required>
                </div>
                <?= isset($errors["skill_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "skill_gains") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="speed_gains">Speed</label>
                    <input type="number" class="form-control" id="speed_gains" name="speed_gains" placeholder="SPD Gains" value="<?= $_SESSION["formData"]["speed_gains"] ?? $statGains->speed ?>" required>
                </div>
                <?= isset($errors["speed_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "speed_gains") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="luck_gains">Luck</label>
                    <input type="number" class="form-control" id="luck_gains" name="luck_gains" placeholder="LCK Gains" value="<?= $_SESSION["formData"]["luck_gains"] ?? $statGains->luck ?>" required>
                </div>
                <?= isset($errors["luck_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "luck_gains") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="defense_gains">Defense</label>
                    <input type="number" class="form-control" id="defense_gains" name="defense_gains" placeholder="DEF Gains" value="<?= $_SESSION["formData"]["defense_gains"] ?? $statGains->defense ?>" required>
                </div>
                <?= isset($errors["defense_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "defense_gains") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="resistance_gains">Resistance</label>
                    <input type="number" class="form-control" id="resistance_gains" name="resistance_gains" placeholder="RES Gains" value="<?= $_SESSION["formData"]["resistance_gains"] ?? $statGains->resistance ?>" required>
                </div>
                <?= isset($errors["resistance_gains"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "resistance_gains") . '</div>' : ""; ?>
            </div>

            <button type="submit" class="btn btn-primary">Edit</button>
            <a class="btn btn-danger" href="index.php?table=user&action=show&id=<?= $user->id ?>">Cancel</a>
        </form>
    </div>
</main>

<?php
if (isset($_SESSION["errors"])) {
    unset($_SESSION["errors"]);
}
if (isset($_SESSION["formData"])) {
    unset($_SESSION["formData"]);
}
?>