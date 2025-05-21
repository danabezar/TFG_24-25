<?php
require_once "controllers/unitController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST["id"];
$unitController = new UnitController();
$unit = $unitController->read($id);

if($unit == null){
    header("location:index.php?table=unit&action=list");
    exit();
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Showing Unit "<?= $unit->name ?>"'s Information</h3>
    </div>
    <div id="content">
        <div class="card">
            <div>
                <div>
                    <h5 class="card-title"><?= $unit->id . ": " . $unit->name ?></h5>
                </div>
                <hr>
                <p class="card-text">
                    Unit's ID: <strong><?= $unit->id ?></strong> <br>
                    Name: <strong><?= $unit->name ?></strong> <br>
                    Class: <strong><?= $unit->class ?></strong> <br>
                    Base Level: <strong><?= $unit->level_base ?></strong> <br>
                    Base Health: <strong><?= $unit->health_base ?></strong> <br>
                    Base Strength: <strong><?= $unit->strength_base ?></strong> <br>
                    Base Magic: <strong><?= $unit->magic_base ?></strong> <br>
                    Base Skill: <strong><?= $unit->skill_base ?></strong> <br>
                    Base Speed: <strong><?= $unit->speed_base ?></strong> <br>
                    Base Luck: <strong><?= $unit->luck_base ?></strong> <br>
                    Base Defense: <strong><?= $unit->defense_base ?></strong> <br>
                    Base Resistance: <strong><?= $unit->resistance_base ?></strong> <br>
                    Health Growth: <strong><?= $unit->health_growth ?>%</strong> <br>
                    Strength Growth: <strong><?= $unit->strength_growth ?>%</strong><br>
                    Magic Growth: <strong><?= $unit->magic_growth ?></strong>%<br>
                    Skill Growth: <strong><?= $unit->skill_growth ?></strong>%<br>
                    Speed Growth: <strong><?= $unit->speed_growth ?></strong>%<br>
                    Luck Growth: <strong><?= $unit->luck_growth ?></strong>%<br>
                    Defense Growth: <strong><?= $unit->defense_growth ?></strong>%<br>
                    Resistance Growth: <strong><?= $unit->resistance_growth ?></strong>%<br>
                </p>
            </div>
        </div>
        <a href="index.php?table=unit&action=list" class="btn btn-primary">Return to list</a>
    </div>
</main>