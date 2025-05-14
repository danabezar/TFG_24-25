<?php
require_once "models/unitModel.php";
require_once "assets/php/functions.php";

class UnitController{
    private $model;

    public function __construct(){
        $this->model = new UnitModel();
    }

    /*
     * TODO: Add comment
    */
    public function create(array $unitDataArray): void{
        $nonNullableFields = [
            "name", 
            "class", 
            "level_base",
            "health_base", 
            "strength_base", 
            "magic_base", 
            "skill_base", 
            "luck_base", 
            "defense_base", 
            "resistance_base", 
            "magic_base", 
            "health_growth", 
            "strength_growth", 
            "magic_growth", 
            "skill_growth", 
            "speed_growth", 
            "luck_growth", 
            "defense_growth", 
            "resistance_growth"    
        ];
        $uniqueFields = ["name"];
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $unitDataArray, $this->model);
        
        if($dataIsValid) {
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
            $newUnitId = $this->model->insert($unitDataArray);

            if($newUnitId != null){
                $newUnitBasesId = $this->model->addBases($newUnitId, $unitDataArray);

                if($newUnitBasesId != null){
                    $newUnitGrowthsId = $this->model->addGrowths($newUnitId, $unitDataArray);

                    if($newUnitGrowthsId != null){
                        header("location:index.php?table=unit&action=show&id=" . $newUnitId);
                        exit();
                    }else{
                        header("location:index.php?table=unit&action=create&error=true");
                        exit();
                    }
                }else{
                    header("location:index.php?table=unit&action=create&error=true");
                    exit();
                }
            }else{
                header("location:index.php?table=unit&action=create&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=unit&action=create&error=true");
            exit();
        }
    }

    /*
     * TODO: Add comment
    */
    public function read(int $id): stdClass | null {
        return $this->model->findByIdDetailed($id);
    }

    /*
     * TODO: Add comment
    */
    public function list(bool $canBeErased = false): array | null {
        $units = $this->model->readAllDetailed();
        return $units;
    }


    //TODO: Hacer esto
    public function update(array $unitDataArray): void{
        $nonNullableFields = [
            "name", 
            "class", 
            "level_base",
            "health_base", 
            "strength_base", 
            "magic_base", 
            "skill_base", 
            "luck_base", 
            "defense_base", 
            "resistance_base", 
            "magic_base", 
            "health_growth", 
            "strength_growth", 
            "magic_growth", 
            "skill_growth", 
            "speed_growth", 
            "luck_growth", 
            "defense_growth", 
            "resistance_growth"    
        ];
        $uniqueFields = [];
        if($unitDataArray["name"] != $unitDataArray["previousName"]){
            $uniqueFields[] = "name";
        }
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $unitDataArray, $this->model);
        
        if($dataIsValid) {
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
            $successfulUnitUpdate = $this->model->update($unitDataArray["id"], $unitDataArray);

            if($successfulUnitUpdate){
                $successfulUnitBasesUpdate = $this->model->updateBases($unitDataArray["id"], $unitDataArray);

                if($successfulUnitBasesUpdate){
                    $successfulUnitGrowthsUpdate = $this->model->updateGrowths($unitDataArray["id"], $unitDataArray);

                    if($successfulUnitGrowthsUpdate){
                        header("location:index.php?table=unit&action=show&id=" . $unitDataArray["id"]);
                        exit();
                    }else{
                        header("location:index.php?table=unit&action=update&id=" . $unitDataArray["id"] . "&error=true");
                        exit();
                    }
                }else{
                    header("location:index.php?table=unit&action=update&id=" . $unitDataArray["id"] . "&error=true");
                    exit();
                }
            }else{
                header("location:index.php?table=unit&action=update&id=" . $unitDataArray["id"] . "&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=unit&action=update&id=" . $unitDataArray["id"] . "&error=true");
            exit();
        }
    }

    public function delete(int $unitId){
        $this->model->delete($unitId);
        header("location:index.php?table=unit&action=list");
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
            $units = $this->model->search($field, $searchType, $searchString);
            return $units;
    }

    
}