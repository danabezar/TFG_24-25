<?php
require_once "controllers/userController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST['id'];
$controller = new UserController();
$user = $controller->read($id);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Showing User <?= $user->username ?>'s Information</h3>
        <a class="btn btn-primary" href="index.php?table=user&action=addUnit&userId=<?= $id ?>" >Add unit</a>
    </div>
    <div id="content">
        <div class="card">
            <div>
                <div>
                    <h5 class="card-title"><?= $user->id . ": " . $user->username ?></h5>
                </div>
                <hr>
                <p class="card-text">
                    User's ID: <strong><?= $user->id ?></strong> <br>
                    User's Name: <strong><?= $user->username ?></strong> <br>
                    Victories: <strong><?= $user->victories ?></strong> <br>
                    Losses: <strong><?= $user->losses ?></strong> <br>
                </p>
            </div>
        </div>
        <a href="index.php?table=user&action=list" class="btn btn-primary">Return to list</a>
    </div>
</main>