<?php
require_once "models/skillModel.php";
require_once "assets/php/functions.php";

class SkillController{
    private $model;

    public function __construct(){
        $this->model = new SkillModel();
    }

    /*
     * TODO: CHANGE
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
        $skills = $this->model->readAll();
        return $skills;
    }

    /*
     * TODO: Add comment
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

    /*
     * TODO: Add comment
    */
    public function delete(int $skillId): void{
        $this->model->delete($skillId);
        header("location:index.php?table=skill&action=list");
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
            $skills = $this->model->search($field, $searchType, $searchString);
            return $skills;
    }

    /*
     * TODO: Add comment
    */
    public function exists(string $field, string $fieldValue): bool {
        return $this->model->exists($field, $fieldValue);
    }
}