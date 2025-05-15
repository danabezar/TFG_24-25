<?php
require_once "models/userUnitModel.php";
require_once "assets/php/functions.php";

class userUnitController{
    private $model;

    public function __construct(){
        $this->model = new UserUnitModel();
    }

    /*
     * TODO: Add comment
    */
    public function create(array $userUnitDataArray): void{
        $nonNullableFields = [
            "userId", 
            "unitId", 
            "level",
            "experience", 
            "strength_gains", 
            "magic_gains", 
            "skill_gains", 
            "speed_gains", 
            "luck_gains", 
            "defense_gains", 
            "resistance_gains" 
        ];
        $uniqueFields = [];
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $userUnitDataArray, $this->model);
        
        if($dataIsValid) {
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
            $newUserUnitId = $this->model->insert($userUnitDataArray);

            if($newUserUnitId != null){
                $newUserUnitGainsId = $this->model->addStatGains($newUserUnitId, $userUnitDataArray);

                if($newUserUnitGainsId != null){
                    header("location:index.php?table=user&action=showUnit&id=" . $newUserUnitId);
                    exit();
                }else{
                    header("location:index.php?table=user&action=addUnit&userId=" . $userUnitDataArray["userId"] . "&error=true");
                    exit();
                }
            }else{
                header("location:index.php?table=user&action=addUnit&userId=" . $userUnitDataArray["userId"] . "&error=true");
                exit();
            }
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
        $userUnits = $this->model->readAll();
        return $userUnits;
    }

    /*
     * TODO: Add comment
    */
    public function listByUserId(int $userId, bool $canBeErased = false): array | null {
        $userUnits = $this->model->readAllByUserId($userId);
        return $userUnits;
    }


    //TODO: Add comment
    public function update(array $userUnitDataArray): void{
        $nonNullableFields = [
            "userId", 
            "userUnitId", 
            "level",
            "experience", 
            "strength_gains", 
            "magic_gains", 
            "skill_gains", 
            "speed_gains", 
            "luck_gains", 
            "defense_gains", 
            "resistance_gains" 
        ];
        $uniqueFields = [];
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $userUnitDataArray, $this->model);
        
        if($dataIsValid) {
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
            $successfulUserUnitUpdate = $this->model->update($userUnitDataArray["userUnitId"], $userUnitDataArray);

            if($successfulUserUnitUpdate){
                $successfulGainsUpdate = $this->model->updateStatGains($userUnitDataArray["userUnitId"], $userUnitDataArray);

                if($successfulGainsUpdate){
                    header("location:index.php?table=user&action=showUnit&id=" . $userUnitDataArray["userUnitId"]);
                    exit();
                }else{
                    header("location:index.php?table=user&action=updateUnit&id=" . $userUnitDataArray["userUnitId"] . "&error=true");
                    exit();
                }
            }else{
                header("location:index.php?table=user&action=updateUnit&id=" . $userUnitDataArray["userUnitId"] . "&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=user&action=updateUnit&id=" . $userUnitDataArray["userUnitId"] . "&error=true");
            exit();
        }
    }

    //TODO: Add comment
    public function delete(int $userId, int $userUnitId){
        $this->model->delete($userUnitId);
        header("location:index.php?table=user&action=show&id=" . $userId);
        exit();
    }
}