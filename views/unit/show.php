<?php
require_once "controllers/unitController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST['id'];
$controller = new UnitController();
$unit = $controller->read($id);
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
                    Base Level: <strong><?= $unit->base_level ?></strong> <br>
                    Base Health: <strong><?= $unit->base_health ?></strong> <br>
                    Base Strength: <strong><?= $unit->base_strength ?></strong> <br>
                    Base Magic: <strong><?= $unit->base_magic ?></strong> <br>
                    Base Skill: <strong><?= $unit->base_skill ?></strong> <br>
                    Base Speed: <strong><?= $unit->base_speed ?></strong> <br>
                    Base Luck: <strong><?= $unit->base_luck ?></strong> <br>
                    Base Defense: <strong><?= $unit->base_defense ?></strong> <br>
                    Base Resistance: <strong><?= $unit->base_resistance ?></strong> <br>
                </p>
            </div>
        </div>
        <a href="index.php?table=unit&action=list" class="btn btn-primary">Return to list</a>
    </div>
</main>