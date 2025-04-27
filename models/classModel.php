<?php
require_once('config/db.php');

class ClassModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Returns an ID if the insertion was succesful, else a null is returned
    */
    public function insert(array $class): int | null {
        try {
            $sqlQuery = "INSERT INTO `class`(`name`, `type`)  VALUES (:name, :type);";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":name" => $class["name"],
                ":type" => $class["type"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Returns a stdClass object if a matching row was found, else a null is returned
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
     * TODO: Add comment
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
     * TODO: Add comment
     */
    public function findByIdSkills(int $id): array | null {
        $sqlQuery = "
            SELECT c.`id`, s.`id`, s.`name`, s.`type`, s.`attribute`, s.`value` 
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
     * TODO: Add comment
    */
    public function findByIdDetailed(int $id): stdClass | null {
        $sqlQuery = "
            SELECT c.`id`, c.`name`, c.`type`, 
            g.`health_growth`, g.`strength_growth`, g.`magic_growth`, g.`skill_growth`, 
            g.`speed_growth`, g.`luck_growth`, g.`defense_growth`, g.`resistance_growth` 
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
     * Returns an array with all the rows in the table or null if none where found
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
     * Returns an array with all the rows in the table with additional info, or null if none where found
    */
    public function readAllDetailed(): array | null{
        $sqlQuery = "
            SELECT c.`id`, c.`name`, c.`type`, 
            g.`health_growth`, g.`strength_growth`, g.`magic_growth`, g.`skill_growth`, 
            g.`speed_growth`, g.`luck_growth`, g.`defense_growth`, g.`resistance_growth` 
            FROM `class` c
            LEFT JOIN `class_growths` g
            ON (c.`id` = g.`class_id`)
            ORDER BY u.`id`;
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
     * TODO: Add comment
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
     * TODO: Add comment
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

    /*
     * Returns a series of rows which match the conditions sent via parameters
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

    /*
     * Checks if any row in the table exists with the specified value in a certain field
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
     * TODO: Add comment
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
     * TODO: Add comment
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
     * TODO: Add comment
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
     * TODO: Add comment
     */
    public function addSkill(int $classId, int $skillId, int $requiredLevel = 10): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `class_skill`(`class_id`, `skill_id`, `level_required` VALUES 
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
     * TODO: Add comment
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