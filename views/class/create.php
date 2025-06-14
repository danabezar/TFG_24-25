<?php
require_once "assets/php/functions.php";
require_once "controllers/classController.php";

$errors = [];
$previousFormData = [];
$errorString = "Errors were found in the data introduced";
$errorVisibility = "none";

if (isset($_SESSION["errors"])) {
    $errors = ($_SESSION["errors"]) ?? [];
    $previousFormData = ($_SESSION["formData"]) ?? [];
    $errorVisibility = "visible";
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Add a new Class</h3>
    </div>
    <div id="content">
        <div class="alert alert-danger" style="display: <?= $errorVisibility ?>;"> <?= $errorString ?> </div>
        <form id="insertion-form" action="index.php?table=class&action=store&event=create" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="Name" placeholder="Write a name for the class" value="<?= $_SESSION["formData"]["name"] ?? "" ?>" required>
                <?= isset($errors["name"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "name") . '</div>' : ""; ?>
            </div>
            <div class="form-group">
                <label for="type_radiogroup">Class Type</label>
                <div id="type-radiogroup">
                    <label>
                        <input type="radio" name="type" value="Starter" checked> Starter
                    </label>
                    <label>
                        <input type="radio" name="type" value="Promoted"
                        <?php 
                            if (isset($_SESSION["formData"]["type"])){
                                if($_SESSION["formData"]["type"] == "Promoted"){
                                    echo "checked";
                                }
                            }
                        ?>> Promoted
                    </label>
                </div>
                <?= isset($errors["type"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "type") . '</div>' : ""; ?>
            </div>
            <hr>
            <div class="form-group">
                <label for="type_radiogroup">Damage Type</label>
                <div id="dmgType-radiogroup">
                    <label>
                        <input type="radio" name="dmgType" value="0" checked> Physical
                    </label>
                    <label>
                        <input type="radio" name="dmgType" value="1"
                        <?php 
                            if (isset($_SESSION["formData"]["dmgType"])){
                                if($_SESSION["formData"]["dmgType"] == 1){
                                    echo "checked";
                                }
                            }
                        ?>> Magical
                    </label>
                </div>
                <?= isset($errors["dmgType"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "dmgType") . '</div>' : ""; ?>
            </div>
            <hr>
            <label for="class-growths">Class Growths (%)</label>
            <div id="class-growths>">
                <div class="form-group">
                    <label for="health_growth">Health</label>
                    <input type="number" class="form-control" id="health_growth" name="health_growth" placeholder="HP Growth Rate" value="<?= $_SESSION["formData"]["health_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["health_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "health_growth") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="strength_growth">Strength</label>
                    <input type="number" class="form-control" id="strength_growth" name="strength_growth" placeholder="STR Growth Rate" value="<?= $_SESSION["formData"]["strength_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["strength_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "strength_growth") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="magic_growth">Magic</label>
                    <input type="number" class="form-control" id="magic_growth" name="magic_growth" placeholder="MAG Growth Rate" value="<?= $_SESSION["formData"]["magic_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["magic_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "magic_growth") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="skill_growth">Skill</label>
                    <input type="number" class="form-control" id="skill_growth" name="skill_growth" placeholder="SKL Growth Rate" value="<?= $_SESSION["formData"]["skill_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["skill_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "skill_growth") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="speed_growth">Speed</label>
                    <input type="number" class="form-control" id="speed_growth" name="speed_growth" placeholder="SPD Growth Rate" value="<?= $_SESSION["formData"]["speed_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["speed_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "speed_growth") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="luck_growth">Luck</label>
                    <input type="number" class="form-control" id="luck_growth" name="luck_growth" placeholder="LCK Growth Rate" value="<?= $_SESSION["formData"]["luck_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["luck_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "luck_growth") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="defense_growth">Defense</label>
                    <input type="number" class="form-control" id="defense_growth" name="defense_growth" placeholder="DEF Growth Rate" value="<?= $_SESSION["formData"]["defense_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["defense_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "defense_growth") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="resistance_growth">Resistance</label>
                    <input type="number" class="form-control" id="resistance_growth" name="resistance_growth" placeholder="RES Growth Rate" value="<?= $_SESSION["formData"]["resistance_growth"] ?? 10 ?>" required>
                </div>
                <?= isset($errors["resistance_growth"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "resistance_growth") . '</div>' : ""; ?>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <a class="btn btn-danger" href="index.php?table=class&action=list">Cancel</a>
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