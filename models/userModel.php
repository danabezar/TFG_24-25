<?php
require_once('config/db.php');
require_once('baseModel.php');

class UserModel implements BaseModel{
    private $connection;
    public function __construct(){
        $this->connection = db::connection();
    }

    /**
     * Inserts a new entry in the database's "user" table
     * 
     * @param array $user Contains the values for each field in the table
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
     * Finds an entry in the database's "user" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns a "stdClass" type if a matching row was found, null if it wasn't
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
     * List every entry in the database's "user" table and returns their info
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
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
     * Updates the data from a particular row in the "user" table
     * 
     * @param array $user Array with the new values for the fields in the table
     * 
     * @return bool Indicates whether the update was successful or not
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
     * Deletes a row from the "user" table
     * 
     * @param int $id ID of the row which will be deleted
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

    /**
     * Returns a list of rows from the "user" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * 
     * @return array|null $users Returns a list of rows who match the condition, or null if none did
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

    /**
     * Checks if a value from a certain field is already registered in the table
     * 
     * @param string $field Name of the field to check
     * @param string $fieldValue Value of the field to check
     * 
     * @return bool Indicates whether the value is already registered or not
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