<?php
ob_start(); //Allow Session usage

require_once "config/sessionControl.php";
require_once "router/router.php";

$view = router();

require_once("views/layout/head.php"); ?>
<div class="container-fluid">
    <div class="row">
        <?php
        require_once "views/layout/navbar.php";

        if (!file_exists($view)) echo "Error en index.php con las rutas";
        else require_once($view);
        ?>
    </div>
</div>

<?php 
require_once("views/layout/footer.php");
?>