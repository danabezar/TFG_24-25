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
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Write a password" value="<?= $_SESSION["formData"]["password"] ?? "" ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <a class="btn btn-danger" href="index.php?table=user&action=list">Cancel</a>
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