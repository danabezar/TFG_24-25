<?php
function router (){
    $url = $_SERVER["REQUEST_URI"];

    //If nothing is added in the URL, the user is redirected to the home page
    if (substr($url,-1)=="/"){
        return "views/home.php";
    } 

    //If the route format is wrong, the user is redirected to the error page
    if (!strpos($url,"index.php")){
        return "views/404.php";
    }

    //If no table has been defined, the user is redirected to the home page
    if (!isset($_REQUEST["table"])){
        return "views/home.php";
    }

    $table = $_REQUEST["table"];

    //TODO: Change all of this
    $tables = [
        "user" => [
            "create" => "create.php",
            "store" => "store.php",
            "show" => "show.php",
            "list" => "list.php",
            "delete" => "delete.php"
        ],
        "class" => [
            "create" => "create.php",
            "store" => "store.php",
            "show" => "show.php",
            "list" => "list.php",
            "update" => "update.php",
            "delete" => "delete.php",
            "search" => "search.php"
        ],
        "skill" => [
            "create" => "create.php",
            "store" => "store.php",
            "show" => "show.php",
            "list" => "list.php",
            "update" => "update.php",
            "delete" => "delete.php",
            "search" => "search.php"
        ],
        "unit" => [
            "create" => "create.php",
            "store" => "store.php",
            "show" => "show.php",
            "list" => "list.php",
            "update" => "update.php",
            "delete" => "delete.php"
        ]
    ];

    //If the table introduced is not registered, the user is redirected to the home page
    if (!isset($tables[$table])){
        return"views/home.php";
    }

    //If no action is defined, the user is redirected to the home page
    if (!isset ($_REQUEST["action"])){
        return "views/home.php";
    }

    $action = $_REQUEST["action"];

    //If the action introduced is not registered, the user is redirected to the home page
    if (!isset($tables[$table][$action])){
        return "views/home.php"; 
    }

    return "views/{$table}/{$tables[$table][$action]}";
}

function generateJSImports(string $route): string{
    $lastPosition = strrpos($route, "/");
    $currentRoute = substr($route, 0, $lastPosition);
    $routeParts = (explode("/", $route));
    $file = end($routeParts);
    $fileParts = explode(".", $file);
    $fileJS = $fileParts[0] . ".js";
    $path = $currentRoute . "/js/" . $fileJS;
    return $path;
}
?>