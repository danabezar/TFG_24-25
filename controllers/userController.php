<?php
require_once "models/userModel.php";
require_once "assets/php/functions.php";

class UserController{
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    /*
     * TODO: Add comment
    */
    public function create(array $userDataArray): void{
        $error = false;
        $errors = [];

        //In case we need to add error and form data, we erase the previously registered ones
        $_SESSION["errors"] = [];
        $_SESSION["formData"] = [];

        // /*****************************************************/
        // // Field format checks
        // // if (!nombreUsuarioValido($arrayUser["nick"])) {
        // //     $error = true;
        // //     $errores["usuario"][] = "El usuario tiene un formato incorrecto";
        // // }
        // /*****************************************************/

        //Empty field checks
        $nonNullableFields = ["username", "password"];
        $foundNullFields = areThereNullFields($nonNullableFields, $userDataArray);

        if (count($foundNullFields) > 0) {
            $error = true;
            for ($i = 0; $i < count($foundNullFields); $i++) {
                $errors[$foundNullFields[$i]][] = "The {$foundNullFields[$i]} field is null";
            }
        }

        //Unique fields checks
        $uniqueFields = ["username"];

        foreach ($uniqueFields as $uniqueField) {
            if ($this->model->exists($uniqueField, $userDataArray[$uniqueField])) {
                $error = true;
                $errors[$uniqueField][] = "Value {$userDataArray[$uniqueField]} for {$uniqueField} is already registered";
            }
        }


        //Final check. If no errors were found, the insertion is made
        if (!$error) {
            $id = $this->model->insert($userDataArray);
        } else {
            $id = null;
        }

        if ($id == null) {
            $_SESSION["errors"] = $errors;
            $_SESSION["formData"] = $userDataArray;
            header("location:index.php?table=user&action=create&error=true");
            exit();
        } else {
        //     /*****************************************************/
        //     //TODO: Change this so the units are added. Adapt the already existing previous code
        //     // //Se obtienen todos los digimon iniciales
        //     // $digimonIniciales = $this->digimonController->buscar("level", "equals", "1");
        //     // $digimonParaUsuario = [];

        //     // //Buscamos aleatoriamente 3 digimon aleatorios distintos
        //     // while(count($digimonParaUsuario) < 3){
        //     //     $seleccionAleatoria = rand(0, (count($digimonIniciales)-1));
        //     //      if(!in_array($digimonIniciales[$seleccionAleatoria]->id, $digimonParaUsuario)){
        //     //          $digimonParaUsuario[]=$digimonIniciales[ $seleccionAleatoria]->id;
        //     //     }
        //     // }

        //     // //Tras encontrar los 3 digimon distintos, se asignan al usuario recién creado
        //     // foreach ($digimonParaUsuario as $idDigimonInicial) {
        //     //     $this->userDigimonController->crear($id, $idDigimonInicial);
        //     // }
        //     /*****************************************************/

            unset($_SESSION["errors"]);
            unset($_SESSION["formData"]);
            header("location:index.php?table=user&action=show&id=".$id);
            exit();
        }
    }

    /*
     * TODO: Add comment
    */
    public function read(int $id): stdClass | null {
        return $this->model->findById($id);
    }

    /*
     * TODO: Add comment
    */
    public function list(bool $canBeErased = false): array | null {
        $users = $this->model->readAll();
        return $users;
    }

    public function delete(int $userId){
        $this->model->delete($userId);
        header("location:index.php?table=user&action=list");
        exit();
    }

    /*
     * TODO: Add comment
    */
    public function search(
        string $field = "username", 
        string $searchType = "contains", 
        string $searchString = "", 
        bool  $canBeErased = false
        ): array | null{
            $users = $this->model->search($field, $searchType, $searchString);
            return $users;
    }
}