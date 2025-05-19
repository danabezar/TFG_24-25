<?php
require_once('config/db.php');
require_once('baseModel.php');

class UnitModel implements BaseModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Inserts a new entry in the database's "unit" table
     * 
     * @param array $unit Contains the values for each field in the table
     */
    public function insert(array $unit): int | null {
        try {
            $sqlQuery = "INSERT INTO `unit`(`name`, `class_id`)  VALUES (:name, :classId);";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":name" => $unit["name"],
                ":classId" => $unit["class"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Finds an entry in the database's "unit" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function findById(int $id): stdClass | null {
        $sqlQuery = "SELECT * FROM `unit` WHERE `id` = :id";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $unit = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($unit != false) ? $unit : null;
        }
    }

    /**
     * TODO: Add comment
     */
    public function findByIdBases(int $id): stdClass | null {
        $sqlQuery = "
            SELECT u.`id`, b.`health`, b.`strength`, b.`magic`, b.`skill`, 
            b.`speed`, b.`luck`, b.`defense`, b.`resistance` 
            FROM `unit` u
            LEFT JOIN `unit_base_stats` b
            ON (u.`id` = b.`unit_id`)
            WHERE u.`id` = :id
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $unit = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($unit != false) ? $unit : null;
        }
    }

    /**
     * TODO: Add comment
     */
    public function findByIdGrowths(int $id): stdClass | null {
        $sqlQuery = "
            SELECT u.`id`, g.`health`, g.`strength`, g.`magic`, g.`skill`, 
            g.`speed`, g.`luck`, g.`defense`, g.`resistance` 
            FROM `unit` u
            LEFT JOIN `unit_growths` g
            ON (u.`id` = g.`unit_id`)
            WHERE u.`id` = :id
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $unit = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($unit != false) ? $unit : null;
        }
    }

    /**
     * Finds an entry in the database's "unit" table and returns its info, with extra details
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function findByIdDetailed(int $id): stdClass | null {
        $sqlQuery = "
            SELECT u.`id`, u.`name`, c.`id` AS `classId`, c.`name` AS `class`, b.`level` AS `level_base`, 
            b.`health` AS `health_base`, b.`strength` AS `strength_base`, 
            b.`magic` AS `magic_base`, b.`skill` AS `skill_base`, 
            b.`speed` AS `speed_base`, b.`luck` AS `luck_base`, 
            b.`defense` AS `defense_base`, b.`resistance` AS `resistance_base`, 
            g.`health` AS `health_growth`, g.`strength` AS `strength_growth`, 
            g.`magic` AS `magic_growth`, g.`skill` AS `skill_growth`, 
            g.`speed` AS `speed_growth`, g.`luck` AS `luck_growth`, 
            g.`defense` AS `defense_growth`, g.`resistance` AS `resistance_growth` 
            FROM `unit` u 
            LEFT JOIN `class` c 
            ON (c.`id` = u.`class_id`) 
            LEFT JOIN `unit_base_stats` b 
            ON (u.`id` = b.`unit_id`) 
            LEFT JOIN `unit_growths` g
            ON (u.`id` = g.`unit_id`) 
            WHERE u.`id` = :id;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $unit = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($unit != false) ? $unit : null;
        }
    }

    /**
     * List every entry in the database's "unit" table and returns their info
     *      * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function readAll(): array | null{
        $sqlQuery = "
            SELECT * FROM `unit` 
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
     * List every entry in the database's "unit" table and returns their info, with extra details
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function readAllDetailed(): array | null{
        $sqlQuery = "
            SELECT u.`id`, u.`name`, c.`id` AS `classId`, c.`name` AS `class`, b.`level` AS `level_base`, 
            b.`health` AS `health_base`, b.`strength` AS `strength_base`, 
            b.`magic` AS `magic_base`, b.`skill` AS `skill_base`, 
            b.`speed` AS `speed_base`, b.`luck` AS `luck_base`, 
            b.`defense` AS `defense_base`, b.`resistance` AS `resistance_base`, 
            g.`health` AS `health_growth`, g.`strength` AS `strength_growth`, 
            g.`magic` AS `magic_growth`, g.`skill` AS `skill_growth`, 
            g.`speed` AS `speed_growth`, g.`luck` AS `luck_growth`, 
            g.`defense` AS `defense_growth`, g.`resistance` AS `resistance_growth` 
            FROM `unit` u 
            LEFT JOIN `class` c 
            ON (c.`id` = u.`class_id`) 
            LEFT JOIN `unit_base_stats` b 
            ON (u.`id` = b.`unit_id`) 
            LEFT JOIN `unit_growths` g 
            ON (u.`id` = g.`unit_id`) 
            ORDER BY u.`id`;
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
     * Updates the data from a particular row in the "unit" table
     * 
     * @param int $id ID of the "unit" to be updated
     * @param array $unit Array with the new values for the fields in the table
     */
    public function update(int $id, array $unit): bool {
        try {
            $sqlQuery = "
                UPDATE `unit` SET `name` = :newName, 
                `class_id` = :newClassId 
                WHERE `id` = :id;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
                ":newName" => $unit['name'],
                ":newClassId" => $unit["class"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a Unit: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Deletes a row from the "unit" table
     * 
     * @param int $id ID of the row which will be deleted
     */
    public function delete(int $id): bool {
        $sqlQuery = "DELETE FROM `unit` WHERE `id` = :id";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Unit: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Returns a list of rows from the "unit" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * 
     * @return array|null $units Returns a list of rows who match the condition, or null if none did
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
            SELECT u.`id`, u.`name`, c.`id` AS `classId`, c.`name` AS `class`, b.`level` AS `level_base`, 
            b.`health` AS `health_base`, b.`strength` AS `strength_base`, 
            b.`magic` AS `magic_base`, b.`skill` AS `skill_base`, 
            b.`speed` AS `speed_base`, b.`luck` AS `luck_base`, 
            b.`defense` AS `defense_base`, b.`resistance` AS `resistance_base`, 
            g.`health` AS `health_growth`, g.`strength` AS `strength_growth`, 
            g.`magic` AS `magic_growth`, g.`skill` AS `skill_growth`, 
            g.`speed` AS `speed_growth`, g.`luck` AS `luck_growth`, 
            g.`defense` AS `defense_growth`, g.`resistance` AS `resistance_growth` 
            FROM `unit` u 
            LEFT JOIN `class` c 
            ON (c.`id` = u.`class_id`) 
            LEFT JOIN `unit_base_stats` b 
            ON (u.`id` = b.`unit_id`) 
            LEFT JOIN `unit_growths` g 
            ON (u.`id` = g.`unit_id`) 
            WHERE {$field} LIKE :conditionedSearch
            ORDER BY u.`id`; 
        ";
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

    /**
     * TODO: Add comment
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

    /**
     * TODO: Add comment
    */
    public function addBases(int $unitId, $unit): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `unit_base_stats`(`unit_id`, `level`, `health`, `strength`, `magic`, `skill`, 
                `speed`, `luck`, `defense`, `resistance`) VALUES (:id, :level, :health, :strength, :magic, 
                :skill, :speed, :luck, :defense, :resistance);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $unitId, 
                ":level" => $unit["level_base"], 
                ":health" => $unit["health_base"], 
                ":strength" => $unit["strength_base"], 
                ":magic" => $unit["magic_base"], 
                ":skill" => $unit["skill_base"], 
                ":speed" => $unit["speed_base"], 
                ":luck" => $unit["luck_base"], 
                ":defense" => $unit["defense_base"], 
                ":resistance" => $unit["resistance_base"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * TODO: Add comment
    */
    public function updateBases(int $unitId, $unit): bool {
        try {
            $sqlQuery = "
                UPDATE `unit_base_stats` SET `level` = :level, `health` = :health, `strength` = :strength, 
                `magic` = :magic, `skill` = :skill, `speed` = :speed, `luck` = :luck, `defense` = :defense, 
                `resistance` = :resistance 
                WHERE `unit_id` = :unitId;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":unitId" => $unitId,
                ":level" => $unit["level_base"], 
                ":health" => $unit["health_base"],
                ":strength" => $unit["strength_base"],
                ":magic" => $unit["magic_base"],
                ":skill" => $unit["skill_base"],
                ":speed" => $unit["speed_base"],
                ":luck" => $unit["luck_base"],
                ":defense" => $unit["defense_base"],
                ":resistance" => $unit["resistance_base"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a Unit\'s base stats: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * TODO: Add comment
    */
    public function deleteBases(int $unitId): bool {
        $sqlQuery = "DELETE FROM `unit_base_stats` WHERE `unit_id` = :unitId";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":unitId" => $unitId,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Unit\' base stats: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * TODO: Add comment
    */
    public function addGrowths(int $unitId, $unit): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `unit_growths`(`unit_id`, `health`, `strength`, `magic`, `skill`, `speed`, 
                `luck`, `defense`, `resistance`) VALUES (:id, :health, :strength, :magic, :skill, 
                :speed, :luck, :defense, :resistance);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $unitId,
                ":health" => $unit["health_growth"],
                ":strength" => $unit["strength_growth"],
                ":magic" => $unit["magic_growth"],
                ":skill" => $unit["skill_growth"],
                ":speed" => $unit["speed_growth"],
                ":luck" => $unit["luck_growth"],
                ":defense" => $unit["defense_growth"],
                ":resistance" => $unit["resistance_growth"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * TODO: Add comment
    */
    public function updateGrowths(int $unitId, $unit): bool {
        try {
            $sqlQuery = "
                UPDATE `unit_growths` SET `health` = :health, `strength` = :strength, `magic` = :magic, 
                `skill` = :skill, `speed` = :speed, `luck` = :luck, `defense` = :defense, `resistance` = :resistance
                WHERE `unit_id` = :unitId;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":unitId" => $unitId,
                ":health" => $unit["health_growth"],
                ":strength" => $unit["strength_growth"],
                ":magic" => $unit["magic_growth"],
                ":skill" => $unit["skill_growth"],
                ":speed" => $unit["speed_growth"],
                ":luck" => $unit["luck_growth"],
                ":defense" => $unit["defense_growth"],
                ":resistance" => $unit["resistance_growth"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a Unit\'s growths: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * TODO: Add comment
    */
    public function deleteGrowths(int $unitId): bool {
        $sqlQuery = "DELETE FROM `unit_growths` WHERE `unit_id` = :unitId";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":unitId" => $unitId,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Unit\' growths: ',  $error->getMessage(), "<br>";
            return false;
        }
    }
}