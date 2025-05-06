<?php
require_once "controllers/skillController.php";

$controller = new SkillController();
$skills = $controller->list(true);
$tableVisibility = "hidden";
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">List of Skills</h1>
    </div>
    <div id="content">
        <?php
        if (count($skills) <= 0) {
            echo " No data to show";
        }
        else { ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Aff. Attribute</th>
                        <th scope="col">Value</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($skills as $skill) {
                        $id = $skill->id;
                    ?>
                        <tr>
                            <th scope="row">
                                <?= $skill->id ?>
                            </th>
                            <td><?= $skill->name ?></td>
                            <td><?= $skill->type ?></td>
                            <td><?= $skill->attribute ?></td>
                            <td><?= $skill->value ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?table=skill&action=show&id=<?= $id ?>"><i class="fa fa-eye"></i> Show</a>
                                <a class="btn btn-success" href="index.php?table=skill&action=update&id=<?= $id ?>"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-danger" href="index.php?table=skill&action=delete&id=<?= $id ?>"><i class="fa fa-trash"></i> Delete</a>
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