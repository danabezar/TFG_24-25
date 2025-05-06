<?php
require_once "controllers/skillController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST['id'];
$controller = new SkillController();
$skill = $controller->read($id);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Showing Skill "<?= $skill->name ?>"'s Information</h3>
    </div>
    <div id="content">
        <div class="card">
            <div>
                <div>
                    <h5 class="card-title"><?= $skill->id . ": " . $skill->name ?></h5>
                </div>
                <hr>
                <p class="card-text">
                    Skill ID: <strong><?= $skill->id ?></strong><br>
                    Name: <strong><?= $skill->name ?></strong><br>
                    Type: <strong><?= $skill->type ?></strong><br>
                    Affected Attribute: <strong><?= $skill->attribute ?></strong><br>
                    Value: <strong><?= $skill->value ?></strong><br>
                </p>
            </div>
        </div>
        <a href="index.php?table=skill&action=list" class="btn btn-primary">Return to list</a>
    </div>
</main>