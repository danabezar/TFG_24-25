<?php
require_once "controllers/classController.php";

$controller = new ClassController();
$classes = $controller->listDetailed(true);
$tableVisibility = "hidden";
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">List of Classes</h1>
    </div>
    <div id="content">
        <?php
        if (count($classes) <= 0) {
            echo " No data to show";
        }
        else { ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">HP(%)</th>
                        <th scope="col">STR(%)</th>
                        <th scope="col">MAG(%)</th>
                        <th scope="col">SKL(%)</th>
                        <th scope="col">SPD(%)</th>
                        <th scope="col">LCK(%)</th>
                        <th scope="col">DEF(%)</th>
                        <th scope="col">RES(%)</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class) {
                        $id = $class->id;
                    ?>
                        <tr>
                            <th scope="row">
                                <?= $class->id ?>
                            </th>
                            <td><?= $class->name ?></td>
                            <td><?= $class->type ?></td>
                            <td><?= $class->health_growth ?></td>
                            <td><?= $class->strength_growth ?></td>
                            <td><?= $class->magic_growth ?></td>
                            <td><?= $class->skill_growth ?></td>
                            <td><?= $class->speed_growth ?></td>
                            <td><?= $class->luck_growth ?></td>
                            <td><?= $class->defense_growth ?></td>
                            <td><?= $class->resistance_growth ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?table=class&action=show&id=<?= $id ?>"><i class="fa fa-eye"></i> Show</a>
                                <a class="btn btn-success" href="index.php?table=class&action=update&id=<?= $id ?>"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-primary" href="index.php?table=class&action=editImages&id=<?= $id ?>"><i class="fa fa-camera"></i> Images</a>
                                <?php
                                $deletionAllowed = "";
                                $deletionRoute = "index.php?table=class&action=delete&id=" . $class->id;
                                if ((isset($class->canBeErased) && $class->canBeErased == false)) {
                                    $deletionAllowed = "disabled";
                                    $deletionRoute = "#";
                                }
                                ?>
                                <a class="btn btn-danger <?= $deletionAllowed ?>" href="<?= $deletionRoute ?>"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
        ?>
    </div>
    <table class="table table-light table-hover">
</main>