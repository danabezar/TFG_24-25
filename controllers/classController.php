<?php
require_once "models/classModel.php";
require_once "assets/php/functions.php";

class ClassController{
    private $model;

    public function __construct(){
        $this->model = new ClassModel();
    }

    /**
     * Inserts a new entry in the database's "class" table
     * 
     * @param array $classDataArray Contains the values for each field in the table
     */
    public function create(array $classDataArray): void{
        $nonNullableFields = [
            "name", 
            "type", 
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
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $classDataArray, $this->model);
        
        if($dataIsValid) {
            //Format checks
            $dataFormatIsValid = true;
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

            $dataFormatIsValid = isValidFormDataFormat("growth", $growthCheckList, $classDataArray);
            if($dataFormatIsValid) {
                $newClassId = $this->model->insert($classDataArray);

                if($newClassId != null){
                    $newClassGrowthsId = $this->model->addGrowths($newClassId, $classDataArray);

                    if($newClassGrowthsId != null){
                        header("location:index.php?table=class&action=show&id=" . $newClassId);
                        exit();
                    }else{
                        header("location:index.php?table=class&action=create&error=true");
                        exit();
                    }
                }else{
                    header("location:index.php?table=class&action=create&error=true");
                    exit();
                }
            }else {
                header("location:index.php?table=class&action=create&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=class&action=create&error=true");
            exit();
        }
    }

    /**
     * Finds an entry in the database's "class" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function read(int $id): stdClass | null {
        return $this->model->findById($id);
    }

    /**
     * Finds an entry in the database's "class_growths" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function readGrowths(int $id): stdClass | null {
        return $this->model->findByIdGrowths($id);
    }

    /**
     * Finds one or more entries in the database's "class_skills" table and returns their info
     * 
     * @param int $id ID of the "class" whose entries will be retrieved
     * 
     * @return array|null Returns an array if one or more matching rows were found, null if there weren't
     */
    public function readSkills(int $id): array | null {
        return $this->model->findByIdSkills($id);
    }

    /**
     * Finds an entry in the database's "class" table and returns its info, with extra details
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function readDetailed(int $id): stdClass | null {
        return $this->model->findByIdDetailed($id);
    }

    /**
     * List every entry in the database's "class" table and returns their info
     * 
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function list(bool $canBeErased = false): array | null {
        $classes = $this->model->readAll();

        if ($canBeErased) {
            foreach ($classes as $class) {
                $class->canBeErased = $this->canBeErased($class);
            }
        }

        return $classes;
    }

    /**
     * List every entry in the database's "class" table and returns their info, with extra details
     * 
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function listDetailed(bool $canBeErased = false): array | null {
        $classes = $this->model->readAllDetailed();

        if ($canBeErased) {
            foreach ($classes as $class) {
                $class->canBeErased = $this->canBeErased($class);
            }
        }

        return $classes;
    }

    /**
     * Updates the data from a particular row in the "class" table
     * 
     * @param int $classId ID of the row whose info will be changed
     * @param array $classDataArray Array with the new values for the fields in the table
     */
    public function update(int $classId, array $classDataArray): void{
        $nonNullableFields = [
            "name", 
            "type", 
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
        if($classDataArray["name"] != $classDataArray["previousName"]){
            $uniqueFields[] = "name";
        }

        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $classDataArray, $this->model);
        
        if($dataIsValid) {
            //Format checks
            $dataFormatIsValid = true;
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

            $dataFormatIsValid = isValidFormDataFormat("growth", $growthCheckList, $classDataArray);
            if($dataFormatIsValid) {
                $succesfulClassUpdate = $this->model->update($classId, $classDataArray);

                if($succesfulClassUpdate){
                    $successfullGrowthsUpdate = $this->model->updateGrowths($classId, $classDataArray);

                    if($successfullGrowthsUpdate){
                        header("location:index.php?table=class&action=show&id=" . $classId);
                        exit();
                    }else{
                        header("location:index.php?table=class&action=update&id={$classId}&error=true");
                        exit();
                    }
                }else{
                    header("location:index.php?table=class&action=update&id={$classId}&error=true");
                    exit();
                }
            }else {
                header("location:index.php?table=class&action=update&id={$classId}&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=class&action=update&id={$classId}&error=true");
            exit();
        }
    }

    /**
     * Deletes a row from the "class" table
     * 
     * @param int $classId ID of the row which will be deleted
     */
    public function delete(int $classId): void{
        $this->model->delete($classId);
        header("location:index.php?table=class&action=list");
        exit();
    }

    /**
     * Returns a list of rows from the "class" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null $classes Returns a list of rows who match the condition, or null if none did
     */
    public function search(
        string $field = "name", 
        string $searchType = "contains", 
        string $searchString = "", 
        bool  $canBeErased = false
        ): array | null{
            $classes = $this->model->search($field, $searchType, $searchString);

            if ($canBeErased) {
                foreach ($classes as $class) {
                    $class->canBeErased = $this->canBeErased($class);
                }
            }

            return $classes;
    }

    /**
     * Checks if a value from a certain field is already registered in the table
     * 
     * @param string $field Name of the field to check
     * @param string $fieldValue Value of the field to check
     * 
     * @return bool Indicates whether the value is already registered or not
     */
    public function exists(string $field, string $fieldValue): bool {
        return $this->model->exists($field, $fieldValue);
    }

    /**
     * Adds a new entry in the "class_growths" table for an already existing "class" row
     * 
     * @param int $classId ID of the row from the "class" table
     * @param array $class Array with the required data for the new entry
     * 
     * @return int|null If the insertion could be made, an int is returned. Else, a null is
     */
    public function addGrowths(int $classId, array $class): int | null {
        return $this->model->addGrowths($classId, $class);
    }

    /**
     * Updates an existing entry in the "class_growths" table
     * 
     * @param int $classId ID of the row from the "class" table
     * @param array $class Array with the required data to update the entry
     * 
     * @return bool Indicates if the update was successful or not
     */
    public function updateGrowths(int $classId, array $class): bool {
        return $this->model->updateGrowths($classId, $class);
    }

    /**
     * Removes an existing row from the "class_growths" table
     * 
     * @param int $classId ID of the row from the "class" table
     */
    public function removeGrowths(int $classId): void {
        $this->model->removeGrowths($classId);
        header("location:index.php?table=class&action=show&id=" . $classId);
        exit();
    }

    /**
     * Inserts a new row in the "class_promotion" table
     * 
     * @param int $starterId The ID of the starter class
     * @param int $promotedId The ID of the promoted class
     * 
     * @return int|null If the insertion was successful, an int will be returned, else a null will
     */
    public function addPromotion(int $starterId, int $promotedId): int | null {
        return $this->model->addPromotion($starterId, $promotedId);
    }

    /**
     * Lists every "class_promotion" linked to a particular "class" ID
     * 
     * @param int $classID The ID of the "class"
     * 
     * @return array|null Returns an array with all the rows found, or null if there weren't any
     */
    public function readPromotions(int $classId): array | null {
        return $this->model->readPromotions($classId);
    }

    /**
     * Lists every "class_promotion" not yet linked to a particular "class" ID
     * 
     * @param int $classID The ID of the "class"
     * 
     * @return array|null Returns an array with all the rows found, or null if there weren't any
     */
    public function readAvailablePromotions(int $classId): array | null {
        return $this->model->readAvailablePromotions($classId);
    }

    /**
     * Deletes a row in the "class_promotion" table
     * 
     * @param int $starterId The ID of the starter class
     * @param int $promotedId The ID of the promoted class
     * 
     * @return bool Indicates whether the deletion was successful or not
     */
    public function removePromotion(int $starterId, int $promotedId): bool {
        return $this->model->removePromotion($starterId, $promotedId);
    }

    /**
     * Adds a new entry in the "class_skill" table
     * 
     * @param array $classArrayData Array with the required data for the new entry
     */
    public function addSkill(array $classDataArray): void {
        $nonNullableFields = [
            "selectedSkill", 
            "requiredLevel"
        ];
        $uniqueFields = [];
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $classDataArray, $this->model);
        
        if($dataIsValid) {
            $this->model->addSkill(
                $classDataArray["id"], 
                $classDataArray["selectedSkill"], 
                $classDataArray["requiredLevel"]
            );

            header("location:index.php?table=class&action=show&id=" . $classDataArray["id"]);
            exit();
        }else{
            header("location:index.php?table=class&action=addSkill&id=".  $classDataArray["id"] . "&error=true");
            exit();
        }
    }

    /**
     * Removes an existing row from the "class_skill" table
     * 
     * @param int $classId ID of the row from the "class" table
     * @param int $skillId ID of the row from the "skill" table
     */
    public function removeSkill(int $classId, int $skillId): void {
        $this->model->removeSkill($classId, $skillId);
        header("location:index.php?table=class&action=show&id=" . $classId);
        exit();
    }

    /**
     * Checks if a "class" entry can be erased by checking if it's currently being used as a foreign key
     * 
     * @param stdClass $class "class" which will be checked
     * 
     * @return bool $canBeErased Indicates whether the "class" can be deleted or not
     */
    private function canBeErased(stdClass $class): bool{
        $canBeErased = true;
        $unitsWithClass = $this->model->readAllUnitsWithClass($class->id);
        $userUnitsWithClass = $this->model->readAllUserUnitsWithClass($class->id);
        
        if (count($unitsWithClass) > 0 || count($userUnitsWithClass) > 0){
            $canBeErased = false;
        }

        return $canBeErased;
    }
}