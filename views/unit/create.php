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

$classController = new ClassController();
$classes = $classController->list();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Add a new Unit</h3>
    </div>
    <div id="content">
        <div class="alert alert-danger" style="display: <?= $errorVisibility ?>;"> <?= $errorString ?> </div>
        <form id="insertion-form" action="index.php?table=unit&action=store&event=create" method="POST">
            <div class="form-group">
                <label for="username">Name</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="Name" placeholder="Write a name for the unit" value="<?= $_SESSION["formData"]["name"] ?? "" ?>" required>
                <?= isset($errors["name"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "name") . '</div>' : ""; ?>
            </div>
            <br> <!-- TODO: Remove this -->
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
                        }
                        echo "<option value=" . $class->id . " " . $isSelected .">" . $class->name . "</option>";
                    }
                    ?>
                </select>
            </div>
            <br> <!-- TODO: Remove this -->
            <label for="unit-bases">Base Stats</label>
            <div id="unit-bases>">
                <div class="form-group">
                    <label for="level_base">Level</label>
                    <input type="number" class="form-control" id="level_base" name="level_base" placeholder="Base Level" value="<?= $_SESSION["formData"]["level_base"] ?? 1 ?>" required>
                </div>
                <?= isset($errors["level_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "level_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="health_base">Health</label>
                    <input type="number" class="form-control" id="health_base" name="health_base" placeholder="Base HP" value="<?= $_SESSION["formData"]["health_base"] ?? 6 ?>" required>
                </div>
                <?= isset($errors["health_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "health_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="strength_base">Strength</label>
                    <input type="number" class="form-control" id="strength_base" name="strength_base" placeholder="Base STR" value="<?= $_SESSION["formData"]["strength_base"] ?? 5 ?>" required>
                </div>
                <?= isset($errors["strength_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "strength_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="magic_base">Magic</label>
                    <input type="number" class="form-control" id="magic_base" name="magic_base" placeholder="Base MAG" value="<?= $_SESSION["formData"]["magic_base"] ?? 5 ?>" required>
                </div>
                <?= isset($errors["magic_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "magic_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="skill_base">Skill</label>
                    <input type="number" class="form-control" id="skill_base" name="skill_base" placeholder="Base SKL" value="<?= $_SESSION["formData"]["skill_base"] ?? 5 ?>" required>
                </div>
                <?= isset($errors["skill_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "skill_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="speed_base">Speed</label>
                    <input type="number" class="form-control" id="speed_base" name="speed_base" placeholder="Base SPD" value="<?= $_SESSION["formData"]["speed_base"] ?? 5 ?>" required>
                </div>
                <?= isset($errors["speed_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "speed_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="luck_base">Luck</label>
                    <input type="number" class="form-control" id="luck_base" name="luck_base" placeholder="Base LCK" value="<?= $_SESSION["formData"]["luck_base"] ?? 5 ?>" required>
                </div>
                <?= isset($errors["luck_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "luck_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="defense_base">Defense</label>
                    <input type="number" class="form-control" id="defense_base" name="defense_base" placeholder="Base DEF" value="<?= $_SESSION["formData"]["defense_base"] ?? 5 ?>" required>
                </div>
                <?= isset($errors["defense_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "defense_base") . '</div>' : ""; ?>
                <div class="form-group">
                    <label for="resistance_base">Resistance</label>
                    <input type="number" class="form-control" id="resistance_base" name="resistance_base" placeholder="Base RES" value="<?= $_SESSION["formData"]["resistance_base"] ?? 5 ?>" required>
                </div>
                <?= isset($errors["resistance_base"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "resistance_base") . '</div>' : ""; ?>
            </div>
            <br> <!-- TODO: Remove this -->
            <label for="unit-growths">Unit Growths (%)</label>
            <div id="unit-growths>">
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
            <a class="btn btn-danger" href="index.php?table=unit&action=list">Cancel</a>
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