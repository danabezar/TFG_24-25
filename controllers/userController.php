<?php
require_once "models/userModel.php";
require_once "assets/php/functions.php";

class UserController{
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    /**
     * Inserts a new entry in the database's "user" table
     * 
     * @param array $userDataArray Contains the values for each field in the table
     */
    public function create(array $userDataArray): void{
        $error = false;
        $errors = [];

        //In case we need to add error and form data, we erase the previously registered ones
        $_SESSION["errors"] = [];
        $_SESSION["formData"] = [];

        //Empty field checks
        $nonNullableFields = [
            "username", 
            "password"
        ];
        $uniqueFields = ["username"];
        $dataIsValid = isValidFormData($nonNullableFields, $uniqueFields, $userDataArray, $this->model);

        if($dataIsValid){
            $newUserId = $this->model->insert($userDataArray);

            if($newUserId != null){
                header("location:index.php?table=user&action=show&id=".$newUserId);
                exit();

            }else{
                header("location:index.php?table=user&action=create&error=true");
                exit();
            }
        }else{
            header("location:index.php?table=user&action=create&error=true");
            exit();
        }
    }

    /**
     * Finds an entry in the database's "user" table and returns its info
     * 
     * @param int $id ID of the row whose information will be retrieved
     * 
     * @return stdClass|null Returns an "stdClass" type if a matching row was found, null if it wasn't
     */
    public function read(int $id): stdClass | null {
        return $this->model->findById($id);
    }

    /**
     * List every entry in the database's "user" table and returns their info
     * 
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null Returns an array with all the rows from the table, null if there were none
     */
    public function list(bool $canBeErased = false): array | null {
        $users = $this->model->readAll();
        return $users;
    }

    /**
     * Deletes a row from the "user" table
     * 
     * @param int $userId ID of the row which will be deleted
     */
    public function delete(int $userId): void{
        $this->model->delete($userId);
        header("location:index.php?table=user&action=list");
        exit();
    }

    /**
     * Returns a list of rows from the "user" table where certain conditions are met
     * 
     * @param string $field Name of the field to apply a condition
     * @param string $searchType Type of condition to apply to the specified field
     * @param string $searchString String required to apply the condition
     * @param bool $canBeErased Used to add an extra field which indicates if the entry can or cannot be deleted due to foreign key restrictions
     * 
     * @return array|null $users Returns a list of rows who match the condition, or null if none did
     */
    public function search(
        string $field = "username", 
        string $searchType = "contains", 
        string $searchString = "", 
        bool  $canBeErased = false
        ): array | null{
            $users = $this->model->search($field, $searchType, $searchString);
            return $users;
    }
}