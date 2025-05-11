<?php
require_once "controllers/unitController.php";

$controller = new UnitController();
$units = $controller->list(true);
$tableVisibility = "hidden";
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">List of Units</h1>
    </div>
    <div id="content">
        <?php
        if (count($units) <= 0) {
            echo " No data to show";
        }
        else { ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Class</th>
                        <th scope="col">Lvl</th>
                        <th scope="col">HP</th>
                        <th scope="col">STR</th>
                        <th scope="col">MAG</th>
                        <th scope="col">SKL</th>
                        <th scope="col">SPD</th>
                        <th scope="col">LCK</th>
                        <th scope="col">DEF</th>
                        <th scope="col">RES</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($units as $unit) {
                        $id = $unit->id;
                    ?>
                        <tr>
                            <th scope="row">
                                <?= $unit->id ?>
                            </th>
                            <td><?= $unit->name ?></td>
                            <td><?= $unit->class ?></td>
                            <td><?= $unit->base_level ?></td>
                            <td><?= $unit->base_health ?></td>
                            <td><?= $unit->base_strength ?></td>
                            <td><?= $unit->base_magic ?></td>
                            <td><?= $unit->base_skill ?></td>
                            <td><?= $unit->base_speed ?></td>
                            <td><?= $unit->base_luck ?></td>
                            <td><?= $unit->base_defense ?></td>
                            <td><?= $unit->base_resistance ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?table=unit&action=show&id=<?= $id ?>"><i class="fa fa-eye"></i> Show</a>
                                <a class="btn btn-success" href="index.php?table=unit&action=update&id=<?= $id ?>"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-danger" href="index.php?table=unit&action=delete&id=<?= $id ?>"><i class="fa fa-trash"></i> Delete</a>
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