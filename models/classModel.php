<?php
require_once('config/db.php');
require_once('baseModel.php');

class ClassModel implements BaseModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Inserts a new entry in the database's "class" table
     * 
     * @param array $class Contains the values for each field in the table
     */
    public function insert(array $class): int | null {
        try {
            $sqlQuery = "INSERT INTO `class`(`name`, `type`, `dmg_type`)  VALUES (:name, :type, :dmgType);";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":name" => $class["name"],
                ":type" => $class["type"],
                ":dmgType" => $class["dmgType"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Finds an entry in the database's "class" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function findById(int $id): stdClass | null {
        $sqlQuery = "SELECT * FROM `class` WHERE `id` = :id";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $class = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($class != false) ? $class : null;
        }
    }

    /**
     * Finds an entry in the database's "class_growths" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function findByIdGrowths(int $id): stdClass | null {
        $sqlQuery = "
            SELECT c.`id`, g.`health`, g.`strength`, g.`magic`, g.`skill`, 
            g.`speed`, g.`luck`, g.`defense`, g.`resistance` 
            FROM `class` c
            LEFT JOIN `class_growths` g
            ON (c.`id` = g.`class_id`)
            WHERE c.`id` = :id
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $class = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($class != false) ? $class : null;
        }
    }

    /**
     * Finds one or more entries in the database's "class_skills" table and returns their info
     * 
     * @param int $id ID of the "class" whose entries will be retrieved
     * 
     * @return array|null Returns an array if one or more matching rows were found, null if there weren't
     */
    public function findByIdSkills(int $id): array | null {
        $sqlQuery = "
            SELECT c.`id`, s.`id`, s.`name`, s.`type`, s.`attribute`, s.`value`, cs.`level_required` 
            FROM `class_skill` cs
            LEFT JOIN `class` c
            ON (cs.`class_id` = c.`id`) 
            LEFT JOIN `skill` s
            ON (cs.`skill_id` = s.`id`)
            WHERE cs.`class_id` = :id
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $classSkills = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $classSkills;
        }
    }

    /**
     * Finds an entry in the database's "class" table and returns its info, with extra details
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function findByIdDetailed(int $id): stdClass | null {
        $sqlQuery = "
            SELECT c.`id`, c.`name`, c.`type`, c.`dmg_type`, 
            g.`health` AS `health_growth`, g.`strength` AS `strength_growth`, 
            g.`magic` AS `magic_growth`, g.`skill` AS `skill_growth`, 
            g.`speed` AS `speed_growth`, g.`luck` AS `luck_growth`, 
            g.`defense` AS `defense_growth`, g.`resistance` AS `resistance_growth` 
            FROM `class` c
            LEFT JOIN `class_growths` g
            ON (c.`id` = g.`class_id`)
            WHERE c.`id` = :id
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $class = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($class != false) ? $class : null;
        }
    }

    /**
     * List every entry in the database's "class" table and returns their info
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function readAll(): array | null{
        $sqlQuery = "
            SELECT * FROM `class` 
            ORDER BY `id`;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $result = $preparedQuery->execute();

        if (!$result) {
            return null;
        } else {
            $units = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $units;
        }
    }

    /**
     * List every entry in the database's "class" table and returns their info, with extra details
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function readAllDetailed(): array | null{
        $sqlQuery = "
            SELECT c.`id`, c.`name`, c.`type`, c.`dmg_type`, 
            g.`health` AS `health_growth`, g.`strength` AS `strength_growth`, 
            g.`magic` AS `magic_growth`, g.`skill` AS `skill_growth`, 
            g.`speed` AS `speed_growth`, g.`luck` AS `luck_growth`, 
            g.`defense` AS `defense_growth`, g.`resistance` AS `resistance_growth` 
            FROM `class` c
            LEFT JOIN `class_growths` g
            ON (c.`id` = g.`class_id`)
            ORDER BY c.`id`;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $result = $preparedQuery->execute();

        if (!$result) {
            return null;
        } else {
            $classes = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $classes;
        }
    }

    /**
     * List every entry in the database's "unit" table who have a particular "class" ID
     * 
     * @param int $classId ID of the "class" to look for
     * 
     * @return array|null $classes Returns an array with all the rows from the table, null if there were none
     */
    public function readAllUnitsWithClass(int $classId): array | null{
        $sqlQuery = "
            SELECT * 
            FROM `unit` 
            WHERE `class_id` = :classId;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":classId" => $classId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $classes = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $classes;
        }
    }

    /**
     * List every entry in the database's "user_unit" table who have a particular "class" ID
     * 
     * @param int $classId ID of the "class" to look for
     * 
     * @return array|null $classes Returns an array with all the rows from the table, null if there were none
     */
    public function readAllUserUnitsWithClass(int $classId): array | null{
        $sqlQuery = "
            SELECT * 
            FROM `user_unit` 
            WHERE `class_id` = :classId;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":classId" => $classId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $classes = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $classes;
        }
    }

    /**
     * Updates the data from a particular row in the "class" table
     * 
     * @param int $id ID of the row whose info will be changed
     * @param array $class Array with the new values for the fields in the table
     */
    public function update(int $id, array $class): bool {
        try {
            $sqlQuery = "
                UPDATE `class` SET `name` = :newName, 
                `type` = :newType 
                WHERE `id` = :id;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
                ":newName" => $class['name'],
                ":newType" => $class["type"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a Class: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Deletes a row from the "class" table
     * 
     * @param int $id ID of the row which will be deleted
     */
    public function delete(int $id): bool {
        $sqlQuery = "DELETE FROM `class` WHERE `id` = :id";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Class: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Returns a list of rows from the "class" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * 
     * @return array|null $classes Returns a list of rows who match the condition, or null if none did
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

        $sqlQuery = "SELECT * FROM `class` WHERE {$field} LIKE :conditionedSearch";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":conditionedSearch" => $condition
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $classes = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $classes;
        }
    }

    /**
     * Checks if a value from a certain field is already registered in the table
     * 
     * @param string $field Name of the field to check
     * @param string $fieldValue Value of the field to check
     * 
     * @return bool Indicates whether the value is already registered or not
     */
    public function exists(string $field, string $fieldValue): bool{
        $sqlQuery = "SELECT * FROM `class` WHERE $field = :fieldValue";
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

    /**
     * Adds a new entry in the "class_growths" table for an already existing "class" row
     * 
     * @param int $classId ID of the row from the "class" table
     * @param array $class Array with the required data for the new entry
     * 
     * @return int|null If the insertion could be made, an int is returned. Else, a null is
     */
    public function addGrowths(int $classId, array $class): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `class_growths`(`class_id`, `health`, `strength`, `magic`, `skill`, `speed`, 
                `luck`, `defense`, `resistance`) VALUES (:id, :health, :strength, :magic, :skill, 
                :speed, :luck, :defense, :resistance);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $classId,
                ":health" => $class["health_growth"],
                ":strength" => $class["strength_growth"],
                ":magic" => $class["magic_growth"],
                ":skill" => $class["skill_growth"],
                ":speed" => $class["speed_growth"],
                ":luck" => $class["luck_growth"],
                ":defense" => $class["defense_growth"],
                ":resistance" => $class["resistance_growth"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
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
        try {
            $sqlQuery = "
                UPDATE `class_growths` SET `health` = :health, `strength` = :strength, `magic` = :magic, 
                `skill` = :skill, `speed` = :speed, `luck` = :luck, `defense` = :defense, `resistance` = :resistance
                WHERE `class_id` = :classId;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":classId" => $classId,
                ":health" => $class["health_growth"],
                ":strength" => $class["strength_growth"],
                ":magic" => $class["magic_growth"],
                ":skill" => $class["skill_growth"],
                ":speed" => $class["speed_growth"],
                ":luck" => $class["luck_growth"],
                ":defense" => $class["defense_growth"],
                ":resistance" => $class["resistance_growth"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a Class\' growths: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Removes an existing row from the "class_growths" table
     * 
     * @param int $classId ID of the row from the "class" table
     */
    public function removeGrowths(int $classId): bool {
        $sqlQuery = "DELETE FROM `class_growths` WHERE `class_id` = :classId";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":classId" => $classId,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Class\' growths: ',  $error->getMessage(), "<br>";
            return false;
        }
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
        try {
            $sqlQuery = "
                INSERT INTO `class_promotion`(`starter_id`, `promoted_id`) 
                VALUES (:starterId, :promotedId);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":starterId" => $starterId,
                ":promotedId" => $promotedId
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Lists every "class_promotion" linked to a particular "class" ID
     * 
     * @param int $classID The ID of the "class"
     * 
     * @return array|null Returns an array with all the rows found, or null if there weren't any
     */
    public function readPromotions(int $classId): array | null {
        $sqlQuery = "
            SELECT p.`promoted_id`, c.`name`,
            g.`health` AS `health_growth`, g.`strength` AS `strength_growth`, 
            g.`magic` AS `magic_growth`, g.`skill` AS `skill_growth`, 
            g.`speed` AS `speed_growth`, g.`luck` AS `luck_growth`, 
            g.`defense` AS `defense_growth`, g.`resistance` AS `resistance_growth` 
            FROM `class_promotion` p
            LEFT JOIN `class` c
            ON (p.`promoted_id` = c.`id`) 
            LEFT JOIN `class_growths` g 
            ON (p.`promoted_id` = g.`class_id`) 
            WHERE p.`starter_id` = :starterId 
            ORDER BY p.`promoted_id`;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":starterId" => $classId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $classes = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $classes;
        }
    }

    /**
     * Lists every "class_promotion" not yet linked to a particular "class" ID
     * 
     * @param int $classID The ID of the "class"
     * 
     * @return array|null Returns an array with all the rows found, or null if there weren't any
     */
    public function readAvailablePromotions(int $classId): array | null {
        $sqlQuery = "
            SELECT c.`id`, c.`name`,
            g.`health` AS `health_growth`, g.`strength` AS `strength_growth`, 
            g.`magic` AS `magic_growth`, g.`skill` AS `skill_growth`, 
            g.`speed` AS `speed_growth`, g.`luck` AS `luck_growth`, 
            g.`defense` AS `defense_growth`, g.`resistance` AS `resistance_growth` 
            FROM `class` c
            LEFT JOIN `class_growths` g 
            ON (c.`id` = g.`class_id`) 
            WHERE c.`type` = 'Promoted' 
            AND c.`id` NOT IN 
            (SELECT `promoted_id` 
            FROM `class_promotion`
            WHERE `starter_id` = :starterId)
            ORDER BY c.`id`;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":starterId" => $classId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $classes = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $classes;
        }
    }

    /**
     * Deletes a row in the "class_promotion" table
     * 
     * @param int $starterId The ID of the starter class
     * @param int $promotionId The ID of the promoted class
     * 
     * @return bool Indicates whether the deletion was successful or not
     */
    public function removePromotion(int $starterId, int $promotedId): bool {
        $sqlQuery = "
            DELETE FROM `class_promotion` 
            WHERE `starter_id` = :starterId AND `promoted_id` = :promotedId;
        ";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":starterId" => $starterId,
                ":promotedId" => $promotedId
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Class\' promotion: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Adds a new entry in the "class_skill" table
     * 
     * @param int $classId ID of the "class" who will be linked to the "skill"
     * @param int $skillId ID of the "skill" who will be linked to the "class"
     * @param int $requiredLevel Level required to reach to obtain the skill
     * 
     * @return int|null If the insertion is successful, an int will be returned. Else, a null
     */
    public function addSkill(int $classId, int $skillId, int $requiredLevel = 10): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `class_skill`(`class_id`, `skill_id`, `level_required`) VALUES 
                (:classId, :skillId, :requiredLevel);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":classId" => $classId,
                ":skillId" => $skillId,
                ":requiredLevel" => $requiredLevel
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Removes an existing row from the "class_skill" table
     * 
     * @param int $classId ID of the row from the "class" table
     * @param int $skillId ID of the row from the "skill" table
     */
    public function removeSkill(int $classId, int $skillId): bool {
        $sqlQuery = "
            DELETE FROM `class_skill` 
            WHERE `class_id` = :classId AND skill_id = :skillId;
        ";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":classId" => $classId,
                ":skillId" => $skillId
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Class\' growths: ',  $error->getMessage(), "<br>";
            return false;
        }
    }
}