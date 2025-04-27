<?php
require_once('config/db.php');

class UserModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Returns an int or a null depending if the operation could be succesfully made or not
    */
    public function insert(array $user): int | null {
        try {
            $sqlQuery = "INSERT INTO `user`(`username`, `password`)  VALUES (:username, :password);";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":username" => $user["username"],
                ":password" => password_hash($user["password"], PASSWORD_DEFAULT),
            ];
            $result = $preparedQuery->execute($dataArray);
            
            return ($result == true) ? $this->connection->lastInsertId() : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Returns a stdClass or a null depending if any row in the table was registered with the id
    */
    public function findById(int $id): stdClass | null {
        $sqlQuery = "SELECT * FROM user WHERE id = :id";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":id" => $id
        ];
        $result = $preparedQuery->execute($dataArray);
        
        if (!$result) {
            return null;
        }else{
            $user = $preparedQuery->fetch(PDO::FETCH_OBJ);
            return ($user != false) ? $user : null;
        }
    }

    /**
     * Returns an array with all the rows in the table or null if none where found
    */
    public function readAll(): array | null{
        $sqlQuery = "SELECT * FROM user";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $result = $preparedQuery->execute();

        if (!$result) {
            return null;
        } else {
            $users = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $users;
        }
    }

    /**
     * TODO: Add comment
     */
    public function update(int $id, array $user): bool {
        try {
            $sqlQuery = "
                UPDATE `user` SET `username` = :newUsername, 
                `password` = :newPassword 
                WHERE `id` = :id;
            ";
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
                ":newUsername" => $user['username'],
                ":newPassword" => password_hash($user["password"], PASSWORD_DEFAULT)
            ];

            return $preparedQuery->execute($dataArray);
        } catch (Exception $error) {
            echo 'An exception occured while editing a User: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /**
     * TODO: Add comment
     */
    public function delete(int $id): bool {
        $sqlQuery = "DELETE FROM `user` WHERE `id` = :id";

        try {
            $preparedQuery = $this->connection->prepare($sqlQuery);
            $dataArray = [
                ":id" => $id,
            ];
            $result = $preparedQuery->execute($dataArray);

            return ($preparedQuery->rowCount() > 0) ? true : false;
        } catch (Exception $error) {
            echo 'An exception ocurred while deleting a User: ',  $error->getMessage(), "<br>";
            return false;
        }
    }

    /*
     * TODO: Add comment
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

        $sqlQuery = "SELECT * FROM user WHERE {$field} LIKE :conditionedSearch";
        $preparedQuery = $this->connection->prepare($sqlQuery);
        $dataArray = [
            ":conditionedSearch" => $condition
        ];
        $result = $preparedQuery->execute($dataArray);

        if (!$result) {
            return null;
        } else {
            $users = $preparedQuery->fetchAll(PDO::FETCH_OBJ);
            return $users;
        }
    }

    /*
     * TODO: Add comment
    */
    public function exists(string $field, string $fieldValue): bool{
        $sqlQuery = "SELECT * FROM user WHERE $field = :fieldValue";
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