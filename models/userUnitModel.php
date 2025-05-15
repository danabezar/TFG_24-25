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
                INSERT INTO `user_unit`(`user_id`, `unit_id`, `level`, `experience`) 
                VALUES (:userId, :unitId, :level, :experience);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userId" => $userUnit["userId"],
                ":unitId" => $userUnit["unitId"],
                ":level" => $userUnit["level"],
                ":experience" => $userUnit["experience"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    //TODO: CHANGE COMMENT
    public function findById(int $id): stdClass | null {
        $sqlQuery = "SELECT * FROM `user_unit` WHERE `id` = :id";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $userUnit = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return $userUnit;
        }
    }

    //TODO: CHANGE COMMENT
    public function findByIdDetailed(int $id): stdClass | null {
        $sqlQuery = "
        SELECT uu.`id`, u.`name`, u.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
            (ubs.`health` + uusg.`health`) AS `health_stat`, (ubs.`strength` + uusg.`strength`) AS `strength_stat`, 
            (ubs.`magic` + uusg.`magic`) AS `magic_stat`, (ubs.`skill` + uusg.`skill`) AS `skill_stat`, 
            (ubs.`speed` + uusg.`speed`) AS `speed_stat`, (ubs.`luck` + uusg.`luck`) AS `luck_stat`, 
            (ubs.`defense` + uusg.`defense`) AS `defense_stat`, (ubs.`resistance` + uusg.`resistance`) AS `resistance_stat`, 
            (cg.`health` + ug.`health`) AS `health_growth`, (cg.`strength` + ug.`strength`) AS `strength_growth`, 
            (cg.`magic` + ug.`magic`) AS `magic_growth`, (cg.`skill` + ug.`skill`) AS `skill_growth`, 
            (cg.`speed` + ug.`speed`) AS `speed_growth`, (cg.`luck` + ug.`luck`) AS `luck_growth`, 
            (cg.`defense` + ug.`defense`) AS `defense_growth`, (cg.`resistance` + ug.`resistance`) AS `resistance_growth` 
            FROM user_unit uu 
            LEFT JOIN user_unit_stat_gains uusg ON (uu.`id` = uusg.`user_unit_id`) 
            LEFT JOIN unit u ON (uu.`unit_id` = u.`id`) 
            LEFT JOIN unit_base_stats ubs ON (u.`id` = ubs.`unit_id`) 
            LEFT JOIN unit_growths ug ON (u.id = ug.unit_id) 
            LEFT JOIN class c ON (u.`class_id` = c.`id`) 
            LEFT JOIN class_growths cg ON (c.id = cg.class_id) 
            WHERE uu.`id` = :id
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $userUnit = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return $userUnit;
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

    //TODO: Add comment
    public function readAll(): array | null{
        $sqlQuery = "
            SELECT uu.`id`, u.`name`, u.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
            (ubs.`health` + uusg.`health`) AS `health_stat`, (ubs.`strength` + uusg.`strength`) AS `strength_stat`, 
            (ubs.`magic` + uusg.`magic`) AS `magic_stat`, (ubs.`skill` + uusg.`skill`) AS `skill_stat`, 
            (ubs.`speed` + uusg.`speed`) AS `speed_stat`, (ubs.`luck` + uusg.`luck`) AS `luck_stat`, 
            (ubs.`defense` + uusg.`defense`) AS `defense_stat`, (ubs.`resistance` + uusg.`resistance`) AS `resistance_stat`, 
            (cg.`health` + ug.`health`) AS `health_growth`, (cg.`strength` + ug.`strength`) AS `strength_growth`, 
            (cg.`magic` + ug.`magic`) AS `magic_growth`, (cg.`skill` + ug.`skill`) AS `skill_growth`, 
            (cg.`speed` + ug.`speed`) AS `speed_growth`, (cg.`luck` + ug.`luck`) AS `luck_growth`, 
            (cg.`defense` + ug.`defense`) AS `defense_growth`, (cg.`resistance` + ug.`resistance`) AS `resistance_growth` 
            FROM user_unit uu 
            LEFT JOIN user_unit_stat_gains uusg ON (uu.`id` = uusg.`user_unit_id`) 
            LEFT JOIN unit u ON (uu.`unit_id` = u.`id`) 
            LEFT JOIN unit_base_stats ubs ON (u.`id` = ubs.`unit_id`) 
            LEFT JOIN unit_growths ug ON (u.id = ug.unit_id) 
            LEFT JOIN class c ON (u.`class_id` = c.`id`) 
            LEFT JOIN class_growths cg ON (c.id = cg.class_id);
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $result = $preparedQuery->execute();
        
        if (!$result) {
            return null;
        }else{
            $userUnits = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnits;
        }
    }

    /**
     * TODO: Add comment
    */
    public function readAllByUserId(int $userId): array | null{
        $sqlQuery = "
            SELECT uu.`id`, u.`name`, u.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
            (ubs.`health` + uusg.`health`) AS `health_stat`, (ubs.`strength` + uusg.`strength`) AS `strength_stat`, 
            (ubs.`magic` + uusg.`magic`) AS `magic_stat`, (ubs.`skill` + uusg.`skill`) AS `skill_stat`, 
            (ubs.`speed` + uusg.`speed`) AS `speed_stat`, (ubs.`luck` + uusg.`luck`) AS `luck_stat`, 
            (ubs.`defense` + uusg.`defense`) AS `defense_stat`, (ubs.`resistance` + uusg.`resistance`) AS `resistance_stat`, 
            (cg.`health` + ug.`health`) AS `health_growth`, (cg.`strength` + ug.`strength`) AS `strength_growth`, 
            (cg.`magic` + ug.`magic`) AS `magic_growth`, (cg.`skill` + ug.`skill`) AS `skill_growth`, 
            (cg.`speed` + ug.`speed`) AS `speed_growth`, (cg.`luck` + ug.`luck`) AS `luck_growth`, 
            (cg.`defense` + ug.`defense`) AS `defense_growth`, (cg.`resistance` + ug.`resistance`) AS `resistance_growth` 
            FROM user_unit uu 
            LEFT JOIN user_unit_stat_gains uusg ON (uu.`id` = uusg.`user_unit_id`) 
            LEFT JOIN unit u ON (uu.`unit_id` = u.`id`) 
            LEFT JOIN unit_base_stats ubs ON (u.`id` = ubs.`unit_id`) 
            LEFT JOIN unit_growths ug ON (u.id = ug.unit_id) 
            LEFT JOIN class c ON (u.`class_id` = c.`id`) 
            LEFT JOIN class_growths cg ON (c.id = cg.class_id) 
            WHERE uu.`user_id` = :userId;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":userId" => $userId
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
     * TODO: Add comment
    */
    public function readAllByUnitId(int $unitId): array | null {
        $sqlQuery = "
            SELECT uu.`id`, u.`name`, u.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
            (ubs.`health` + uusg.`health`) AS `health_stat`, (ubs.`strength` + uusg.`strength`) AS `strength_stat`, 
            (ubs.`magic` + uusg.`magic`) AS `magic_stat`, (ubs.`skill` + uusg.`skill`) AS `skill_stat`, 
            (ubs.`speed` + uusg.`speed`) AS `speed_stat`, (ubs.`luck` + uusg.`luck`) AS `luck_stat`, 
            (ubs.`defense` + uusg.`defense`) AS `defense_stat`, (ubs.`resistance` + uusg.`resistance`) AS `resistance_stat`, 
            (cg.`health` + ug.`health`) AS `health_growth`, (cg.`strength` + ug.`strength`) AS `strength_growth`, 
            (cg.`magic` + ug.`magic`) AS `magic_growth`, (cg.`skill` + ug.`skill`) AS `skill_growth`, 
            (cg.`speed` + ug.`speed`) AS `speed_growth`, (cg.`luck` + ug.`luck`) AS `luck_growth`, 
            (cg.`defense` + ug.`defense`) AS `defense_growth`, (cg.`resistance` + ug.`resistance`) AS `resistance_growth` 
            FROM user_unit uu 
            LEFT JOIN user_unit_stat_gains uusg ON (uu.`id` = uusg.`user_unit_id`) 
            LEFT JOIN unit u ON (uu.`unit_id` = u.`id`) 
            LEFT JOIN unit_base_stats ubs ON (u.`id` = ubs.`unit_id`) 
            LEFT JOIN unit_growths ug ON (u.id = ug.unit_id) 
            LEFT JOIN class c ON (u.`class_id` = c.`id`) 
            LEFT JOIN class_growths cg ON (c.id = cg.class_id) 
            WHERE uu.`unit_id` = :unitId;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":unitId" => $unitId
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
     * TODO: Add comment
     */
    public function update(int $userUnitId, array $userUnit): bool {
        try {
            $sqlQuery = "
                UPDATE `user_unit` SET `level` = :level, `experience` = :experience 
                WHERE `id` = :userUnitId;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId,
                ":level" => $userUnit['level'],
                ":experience" => $userUnit['experience']
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
     * TODO: Add comment
    */
    public function search(string $field, string $searchType, string $searchString): array | null{
        switch($field){
            case "name":
                $field = "u.`name`";
                break;
            case "class":
                $field = "c.`name`";
                break;
            default:
                break;
        }

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

        $sqlQuery = "
            SELECT uu.`id`, u.`name`, u.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience` 
            FROM user_unit uu 
            LEFT JOIN unit u ON (uu.`unit_id` = u.`id`) 
            LEFT JOIN class c ON (u.`class_id` = c.`id`) 
            WHERE {$field} LIKE :conditionedSearch
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":conditionedSearch" => $condition
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $userUnits = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $userUnits;
        }
    }

    /*
     * TODO: PENDING
    */
    public function exists(string $field, string $fieldValue): bool{
        // $sqlQuery = "SELECT * FROM unit WHERE $field = :fieldValue";
        // $preparedQuery = $this->connection->prepare($sqlQuery);
        // $dataArray = [
        //     ":fieldValue" => $fieldValue
        // ];

        // $result = $preparedQuery->execute($dataArray);
        // if(!$result || $preparedQuery->rowCount() <= 0) {
        //     return false;
        // } else {
        //     return true;
        // }
        return true;
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