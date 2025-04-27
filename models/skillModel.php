<?php
require_once('config/db.php');

class SkillModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Returns an ID if the insertion was succesful, else a null is returned
    */
    public function insert(array $skill): int | null {
        try {
            $sqlQuery = "INSERT INTO `skill`(`name`, `type`, `value`)  VALUES (:name, :type, :value);";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":name" => $skill["name"],
                ":type" => $skill["type"],
                ":value" => $skill["value"]
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
     * Returns an array with all the rows in the table or null if none where found
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
     * TODO: Add comment
     */
    public function update(int $id, array $skill): bool {
        try {
            $sqlQuery = "
                UPDATE `skill` SET `name` = :newName, 
                `type` = :newType, `value` =:newValue 
                WHERE `id` = :id;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
                ":newName" => $skill['name'],
                ":newType" => $skill["type"],
                ":newValue" => $skill["value"]
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a Skill: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * TODO: Add comment
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

    /*
     * Checks if any row in the table exists with the specified value in a certain field
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
}