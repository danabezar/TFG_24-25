<?php
require_once('config/db.php');
require_once('baseModel.php');

class SkillModel implements BaseModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Inserts a new entry in the database's "skill" table
     * 
     * @param array $skill Contains the values for each field in the table
     */
    public function insert(array $skill): int | null {
        try {
            $sqlQuery = "
                INSERT INTO `skill`(`name`, `type`, `attribute`, `value`) 
                VALUES (:name, :type, :attribute, :value);
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":name" => $skill["name"],
                ":type" => $skill["type"],
                ":attribute" => $skill["attribute"],
                ":value" => $skill["value"]
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Finds an entry in the database's "skill" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function findById(int $id): stdClass | null {
        $sqlQuery = "SELECT * FROM `skill` WHERE `id` = :id";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $skill = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($skill != false) ? $skill : null;
        }
    }

    /**
     * List every entry in the database's "skill" table and returns their info
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function readAll(): array | null{
        $sqlQuery = "
            SELECT * FROM `skill` 
            ORDER BY `id`;
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $result = $preparedQuery->execute();

        if (!$result) {
            return null;
        } else {
            $skills = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $skills;
        }
    }

    /**
     * Updates the data from a particular row in the "skill" table
     * 
     * @param int $id ID of the row whose info will be changed
     * @param array $skill Array with the new values for the fields in the table
     * 
     * @return bool Indicates whether the update was successful or not
     */
    public function update(int $id, array $skill): bool {
        try {
            $sqlQuery = "
                UPDATE `skill` 
                SET `name` = :newName, `type` = :newType, 
                `value` = :newValue, `attribute` = :newAttribute 
                WHERE `id` = :id;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
                ":newName" => $skill['name'],
                ":newType" => $skill["type"],
                ":newAttribute" => $skill["attribute"],
                ":newValue" => $skill["value"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a Skill: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Deletes a row from the "skill" table
     * 
     * @param int $id ID of the row which will be deleted
     */
    public function delete(int $id): bool {
        $sqlQuery = "DELETE FROM `skill` WHERE `id` = :id";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a Skill: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * Returns a list of rows from the "skill" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * 
     * @return array|null $skill Returns a list of rows who match the condition, or null if none did
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

        $sqlQuery = "SELECT * FROM `skill` WHERE {$field} LIKE :conditionedSearch";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":conditionedSearch" => $condition
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $skills = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $skills;
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
        $sqlQuery = "SELECT * FROM `skill` WHERE $field = :fieldValue";
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
     * Returns a list with all the entries in the "skill" table that aren't linked to a particular entry in the "class" table yet
     * 
     * @param int $classId ID of the row from the "class" table
     * 
     * @return array|null Returns an array with all the available rows, or null if there were none
     */
    function getAvailableForClass(int $classId): array | null {
        $sqlQuery = "
            SELECT * FROM `skill` 
            WHERE `id` NOT IN 
            (SELECT `skill_id` FROM `class_skill` 
            WHERE `class_id` = :classId);
        ";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":classId" => $classId
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $skills = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $skills;
        }
    }
}