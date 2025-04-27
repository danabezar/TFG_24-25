<?php
class db{
    const HOST = "localhost";
    const DBNAME = "tfg";
    const USER = "root";
    const PASSWORD = "";

    static public function connection(){
        $connection = null;

        try {
            $options =  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $connection = new PDO('mysql:host=localhost;  dbname=' . self::DBNAME, self::USER, self::PASSWORD, $options);
        } catch (Exception $e) {
            echo "There's been a problem with the database: " . $e->getMessage();
        }

        return $connection;
    }
}
