<?php
require_once "models/unitModel.php";
require_once "assets/php/functions.php";

class UnitController{
    private $model;

    public function __construct(){
        $this->model = new UnitModel();
    }

    /**
     * Inserts a new entry in the database's "unit" table
     * 
     * @param array $unitDataArray Contains the values for each field in the table
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
            "speed_base", 
            "luck_base", 
            "defense_base", 
            "resistance_base", 
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
             //Format checks
            $dataFormatIsValid = true;
            $baseCheckList = [
                "health_base", 
                "strength_base", 
                "magic_base", 
                "skill_base",
                "speed_base", 
                "luck_base", 
                "defense_base", 
                "resistance_base"
            ];
            $growthCheckList = [
                "health_growth", 
                "strength_growth", 
                "magic_growth", 
                "skill_growth", 
                "speed_growth", 
                "luck_growth", 
                "defense_growth", 
                "resistance_growth" 
            ];

            $dataFormatIsValid = 
            isValidFormDataFormat("level", ["level_base"], $unitDataArray) && 
            isValidFormDataFormat("base", $baseCheckList, $unitDataArray) && 
            isValidFormDataFormat("growth", $growthCheckList, $unitDataArray);

            if($dataFormatIsValid) {
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
            }else {
                header("location:index.php?table=unit&action=create&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=unit&action=create&error=true");
            exit();
        }
    }

    /**
     * Finds an entry in the database's "unit" table and returns its info, with extra details
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function read(int $id): stdClass | null {
        return $this->model->findByIdDetailed($id);
    }

    /**
     * List every entry in the database's "unit" table and returns their info, with extra details
     * 
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function list(bool $canBeErased = false): array | null {
        $units = $this->model->readAllDetailed();
        return $units;
    }

    /**
     * Updates the data from a particular row in the "unit" table
     * 
     * @param array $unitDataArray Array with the new values for the fields in the table
     */
    public function update(array $unitDataArray): void{
        $nonNullableFields = [
            "name", 
            "class", 
            "level_base",
            "health_base", 
            "strength_base", 
            "magic_base", 
            "skill_base", 
            "speed_base", 
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
           //Format checks
            $dataFormatIsValid = true;
            $baseCheckList = [
                "health_base", 
                "strength_base", 
                "magic_base", 
                "skill_base",
                "speed_base", 
                "luck_base", 
                "defense_base", 
                "resistance_base"
            ];
            $growthCheckList = [
                "health_growth", 
                "strength_growth", 
                "magic_growth", 
                "skill_growth", 
                "speed_growth", 
                "luck_growth", 
                "defense_growth", 
                "resistance_growth" 
            ];

            $dataFormatIsValid = 
            isValidFormDataFormat("level", ["level_base"], $unitDataArray) && 
            isValidFormDataFormat("base", $baseCheckList, $unitDataArray) && 
            isValidFormDataFormat("growth", $growthCheckList, $unitDataArray);
            
            if($dataFormatIsValid) {
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
        }else{
            header("location:index.php?table=unit&action=update&id=" . $unitDataArray["id"] . "&error=true");
            exit();
        }
    }

    /**
     * Deletes a row from the "unit" table
     * 
     * @param int $unitId ID of the row which will be deleted
     */
    public function delete(int $unitId){
        $this->model->delete($unitId);
        header("location:index.php?table=unit&action=list");
        exit();
    }

    /**
     * Returns a list of rows from the "unit" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null $units Returns a list of rows who match the condition, or null if none did
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