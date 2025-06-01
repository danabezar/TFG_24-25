<?php
require_once "assets/php/functions.php";
require_once "controllers/skillController.php";

if(!isset($_REQUEST["id"])){
    header("location:index.php?table=skill&action=list");
    exit();
}

$skillId = $_REQUEST["id"];
$skillController = new SkillController();
$skillData = $skillController->read($skillId);

if($skillData == null){
    header("location:index.php?table=skill&action=list");
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
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Editing skill "<?= $skillData->name ?>" with ID <?= $skillData->id ?></h3>
    </div>
    <div id="content">
        <div class="alert alert-danger" style="display: <?= $errorVisibility ?>;"> <?= $errorString ?> </div>
        <form id="insertion-form" action="index.php?table=skill&action=store&event=update" method="POST">
            <input type="hidden" id="id" name="id" value="<?= $skillData->id ?>">
            <input type="hidden" id="previousName" name="previousName" value="<?= $skillData->name ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="Name" placeholder="Write a name for the skill" value="<?= $_SESSION["formData"]["name"] ?? $skillData->name ?>" required>
                <?= isset($errors["name"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "name") . '</div>' : ""; ?>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type-select" name="type">
                    <option value="Stat Boost" 
                        <?php 
                            if (isset($_SESSION["formData"]["type"])){
                                if($_SESSION["formData"]["type"] == "Stat Boost"){
                                    echo "selected";
                                }
                            } else if($skillData->type == "Stat Boost"){
                                echo "selected";
                            }
                        ?>
                    >Stat Boost</option>
                    <option value="Attacker Boost"
                        <?php 
                            if (isset($_SESSION["formData"]["type"])){
                                if($_SESSION["formData"]["type"] == "Attacker Boost"){
                                    echo "selected";
                                }
                            } else if($skillData->type == "Attacker Boost"){
                                echo "selected";
                            }
                        ?>
                    >Attacker Boost</option>
                    <option value="Defender Boost"
                        <?php 
                            if (isset($_SESSION["formData"]["type"])){
                                if($_SESSION["formData"]["type"] == "Defender Boost"){
                                    echo "selected";
                                }
                            } else if($skillData->type == "Defender Boost"){
                                echo "selected";
                            }
                        ?>
                    >Defender Boost</option>
                </select>
                <?= isset($errors["type"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "type") . '</div>' : ""; ?>
            </div>
            <div class="form-group">
                <label for="attribute">Attribute</label>
                <select id="attribute-select" name="attribute">
                    <?php
                        // $currentType = $_SESSION["formData"]["type"] ?? $skillData->type;
                        // switch($currentType){
                        //     case "Stat Boost":
                                $optionValues = ["Health", "Strength", "Magic", "Skill", "Speed", "Luck", "Defense", "Resistance", "Attack", "Hit", "Crit", "Reduction", "Avoid", "Dodge"];
                        //         break;
                        //     default: 
                        //         $optionValues = ["Attack", "Hit"];
                        //         break;
                        // }

                        for($i = 0; $i < count($optionValues); $i++){
                            $newOption = "<option value={$optionValues[$i]} ";

                            if (isset($_SESSION["formData"]["attribute"])){
                                if($_SESSION["formData"]["attribute"] == $optionValues[$i]){
                                    $newOption.= "selected";
                                }
                            } else if($skillData->attribute == $optionValues[$i]){
                                $newOption.= "selected";
                            }

                            $newOption.= ">{$optionValues[$i]}</option>";
                            echo $newOption;
                        }
                    ?>
                </select>
                <?= isset($errors["attribute"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "attribute") . '</div>' : ""; ?>
            </div>
            <div class="form-group">
                <label for="value">Value Modifier</label>
                <input type="number" class="form-control" id="value" name="value" placeholder="5" value="<?= $_SESSION["formData"]["value"] ?? $skillData->value ?>" required>
            </div>
            <?= isset($errors["value"]) ? '<div class="alert alert-danger" role="alert">' . showErrors($errors, "value") . '</div>' : ""; ?>
            <button type="submit" class="btn btn-primary">Edit</button>
            <a class="btn btn-danger" href="index.php?table=skill&action=list">Cancel</a>
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