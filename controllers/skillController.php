<?php
require_once "models/skillModel.php";
require_once "assets/php/functions.php";

class SkillController{
    private $model;

    public function __construct(){
        $this->model = new SkillModel();
    }

    /**
     * Inserts a new entry in the database's "skill" table
     * 
     * @param array $skillDataArray Contains the values for each field in the table
     */
    public function create(array $skillDataArray): void{
        $nonNullableFields = [
            "name", 
            "type", 
            "attribute", 
            "value"   
        ];
        $uniqueFields = ["name"];
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $skillDataArray, $this->model);
        
        if($dataIsValid) {
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
            $newSkillId = $this->model->insert($skillDataArray);
            header("location:index.php?table=skill&action=list");
            exit();
        }else{
            header("location:index.php?table=skill&action=create&error=true");
            exit();
        }
    }

    /**
     * Finds an entry in the database's "skill" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function read(int $id): stdClass | null {
        return $this->model->findById($id);
    }

    /**
     * List every entry in the database's "skill" table and returns their info
     * 
     * @param bool $canBeErased Used to add an extra field which indicates if they can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function list(bool $canBeErased = false): array | null {
        $skills = $this->model->readAll();
        return $skills;
    }

    /**
     * Updates the data from a particular row in the "skill" table
     * 
     * @param int $skillId ID of the row whose info will be changed
     * @param array $skillDataArray Array with the new values for the fields in the table
     */
    public function update(int $skillId, array $skillDataArray): void{
        $nonNullableFields = [
            "name", 
            "type", 
            "attribute", 
            "value"   
        ];
        $uniqueFields = [];
        if($skillDataArray["name"] != $skillDataArray["previousName"]){
            $uniqueFields[] = "name";
        }

        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $skillDataArray, $this->model);
        
        if($dataIsValid) {
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
            $successfulSkillUpdate = $this->model->update($skillId, $skillDataArray);

            if($successfulSkillUpdate){
                header("location:index.php?table=skill&action=show&id={$skillId}");
                exit();
            }else{
                header("location:index.php?table=skill&action=update&id={$skillId}&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=skill&action=update&id={$skillId}&error=true");
            exit();
        }
    }

    /**
     * Deletes a row from the "skill" table
     * 
     * @param int $skillId ID of the row which will be deleted
     */
    public function delete(int $skillId): void{
        $this->model->delete($skillId);
        header("location:index.php?table=skill&action=list");
        exit();
    }

    /**
     * Returns a list of rows from the "skill" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null $skill Returns a list of rows who match the condition, or null if none did
     */
    public function search(
        string $field = "name", 
        string $searchType = "contains", 
        string $searchString = "", 
        bool  $canBeErased = false
        ): array | null{
            $skills = $this->model->search($field, $searchType, $searchString);
            return $skills;
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
     * Returns a list with all the entries in the "skill" table that aren't linked to a particular entry in the "class" table yet
     * 
     * @param int $classId ID of the row from the "class" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function getAvailableForClass(int $classId): array | null {
        return $this->model->getAvailableForClass($classId);
    }
}