<?php
require_once "models/classModel.php";
require_once "assets/php/functions.php";

class ClassController{
    private $model;

    public function __construct(){
        $this->model = new ClassModel();
    }

    /*
     * TODO: ADD COMMENT
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
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
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
        }else{
            header("location:index.php?table=class&action=create&error=true");
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
    public function readGrowths(int $id): stdClass | null {
        return $this->model->findByIdGrowths($id);
    }

    /*
     * TODO: Add comment
    */
    public function readSkills(int $id): array | null {
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
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
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
        }else{
            header("location:index.php?table=class&action=update&id={$classId}&error=true");
            exit();
        }
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
    public function addSkill(int $classId, int $skillId, int $requiredLevel = 10): void {
        //TODO: ADD CHECKS
        $this->model->addSkill($classId, $skillId, $requiredLevel);
        header("location:index.php?table=class&action=show&id=" . $classId);
        exit();
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