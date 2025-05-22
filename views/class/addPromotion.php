<?php
require_once "controllers/classController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$classId = $_REQUEST["id"];
$classController = new ClassController();
$class = $classController->readDetailed($classId);

if($class == null || $class->type === "Promoted"){
    header("location:index.php?table=class&action=list");
    exit();
}

$availablePromotions = $classController->readAvailablePromotions($classId);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Showing available promotions for Class "<?= $class->name ?>"</h3>
    </div>
    <?php 
    if($class->type === "Starter"){ 
        if(count($availablePromotions) > 0){ ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($availablePromotions as $classPromotion) {?>
                        <tr>
                            <th scope="row">
                                <?= $classPromotion->id ?>
                            </th>
                            <td><?= $classPromotion->name ?></td>
                            <td>
                                <a class="btn btn-success" href="index.php?table=class&action=storePromotion&starterId=<?= $class->id ?>&promotedId=<?= $classPromotion->id ?>"><i class="fa fa-trash"></i> Add</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php }else {
            echo "No more promotions for this class are available<br>";
        }
    }
    ?>
    <br>
    <a href="index.php?table=class&action=show&id=<?= $class->id ?>" class="btn btn-primary">Return to list</a>
    <table class="table table-light table-hover">
</main>