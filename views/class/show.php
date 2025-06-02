<?php
require_once "controllers/classController.php";

if (!isset($_REQUEST['id'])) {
    header("location:index.php");
    exit();
}

$id = $_REQUEST["id"];
$classController = new ClassController();
$class = $classController->readDetailed($id);

if($class == null){
    header("location:index.php?table=class&action=list");
    exit();
}

$classSkills = $classController->readSkills($id);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Showing Class "<?= $class->name ?>"'s Information</h3>
    </div>
    <div id="content">
        <div class="card">
            <div>
                <div>
                    <h5 class="card-title"><?= $class->id . ": " . $class->name ?></h5>
                </div>
                <hr>
                <p class="card-text">
                    Class ID: <strong><?= $class->id ?></strong><br>
                    Name: <strong><?= $class->name ?></strong><br>
                    Type: <strong><?= $class->type ?></strong><br>
                    Damage Type: <strong>
                        <?php 
                        if($class->dmg_type === 0){
                            echo "Physical";
                        }else{
                            echo "Magical";
                        } 
                        ?>
                    </strong><br>
                    Health Growth: <strong><?= $class->health_growth ?>%</strong> <br>
                    Strength Growth: <strong><?= $class->strength_growth ?>%</strong> <br>
                    Magic Growth: <strong><?= $class->magic_growth ?>%</strong> <br>
                    Skill Growth: <strong><?= $class->skill_growth ?>%</strong> <br>
                    Speed Growth: <strong><?= $class->speed_growth ?>%</strong> <br>
                    Luck Growth: <strong><?= $class->luck_growth ?>%</strong> <br>
                    Defense Growth: <strong><?= $class->defense_growth ?>%</strong> <br>
                    Resistance Growth: <strong><?= $class->resistance_growth ?>%</strong> <br>
                </p>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">Current Skills</h1>
        <a class="btn btn-primary" href="index.php?table=class&action=addSkill&id=<?= $id ?>" >Add skill</a>
    </div>
    <div>
        <?php
        if (count($classSkills) <= 0) {
            echo " No data to show";
        }
        else { ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Attribute</th>
                        <th scope="col">Value</th>
                        <th scope="col">Lvl Req.</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classSkills as $classSkill) {
                        $id = $classSkill->id;
                    ?>
                        <tr>
                            <th scope="row">
                                <?= $classSkill->id ?>
                            </th>
                            <td><?= $classSkill->name ?></td>
                            <td><?= $classSkill->type ?></td>
                            <td><?= $classSkill->attribute ?></td>
                            <td><?= $classSkill->value ?></td>
                            <td><?= $classSkill->level_required ?></td>
                            <td>
                                <a class="btn btn-danger" href="index.php?table=class&action=deleteSkill&id=<?= $class->id ?>&skillId=<?= $classSkill->id ?>"><i class="fa fa-trash"></i> Delete</a>
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
    <?php 
    if($class->type === "Starter"){?>
        <br>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h5">Current Promotions</h1>
            <a class="btn btn-primary" href="index.php?table=class&action=addPromotion&id=<?= $class->id ?>" >Add promotion</a>
        </div>
        <?php 
        $classPromotions = $classController->readPromotions($class->id);
        
        if(count($classPromotions) > 0){ ?>
            <table class="table table-light table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
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
                    <?php foreach ($classPromotions as $classPromotion) {?>
                        <tr>
                            <th scope="row">
                                <?= $classPromotion->promoted_id ?>
                            </th>
                            <td><?= $classPromotion->name ?></td>
                            <td><?= $classPromotion->health_growth ?></td>
                            <td><?= $classPromotion->strength_growth ?></td>
                            <td><?= $classPromotion->magic_growth ?></td>
                            <td><?= $classPromotion->skill_growth ?></td>
                            <td><?= $classPromotion->speed_growth ?></td>
                            <td><?= $classPromotion->luck_growth ?></td>
                            <td><?= $classPromotion->defense_growth ?></td>
                            <td><?= $classPromotion->resistance_growth ?></td>
                            <td>
                                <a class="btn btn-danger" href="index.php?table=class&action=deletePromotion&starterId=<?= $class->id ?>&promotedId=<?= $classPromotion->promoted_id ?>"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php }else {
            echo "No promotions for this class were found<br>";
        }
    }
    ?>
    <br>
    <a href="index.php?table=class&action=list" class="btn btn-primary">Return to list</a>
    <table class="table table-light table-hover">
</main>