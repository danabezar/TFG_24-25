<?php
require_once "controllers/classController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST['id'];
$controller = new ClassController();
$class = $controller->readDetailed($id);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Showing Class "<?= $class->name ?>"'s Information</h3>
    </div>
    <div id="content">
        <div class="card">
            <div>
                <div>
                    <h5 class="card-title"><?= $class->id . ": " . $class->name ?></h5>
                </div>
                <hr>
                <p class="card-text">
                    Class ID: <strong><?= $class->id ?></strong><br>
                    Name: <strong><?= $class->name ?></strong><br>
                    Type: <strong><?= $class->type ?></strong><br>
                    Health Growth: <strong><?= $class->health_growth ?>%</strong> <br>
                    Strength Growth: <strong><?= $class->strength_growth ?>%</strong> <br>
                    Magic Growth: <strong><?= $class->magic_growth ?>%</strong> <br>
                    Skill Growth: <strong><?= $class->skill_growth ?>%</strong> <br>
                    Speed Growth: <strong><?= $class->speed_growth ?>%</strong> <br>
                    Luck Growth: <strong><?= $class->luck_growth ?>%</strong> <br>
                    Defense Growth: <strong><?= $class->defense_growth ?>%</strong> <br>
                    Resistance Growth: <strong><?= $class->resistance_growth ?>%</strong> <br>
                </p>
            </div>
        </div>
        <a href="index.php?table=class&action=list" class="btn btn-primary">Return to list</a>
    </div>
</main>