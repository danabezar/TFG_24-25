<?php
require_once "controllers/classController.php";

$controller = new ClassController();
$showData = false;

$filteredClasses = [];
$previousFilterField = $_REQUEST["filterField"] ?? "";
$previousFilterType = $_REQUEST["filterType"] ?? "";

if (isset($_REQUEST["event"])) {
    $showData = true;
    switch ($_REQUEST["event"]) {
        case "all":
            $filteredClasses = $controller->listDetailed(true);
            break;
        case "filter":
            $filterField = $_REQUEST["filterField"] ?? "";
            $filterType = $_REQUEST["filterType"] ?? "";
            $filterInput = $_REQUEST["filterInput"] ?? "";

            $filteredClasses = $controller->search($filterField, $filterType, $filterInput, true);
            break;
    }
}
?>

<link rel="stylesheet" href="views/digimon/css/search.css">
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Searching Classes</h1>
    </div>
    <div id="content">
        <div>
            <form action="index.php?table=class&action=search&event=filter" method="POST" class="filterForm">
                <div class="form-group filterOptionContainer">
                    <label>Field</label>
                    <select name="filterField" class="form-select">
                        <option value="id" <?= $previousFilterField == "id" ? "selected" : "" ?>>ID</option>
                        <option value="name" <?= $previousFilterField == "name" ? "selected" : "" ?>>Name</option>
                        <option value="type" <?= $previousFilterField == "type" ? "selected" : "" ?>>Type</option>
                    </select>
                </div>
                <div class="form-group filterOptionContainer">
                    <label>Type</label>
                    <select name="filterType" class="form-select">
                        <option value="begins" <?= $previousFilterType == "begins" ? "selected" : "" ?>>Begins...</option>
                        <option value="ends" <?= $previousFilterType == "ends" ? "selected" : "" ?>>Ends...</option>
                        <option value="contains" <?= $previousFilterType == "contains" ? "selected" : "" ?>>Contains...</option>
                        <option value="equals" <?= $previousFilterType == "equals" ? "selected" : "" ?>>Equals...</option>
                    </select>
                </div>
                <div class="form-group filterOptionContainer">
                    <label for="filterInput">Your search</label>
                    <input type="text" required class="form-control" id="filterInput" name="filterInput" placeholder="Add text to filter your search">
                </div>
                <button type="submit" class="btn btn-success" name="applyFilter"><i class="fas fa-search"></i> Search</button>
            </form>
            <form action="index.php?table=digimon&action=search&event=all" method="POST" class="fullSearchForm">
                <button type="submit" class="btn btn-info" name="all"><i class="fas fa-list"></i> List all</button>
            </form>
        
            <?php 
                if(!$showData){
                    if(!isset($_REQUEST["event"])){
                        echo "<div class='alert alert-primary msgWelcome'>Fill the fields above to make your search</div>";
                    }
                }else{ 
                    if(empty($filteredClasses) || count($filteredClasses) < 1){
                        echo "<div class='alert alert-primary msgNoData'>No matching results were found</div>";
                    }else{ ?>
                        <table class="table table-light table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filteredClasses as $filteredClass) :
                                    $id = $filteredClass->id;
                                ?>
                                    <tr>
                                        <th scope="row"><?= $id ?></th>
                                        <td><?= $filteredClass->name ?></td>
                                        <td><?= $filteredClass->type ?></td>
                                        <td>
                                            <a class="btn btn-warning" href="index.php?table=class&action=show&id=<?= $filteredClass->id ?>"><i class="fa fa-eye"></i> Show</a>     
                                            <a class="btn btn-success" href="index.php?table=class&action=update&id=<?= $id ?>"><i class='fas fa-pencil-alt'></i> Edit</a>
                                            <?php
                                            // $deletionAllowed = "";
                                            // $deletionRoute = "index.php?table=class&action=delete&id={$id}";
                                            // if ((isset($digimonInd->esBorrable) && $digimonInd->esBorrable == false) || ($digimonInd->esDigievolucion)) {
                                            //     $habilitado = "disabled";
                                            //     $rutaDeAccion = "#";
                                            // }
                                            ?>
                                            <!-- <a class="btn btn-danger <?= $habilitado ?>" href="<?= $rutaDeAccion ?>"><i class="fa fa-trash"></i> Borrar</a> -->
                                            <a class="btn btn-danger" href="index.php?table=class&action=delete&id=<?= $filteredClass->id ?>"><i class="fa fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    <?php }
                } ?>  
        </div>
    </div>
</main>