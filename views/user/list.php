<?php
require_once "controllers/userController.php";

$controller = new UserController();
$users = $controller->list(true);
$tableVisibility = "hidden";
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">List of Users</h1>
    </div>
    <div id="content">
        <?php
        if (count($users) <= 0) {
            echo " No data to show";
        }
        else { ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) {
                        $id = $user->id;
                    ?>
                        <tr>
                            <th scope="row"><?= $user->id ?></th>
                            <td><?= $user->username ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?table=user&action=show&id=<?= $id ?>"><i class="fa fa-eye"></i> Show</a>
                                <a class="btn btn-danger" href="index.php?table=user&action=delete&id=<?= $id ?>"><i class="fa fa-trash"></i> Delete</a>
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