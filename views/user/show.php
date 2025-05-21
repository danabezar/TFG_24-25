<?php
require_once "controllers/userController.php";
require_once "controllers/userUnitController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST["id"];
$userController = new UserController();
$user = $userController->read($id);

if($user == null){
    header("location:index.php?table=user&action=list");
    exit();
}

$userUnitController = new UserUnitController();
$userUnits = $userUnitController->listByUserId($user->id);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Showing User <?= $user->username ?>'s Information</h3>
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
    <br>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">Current Units</h1>
        <a class="btn btn-primary" href="index.php?table=user&action=addUnit&userId=<?= $user->id ?>" >Add unit</a>
    </div>
    <br>
    <div>
        <?php
        if (count($userUnits) > 0) { ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Class</th>
                        <th scope="col">Level</th>
                        <th scope="col">Experience</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userUnits as $userUnit) { ?>
                        <tr>
                            <th scope="row">
                                <?= $userUnit->id ?>
                            </th>
                            <td><?= $userUnit->name ?></td>
                            <td><?= $userUnit->class ?></td>
                            <td><?= $userUnit->level ?></td>
                            <td><?= $userUnit->experience ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?table=user&action=showUnit&userId=<?= $user->id?>&userUnitId=<?= $userUnit->id ?>"><i class="fa fa-eye"></i> Show</a>
                                <a class="btn btn-success" href="index.php?table=user&action=updateUnit&userId=<?= $user->id ?>&userUnitId=<?= $userUnit->id ?>"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-danger" href="index.php?table=user&action=deleteUnit&userId=<?= $user->id ?>&userUnitId=<?= $userUnit->id ?>"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php }
        else { 
            echo "This user has no units";
        } ?>
    </div>
    <table class="table table-light table-hover">
</main>