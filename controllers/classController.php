<?php
require_once "models/classModel.php";
require_once "assets/php/functions.php";

class ClassController{
    private $model;

    public function __construct(){
        $this->model = new ClassModel();
    }

    /*
     * TODO: CHANGE
    */
    public function create(array $classDataArray): void{
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
        $nonNullableFields = [
            "name", "type", 
            "health_growth", "strength_growth", "magic_growth", "skill_growth",
            "speed_growth", "luck_growth", "defense_growth", "resistance_growth"    
        ];
        $foundNullFields = areThereNullFields($nonNullableFields, $classDataArray);

        if (count($foundNullFields) > 0) {
            $error = true;
            for ($i = 0; $i < count($foundNullFields); $i++) {
                $errors[$foundNullFields[$i]][] = "The {$foundNullFields[$i]} field is null";
            }
        }

        //Unique fields checks
        $uniqueFields = ["name"];

        foreach ($uniqueFields as $uniqueField) {
            if ($this->model->exists($uniqueField, $classDataArray[$uniqueField])) {
                $error = true;
                $errors[$uniqueField][] = "Value {$classDataArray[$uniqueField]} for {$uniqueField} is already registered";
            }
        }


        //Final check. If no errors were found, the insertion is made
        if (!$error) {
            $classId = $this->model->insert($classDataArray);
        } else {
            $classId = null;
        }

        if ($classId == null) {
            $_SESSION["errors"] = $errors;
            $_SESSION["formData"] = $classDataArray;
            header("location:index.php?table=class&action=create&error=true");
            exit();
        } else {
            $classGrowthsId = $this->model->addGrowths($classId, $classDataArray);

            if($classGrowthsId == null){
                $_SESSION["errors"] = $errors;
                $_SESSION["formData"] = $classDataArray;
                header("location:index.php?table=class&action=create&error=true");
                exit();
            }else{
                unset($_SESSION["errors"]);
                unset($_SESSION["formData"]);
                header("location:index.php?table=class&action=show&id=".$classId);
                exit();
            }
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
    public function readGrowths(int $id): stdClass | null {
        return $this->model->findByIdGrowths($id);
    }

    /*
     * TODO: Add comment
    */
    public function readSkills(int $id): stdClass | null {
        return $this->model->findByIdSkills($id);
    }

    /*
     * TODO: Add comment
    */
    public function readDetailed(int $id): stdClass | null {
        return $this->model->findByIdDetailed($id);
    }

    /*
     * TODO: Add comment
    */
    public function list(bool $canBeErased = false): array | null {
        $classes = $this->model->readAll();
        return $classes;
    }

    /*
     * TODO: Add comment
    */
    public function listDetailed(bool $canBeErased = false): array | null {
        $classes = $this->model->readAllDetailed();
        return $classes;
    }

    /*
     * TODO: Add comment
    */
    public function update(int $classId, array $class): bool{
        return $this->model->update($classId, $class);
    }

    /*
     * TODO: Add comment
    */
    public function delete(int $classId): void{
        $this->model->delete($classId);
        header("location:index.php?table=class&action=list");
        exit();
    }

    /*
     * TODO: Add comment
    */
    public function search(
        string $field = "name", 
        string $searchType = "contains", 
        string $searchString = "", 
        bool  $canBeErased = false
        ): array | null{
            $classes = $this->model->search($field, $searchType, $searchString);
            return $classes;
    }

    /*
     * TODO: Add comment
    */
    public function exists(string $field, string $fieldValue): bool {
        return $this->model->exists($field, $fieldValue);
    }

    /*
     * TODO: Add comment
    */
    public function addGrowths(int $classId, array $class): int | null {
        return $this->model->addGrowths($classId, $class);
    }

    /*
     * TODO: Add comment
    */
    public function updateGrowths(int $classId, array $class): bool {
        return $this->model->updateGrowths($classId, $class);
    }

    /*
     * TODO: Add comment
    */
    public function removeGrowths(int $classId): void {
        $this->model->removeGrowths($classId);
        header("location:index.php?table=class&action=show&id=" . $classId);
        exit();
    }

    /*
     * TODO: Add comment
    */
    public function addSkill(int $classId, int $skillId, int $requiredLevel = 10): int | null {
        return $this->model->addSkill($classId, $skillId, $requiredLevel);
    }

    /*
     * TODO: Add comment
    */
    public function removeSkill(int $classId, int $skillId): void {
        $this->model->removeSkill($classId, $skillId);
        header("location:index.php?table=class&action=show&id=" . $classId);
        exit();
    }
}