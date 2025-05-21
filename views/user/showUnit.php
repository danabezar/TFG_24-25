<?php
require_once "controllers/userController.php";
require_once "controllers/userUnitController.php";

if (!isset($_REQUEST["userId"], $_REQUEST['userUnitId'])) {
    header("location:index.php?table=user&action=list");
    exit();
}

$userId = $_REQUEST["userId"];
$userController = new UserController();
$user = $userController->read($userId);

$userUnitId = $_REQUEST["userUnitId"];
$userUnitController = new UserUnitController();
$userUnit = $userUnitController->read($userUnitId);

if($user == null || $userUnit == null){
    header("location:index.php?table=user&action=list");
    exit();
}

$userUnitSkills = $userUnitController->getSkillsById($userUnit->id);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3"><?= $user->username?>'s "<?= $userUnit->name ?>" Information</h3>
    </div>
    <div id="content">
        <div class="card">
            <div>
                <div>
                    <h5 class="card-title"><?= $userUnit->id . ": " . $userUnit->name ?></h5>
                </div>
                <hr>
                <p class="card-text">
                    User's Unit ID: <strong><?= $userUnit->id ?></strong> <br>
                    Name: <strong><?= $userUnit->name ?></strong> <br>
                    Class: <strong><?= $userUnit->class ?></strong> <br>
                    Level: <strong><?= $userUnit->level ?></strong> <br>
                    Health: <strong><?= $userUnit->health_stat ?></strong> <br>
                    Strength: <strong><?= $userUnit->strength_stat ?></strong> <br>
                    Magic: <strong><?= $userUnit->magic_stat ?></strong> <br>
                    Skill: <strong><?= $userUnit->skill_stat ?></strong> <br>
                    Speed: <strong><?= $userUnit->speed_stat ?></strong> <br>
                    Luck: <strong><?= $userUnit->luck_stat ?></strong> <br>
                    Defense: <strong><?= $userUnit->defense_stat ?></strong> <br>
                    Resistance: <strong><?= $userUnit->resistance_stat ?></strong> <br>
                    Health Growth: <strong><?= $userUnit->health_growth ?>%</strong> <br>
                    Strength Growth: <strong><?= $userUnit->strength_growth ?>%</strong><br>
                    Magic Growth: <strong><?= $userUnit->magic_growth ?></strong>%<br>
                    Skill Growth: <strong><?= $userUnit->skill_growth ?></strong>%<br>
                    Speed Growth: <strong><?= $userUnit->speed_growth ?></strong>%<br>
                    Luck Growth: <strong><?= $userUnit->luck_growth ?></strong>%<br>
                    Defense Growth: <strong><?= $userUnit->defense_growth ?></strong>%<br>
                    Resistance Growth: <strong><?= $userUnit->resistance_growth ?></strong>%<br>
                </p>
            </div>
        </div>
        <a href="index.php?table=user&action=show&id=<?= $user->id?>" class="btn btn-primary">Return to user</a>
    </div>
    <br>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h5">Current Skills</h1>
        <a class="btn btn-primary" href="index.php?table=user&action=addUnitSkill&userId=<?= $user->id?>&userUnitId=<?= $userUnit->id ?>">Add skill</a>
    </div>
    <div>
        <?php
        if (count($userUnitSkills) <= 0) {
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
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userUnitSkills as $userUnitSkill) {?>
                        <tr>
                            <th scope="row">
                                <?= $userUnitSkill->id ?>
                            </th>
                            <td><?= $userUnitSkill->name ?></td>
                            <td><?= $userUnitSkill->type ?></td>
                            <td><?= $userUnitSkill->attribute ?></td>
                            <td><?= $userUnitSkill->value ?></td>
                            <td>
                                <a class="btn btn-danger" href="index.php?table=user&action=deleteUnitSkill&userId=<?= $user->id ?>&userUnitId=<?= $userUnit->id ?>&skillId=<?= $userUnitSkill->skill_id?>"><i class="fa fa-trash"></i> Delete</a>
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
</main>