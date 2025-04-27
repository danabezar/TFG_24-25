<?php
ob_start(); //Allow Session usage

require_once "config/sessionControl.php";
require_once "router/router.php";
// $headerMessage = "MENÚ DE INICIO";

$view = router();

// switch($vista){
//     case 'views/inicio.php':
//         $headerMessage = "MENÚ DE INICIO";
//         break;
//     case 'views/team/team.php':
//         case 'views/team/details.php':
//             $headerMessage = "MIS DIGIMON";
//             break;    
//     case 'views/team/selection.php':
//         $headerMessage = "SELECCIÓN DE EQUIPO";
//         break;
//     case 'views/team/digivolution.php':
//     case 'views/team/watchDigivolution.php':
//         $headerMessage = "DIGIEVOLUCIÓN";
//         break;
//     case 'views/match/pickmatch.php':
//         $headerMessage = "SELECCIÓN DE OPONENTE";
//         break;
//     case 'views/match/batalla.php':
//         $headerMessage = "BATALLA";
//         break;
//     case 'views/match/storeMatch.php':
//     case 'views/match/watchMatch.php':
//         $headerMessage = "COMBATE";
//         break;
//     case 'views/match/results':
//         $headerMessage = "RESULTADOS";
//     default:
//         break;
// }

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