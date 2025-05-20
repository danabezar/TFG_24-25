<?php
require_once('config/db.php');
require_once('baseModel.php');

class UserUnitModel implements BaseModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Inserts a new entry in the database's "user_unit" table
     * 
     * @param array $userUnit Contains the values for each field in the table
     */
    public function insert(array $userUnit): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `user_unit`(`user_id`, `unit_id`, `class_id`, `level`, `experience`) 
                VALUES (:userId, :unitId, :classId, :level, :experience);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userId" => $userUnit["userId"],
                ":unitId" => $userUnit["unitId"],
                ":classId" => $userUnit["class"],
                ":level" => $userUnit["level"],
                ":experience" => $userUnit["experience"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Finds an entry in the database's "user_unit" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
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

    /**
     * Finds an entry in the database's "user_unit" table and returns its info, with extra details
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function findByIdDetailed(int $id): stdClass | null {
        $sqlQuery = "
        SELECT uu.`id`, u.`name`, uu.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
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
            LEFT JOIN class c ON (uu.`class_id` = c.`id`) 
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
     * Finds an entry in the database's "user_unit" table with particular IDs from a "user" and a "unit"
     * 
     * @param int $userId ID from a "user"
     * @param int $unitId ID from a "unit"
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
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
     * List every entry in the database's "user_unit" table and returns their info
     * 
     * @return array|null $userUnits Returns an array with all the rows from the table, null if there were none
     */
    public function readAll(): array | null{
        $sqlQuery = "
            SELECT uu.`id`, u.`name`, uu.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
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
            LEFT JOIN class c ON (uu.`class_id` = c.`id`) 
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
     * List every entry in the database's "user_unit" table which match the specified "user" ID, and returns their info
     * 
     * @param int $userId ID of the User to search for
     * 
     * @return array|null $userUnits Returns an array with all the rows from the table, null if there were none
     */
    public function readAllByUserId(int $userId): array | null{
        $sqlQuery = "
            SELECT uu.`id`, u.`name`, uu.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
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
            LEFT JOIN class c ON (uu.`class_id` = c.`id`) 
            LEFT JOIN class_growths cg ON (c.id = cg.class_id) 
            WHERE uu.`user_id` = :userId
            ORDER BY uu.`unit_id`;
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
     * List every entry in the database's "user_unit" table which match the specified "unit" ID, and returns their info
     * 
     * @param int $unitId ID of the Unit to search for
     * 
     * @return array|null $userUnits Returns an array with all the rows from the table, null if there were none
     */
    public function readAllByUnitId(int $unitId): array | null {
        $sqlQuery = "
            SELECT uu.`id`, u.`name`, uu.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience`, 
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
            LEFT JOIN class c ON (uu.`class_id` = c.`id`) 
            LEFT JOIN class_growths cg ON (c.id = cg.class_id) 
            WHERE uu.`unit_id` = :unitId
            ORDER BY uu.`unit_id`;
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
     * Returns a list with all the entries in the "unit" table that aren't linked to a particular entry in the "user" table yet
     * 
     * @param int $userId ID of the row from the "user" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function readAllAvailableForUser(int $userId): array | null {
        $sqlQuery = "
            SELECT * FROM `unit` WHERE `id` NOT IN 
            (SELECT `unit_id` FROM user_unit WHERE `user_id` = :userId);
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":userId" => $userId
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $units = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $units;
        }
    }

    /**
     * Returns a list with all the entries in the "skill" table that aren't linked to a particular entry in the "user_unit" table yet
     * 
     * @param int $userUnitId ID of the row from the "user_unit" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function readAllSkillsAvailable(int $userUnitId): array | null {
        $sqlQuery = "
            SELECT * FROM `skill` WHERE `id` NOT IN 
            (SELECT `skill_id` FROM user_unit_skill WHERE `user_unit_id` = :userUnitId);
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":userUnitId" => $userUnitId
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $skills = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $skills;
        }
    }

    /**
     * Updates the data from a particular row in the "user_unit" table
     * 
     * @param array $userUnitId ID of the "user_unit" to update
     * @param array $userUnit Array with the data needed for the update
     * 
     * @return bool Indicates whether the update was successful or not
     */
    public function update(int $userUnitId, array $userUnit): bool {
        try {
            $sqlQuery = "
                UPDATE `user_unit` SET `class_id` = :classId, `level` = :level, `experience` = :experience 
                WHERE `id` = :userUnitId;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":userUnitId" => $userUnitId,
                ":classId" => $userUnit['class'],
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
     * Deletes a row from the "user_unit" table
     * 
     * @param int $id ID of the row which will be deleted
     * 
     * @return bool Indicates whether the deletion was successful or not
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

    /**
     * Returns a list of rows from the "user_unit" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * 
     * @return array|null $userUnits Returns a list of rows who match the condition, or null if none did
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
            SELECT uu.`id`, u.`name`, uu.`class_id`, c.`name` AS `class`, uu.`level`, uu.`experience` 
            FROM user_unit uu 
            LEFT JOIN unit u ON (uu.`unit_id` = u.`id`) 
            LEFT JOIN class c ON (uu.`class_id` = c.`id`) 
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

    public function exists(string $field, string $fieldValue): bool{
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

    /**
     * Returns a row from the "user_unit_stat_gains" table if they're linked to an entry in the "user_unit" table
     * 
     * @param int $userUnitId ID of the row from the "user_unit" table
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function getStatGainsById(int $userUnitId): stdClass | null {
        $sqlQuery = "
            SELECT * FROM `user_unit_stat_gains` 
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
            $userUnitStatGains = $preparedQuery->fetch(PDO::FETCH_OBJ);
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

    /**
     * Adds a new entry in the "user_unit_skill" table
     * 
     * @param int $userUnitId ID of a "user_unit"
     * @param int $skillId ID of a "skill"
     * 
     * @return int|null Returns an int if the insertion was successful, null if it wasn't
     */
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

    /**
     * Returns a row from the "user_unit_skill" table if they're linked to an entry in the "user_unit" table
     * 
     * @param int $userUnitId ID of the row from the "user_unit" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    public function getSkillsById(int $userUnitId): array | null {
        $sqlQuery = "
            SELECT uus.`id`, uus.`skill_id`, s.`name`, s.`type`, s.`attribute`, s.`value` 
            FROM `user_unit_skill` uus 
            LEFT JOIN skill s ON (uus.`skill_id` = s.`id`) 
            WHERE uus.`user_unit_id` = :id;
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

    /**
     * Removes an existing row from the "user_unit_skill" table
     * 
     * @param int $userUnitId ID of a "user_unit"
     * @param int $skillId ID of a "skill"
     * 
     * @return bool Indicates whether the deletion was successful or not
     */
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