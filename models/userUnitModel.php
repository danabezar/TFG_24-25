<?php
require_once('config/db.php');
require_once('baseModel.php');

class UserUnitModel implements BaseModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    //TODO: CHANGE ALL THE COMMENTS TO THE CORRECT FORMAT
    /**
     * Returns an ID if a new UserUnit could be added, else null is returned
    */
    public function insert(array $userUnit): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `user_unit`(`user_id`, `unit_id`, `level`) 
                VALUES (:userId, :unitId, :level);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userId" => $userUnit["userId"],
                ":unitId" => $userUnit["unitId"],
                ":level" => $userUnit["level"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    //TODO: Hacer esto
    public function findById(int $id): stdClass | null {
        return null;
    }

    /**
     * TODO: CHANGE THIS Returns a UserUnit standard object if a matching row was found, else null is returned
    */
    public function findByUserId(int $userId): array | null{
        $sqlQuery = "SELECT * FROM `user_unit` WHERE `user_id` = :id";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $userId
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $userUnits = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnits;
        }
    }

    /**
     * TODO: CHANGE THIS Returns a UserUnit standard object if a matching row was found, else null is returned
    */
    public function findByUnitId(int $unitId): array | null {
        $sqlQuery = "SELECT * FROM `user_unit` WHERE `unit_id` = :id";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $unitId
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $userUnits = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnits;
        }
    }

    /**
     * Returns a UserUnit standard object if a matching row was found, else null is returned
    */
    public function findByUserAndUnitId(int $userId, int $unitId): stdClass | null {
        $sqlQuery = "
            SELECT * FROM `user_unit` WHERE `user_id` = :userId 
            AND unit_id = :unitId;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":userId" => $userId,
            ":unitId" => $unitId
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $userUnit = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($userUnit != false) ? $userUnit : null;
        }
    }

    /**
     * Returns an array with all the UserUnits or null if none were found
    */
    public function readAll(): array | null{
        $sqlQuery = "
            SELECT * FROM `user_unit` 
            ORDER BY `id`;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $result = $preparedQuery->execute();

        if (!$result) {
            return null;
        } else {
            $userUnits = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnits;
        }
    }

    /**
     * TODO: Add detailed readAll
     */

    /**
     * TODO: Add comment
     */
    public function update(int $userUnitId, array $userUnit): bool {
        try {
            $sqlQuery = "
                UPDATE `user_unit` SET `user_id` = :userId, 
                `unit_id` = :unitId 
                WHERE `id` = :userUnitId;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId,
                ":userId" => $userUnit['user_id'],
                ":unitId" => $userUnit['unit_id']
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a User\'s Unit: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * TODO: Add comment
     */
    public function delete(int $id): bool {
        $sqlQuery = "DELETE FROM `user_unit` WHERE `id` = :id";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a User\'s Unit: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /*
     * TODO: CHANGE WHEN DETAILED READALL HAS BEEN ADDED
    */
    public function search(string $field, string $searchType, string $searchString): array | null{
        switch ($searchType) {
            case "begins":
                $condition = "{$searchString}%";
                break;
            case "ends":
                $condition = "%{$searchString}";
                break;
            case "contains":
                $condition = "%{$searchString}%";
                break;
            case "equals":
                $condition = $searchString;
                break;
        }

        $sqlQuery = "SELECT * FROM unit WHERE {$field} LIKE :conditionedSearch";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":conditionedSearch" => $condition
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $units = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $units;
        }
    }

    /*
     * TODO: CHANGE WHEN DETAILED READALL HAS BEEN ADDED
    */
    public function exists(string $field, string $fieldValue): bool{
        $sqlQuery = "SELECT * FROM unit WHERE $field = :fieldValue";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":fieldValue" => $fieldValue
        ];

        $result = $preparedQuery->execute($dataArray);
        if(!$result || $preparedQuery->rowCount() <= 0) {
            return false;
        } else {
            return true;
        }
    }

    public function addStatGains(int $userUnitId, $statGains): int | null{
        try {
            $sqlQuery = "
                INSERT INTO `user_unit_stat_gains`(`user_unit_id`, `health`, `strength`, `magic`, `skill`, `speed`, 
                `luck`, `defense`, `resistance`) VALUES (:id, :health, :strength, :magic, :skill, 
                :speed, :luck, :defense, :resistance);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $userUnitId,
                ":health" => $statGains["health_gains"],
                ":strength" => $statGains["strength_gains"],
                ":magic" => $statGains["magic_gains"],
                ":skill" => $statGains["skill_gains"],
                ":speed" => $statGains["speed_gains"],
                ":luck" => $statGains["luck_gains"],
                ":defense" => $statGains["defense_gains"],
                ":resistance" => $statGains["resistance_gains"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }

    }

    public function getStatGainsById(int $userUnitId): array | null {
        $sqlQuery = "
            SELECT * FROM `user_unit_stat_gains` 
            WHERE `id` = :id;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $userUnitId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $userUnitStatGains = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnitStatGains;
        }
    }

    public function updateStatGains(int $userUnitId, $statGains): bool {
        try {
            $sqlQuery = "
                UPDATE `user_unit_stat_gains` SET `health` = :health, `strength` = :strength, `magic` = :magic, 
                `skill` = :skill, `speed` = :speed, `luck` = :luck, `defense` = :defense, `resistance` = :resistance
                WHERE `user_unit_id` = :userUnitId;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId,
                ":health" => $statGains["health_gains"],
                ":strength" => $statGains["strength_gains"],
                ":magic" => $statGains["magic_gains"],
                ":skill" => $statGains["skill_gains"],
                ":speed" => $statGains["speed_gains"],
                ":luck" => $statGains["luck_gains"],
                ":defense" => $statGains["defense_gains"],
                ":resistance" => $statGains["resistance_gains"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a User\'s Unit Stat Gains : ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    public function deleteStatGains($userUnitId): bool {
        $sqlQuery = "DELETE FROM `user_unit_stat_gains` WHERE `user_unit_id` = :userUnitId";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a User\'s Unit Stat Gains: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    public function addSkill($userUnitId, $skillId): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `user_unit_skill`(`user_unit_id`, `skill_id`) 
                VALUES (:userUnitId, :skillId);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId,
                ":skillId" => $skillId
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getSkillsById(int $userUnitId): array | null {
        $sqlQuery = "
            SELECT * FROM `user_unit_skill` 
            WHERE `user_unit_id` = :id;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $userUnitId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $userUnitSkills = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnitSkills;
        }
    }

    public function removeSkill($userUnitId, $skillId): bool {
        $sqlQuery = "
            DELETE FROM `user_unit_skill` 
            WHERE `user_unit_id` = :userUnitId
            AND `skill_id` = :skillId;
        ";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId,
                ":skillId" => $skillId
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a User\'s Unit Skill: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    public function equipSkill($userUnitId, $userUnitSkillId): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `user_unit_equipped_skill`(`user_unit_id`, `user_unit_skill_id`) 
                VALUES (:userUnitId, :userUnitSkillId);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId,
                ":userUnitSkillId" => $userUnitSkillId
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getEquippedSkillsById($userUnitId): array | null {
        $sqlQuery = "
            SELECT * FROM `user_unit_equipped_skill` 
            WHERE `user_unit_id` = :id;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $userUnitId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $userUnitEquippedSkills = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnitEquippedSkills;
        }
    }

    public function unequipSkill($userUnitSkillId): bool {
        $sqlQuery = "
            DELETE FROM `user_unit_equipped_skill` 
            WHERE `user_unit_skill_id` = :userUnitSkillId;
        ";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitSkillId" => $userUnitSkillId
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a User\'s Unit Equipped Skill: ',  $error->getMessage(), "<br>";
            return false;
        }
    }
}