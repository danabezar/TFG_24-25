<?php
require_once "models/userUnitModel.php";
require_once "assets/php/functions.php";

class UserUnitController{
    private $model;

    public function __construct(){
        $this->model = new UserUnitModel();
    }

    /**
     * Inserts a new entry in the database's "user_unit" table
     * 
     * @param array $userUnitDataArray Contains the values for each field in the table
     */
    public function create(array $userUnitDataArray): void{
        $nonNullableFields = [
            "userId", 
            "unitId",
            "class"
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
                    header("location:index.php?table=user&action=showUnit&userId=" . $userUnitDataArray["userId"] . "&userUnitId=" . $newUserUnitId);
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

    /**
     * Finds an entry in the database's "user_unit" table and returns its info, with extra details
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function read(int $id): stdClass | null {
        return $this->model->findByIdDetailed($id);
    }

    /**
     * List every entry in the database's "user_unit" table and returns their info
     * 
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null $userUnits Returns an array with all the rows from the table, null if there were none
     */
    public function list(bool $canBeErased = false): array | null {
        $userUnits = $this->model->readAll();
        return $userUnits;
    }

    /**
     * List every entry in the database's "user_unit" table which match the specified "user" ID, and returns their info
     * 
     * @param int $userId ID of the User to search for
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null $userUnits Returns an array with all the rows from the table, null if there were none
     */
    public function listByUserId(int $userId, bool $canBeErased = false): array | null {
        $userUnits = $this->model->readAllByUserId($userId);
        return $userUnits;
    }

    /**
     * Returns a list with all the entries in the "unit" table that aren't linked to a particular entry in the "user" table yet
     * 
     * @param int $userId ID of the row from the "user" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function listAvailableForUser(int $userId): array | null {
        return $this->model->readAllAvailableForUser($userId);
    }

    /**
     * Returns a list with all the entries in the "skill" table that aren't linked to a particular entry in the "user_unit" table yet
     * 
     * @param int $userUnitId ID of the row from the "user_unit" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function listAvailableSkills(int $userUnitId): array | null {
        return $this->model->readAllSkillsAvailable($userUnitId);
    }

    /**
     * Returns a row from the "user_unit_stat_gains" table if they're linked to an entry in the "user_unit" table
     * 
     * @param int $userUnitId ID of the row from the "user_unit" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function getStatGainsById(int $userUnitId): stdClass | null {
        return $this->model->getStatGainsById($userUnitId);
    }

    /**
     * Returns a row from the "user_unit_skill" table if they're linked to an entry in the "user_unit" table
     * 
     * @param int $userUnitId ID of the row from the "user_unit" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function getSkillsById(int $userUnitId): array | null {
        return $this->model->getSkillsById($userUnitId);
    }

    /**
     * Updates the data from a particular row in the "user_unit" table
     * 
     * @param array $userUnitDataArray Array with the new values for the fields in the table
     */
    public function update(array $userUnitDataArray): void{
        $nonNullableFields = [
            "userId", 
            "userUnitId", 
            "level",
            "experience",
            "health_gains", 
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
            //Format checks
            $dataFormatIsValid = true;
            $gainsCheckList = [
                "health_gains", 
                "strength_gains", 
                "magic_gains", 
                "skill_gains", 
                "speed_gains", 
                "luck_gains", 
                "defense_gains", 
                "resistance_gains"
            ];

            $dataFormatIsValid = 
            isValidFormDataFormat("level", ["level"], $userUnitDataArray) && 
            isValidFormDataFormat("stat", $gainsCheckList, $userUnitDataArray);
            
            if($dataFormatIsValid) {
                $successfulUserUnitUpdate = $this->model->update($userUnitDataArray["userUnitId"], $userUnitDataArray);

                if($successfulUserUnitUpdate){
                    $successfulGainsUpdate = $this->model->updateStatGains($userUnitDataArray["userUnitId"], $userUnitDataArray);

                    if($successfulGainsUpdate){
                        header("location:index.php?table=user&action=showUnit&userId=" . $userUnitDataArray["userId"] .  "&userUnitId=" . $userUnitDataArray["userUnitId"]);
                        exit();
                    }else{
                        header("location:index.php?table=user&action=updateUnit&userId=" . $userUnitDataArray["userId"] .  "&userUnitId=" . $userUnitDataArray["userUnitId"] . "&error=true");
                        exit();
                    }
                }else{
                    header("location:index.php?table=user&action=updateUnit&userId=" . $userUnitDataArray["userId"] .  "&userUnitId=" . $userUnitDataArray["userUnitId"] . "&error=true");
                    exit();
                }
            }else{
                header("location:index.php?table=user&action=updateUnit&userId=" . $userUnitDataArray["userId"] .  "&userUnitId=" . $userUnitDataArray["userUnitId"] . "&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=user&action=updateUnit&userId=" . $userUnitDataArray["userId"] .  "&userUnitId=" . $userUnitDataArray["userUnitId"] . "&error=true");
            exit();
        }
    }

    /**
     * Deletes a row from the "user_unit" table
     * 
     * @param int $userId ID of the "user" linked to the "user_unit" entry
     * @param int $userUnitId ID of the row which will be deleted
     */
    public function delete(int $userId, int $userUnitId){
        $this->model->delete($userUnitId);
        header("location:index.php?table=user&action=show&id=" . $userId);
        exit();
    }

    /**
     * Adds a new entry in the "user_unit_skill" table
     * 
     * @param array $userUnitSkillDataArray Array with the required data for the new entry
     */
    public function addSkill($userUnitSkillDataArray): void {
        $nonNullableFields = [
            "userId", 
            "userUnitId",
            "skill"
        ];
        $uniqueFields = [];
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $userUnitSkillDataArray, $this->model);
        
        if($dataIsValid) {
            /*
            TODO: ADD FORMATTING CHECKS HERE
            */
            $newUserUnitSkillId = $this->model->addSkill($userUnitSkillDataArray["userUnitId"], $userUnitSkillDataArray["skill"]);

            if($newUserUnitSkillId != null){
                header("location:index.php?table=user&action=showUnit&userId=" . $userUnitSkillDataArray["userId"] . "&userUnitId=" . $userUnitSkillDataArray["userUnitId"]);
                exit();
            }else{
                header("location:index.php?table=user&action=addUnitSkill&userId=" . $userUnitSkillDataArray["userId"] . "&userUnitId=" . $userUnitSkillDataArray["userUnitId"] . "&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=user&action=addUnitSkill&userId=" . $userUnitSkillDataArray["userId"] . "&userUnitId=" . $userUnitSkillDataArray["userUnitId"] . "&error=true");
            exit();
        }
    }

    /**
     * Removes an existing row from the "user_unit_skill" table
     * 
     * @param array $userUnitSkillDataArray Array with the required data for the new entry
     */
    public function removeSkill(array $userUnitSkillDataArray): void {
        $this->model->removeSkill($userUnitSkillDataArray["userUnitId"], $userUnitSkillDataArray["skillId"]);
        header("location:index.php?table=user&action=showUnit&userId=" . $userUnitSkillDataArray["userId"] . "&userUnitId=" . $userUnitSkillDataArray["userUnitId"]);
        exit();
    }
}