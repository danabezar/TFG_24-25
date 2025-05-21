<?php
require_once "assets/php/functions.php";
require_once "controllers/userController.php";
require_once "controllers/unitController.php";
require_once "controllers/userUnitController.php";

if(!isset($_REQUEST["userId"])){
    header("location:index.php?table=user&action=list");
    exit();
}

$userId = $_REQUEST["userId"];
$userController = new UserController();
$user = $userController->read($userId);

if($user == null){
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

$unitController = new UnitController();

$userUnitController = new UserUnitController();
$units = $userUnitController->listAvailableForUser($user->id);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Adding a new Unit to <?= $user->username ?></h3>
    </div>
    <div id="content">
        <div class="alert alert-danger" style="display: <?= $errorVisibility ?>;"> <?= $errorString ?> </div>
        <?php if($units != null && count($units) > 0){ ?>
        <form id="insertion-form" action="index.php?table=user&action=storeUnit&event=add" method="POST">
            <input type="hidden" id="userId" name="userId" value="<?= $user->id ?>">
            <div class="form-group">
                <label for="unitId">Unit</label>
                <select id="unitId" name="unitId">
                    <?php 
                    foreach($units as $unit){
                        $isSelected = "";

                        if (isset($_SESSION["formData"]["unitId"])){
                            if($_SESSION["formData"]["unitId"] == $unit->id){
                                $isSelected = "selected";
                            }
                        }
                        echo "<option value=" . $unit->id . " " . $isSelected .">" . $unit-> id . " - ". $unit->name . "</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add</button>
            <?php } else { ?>
                <div>
                    <p>No units can be added</p>
                </div>
            <?php }?>
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