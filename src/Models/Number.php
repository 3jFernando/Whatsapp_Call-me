<?php

class Number {

    private $conn;
    private $table = "numbers";

    public $id;
    public $code;
    public $number;
    public $link;
    public $active;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read products
    public function Reader(){

        // select all query
        $query = "SELECT * FROM ".$this->table;

        // prepare query statement
        $numbersQuery = $this->conn->prepare($query);

        // execute query
        $numbersQuery->execute();

        $numbersArray = [];
        while ($row = $numbersQuery->fetch(PDO::FETCH_ASSOC)){

            extract($row);
            $number = [
                "id" => $row['id'],
                "code" => $row['code'],
                "number" => $row['number'],
                "link" => $row['link'],
                "active" => $row['active']
            ];

            array_push($numbersArray, $number);
        }

        return $numbersArray;
    }

    public function Form($id, $code, $number, $action)
    {

        try {

            if($action == 1)
                $query = "INSERT INTO ".$this->table." (code, number, link) VALUES ('".$code."','".$number."','https://wa.me/".$code.$number."')";
            else
                $query = "UPDATE ".$this->table." SET code = '".$code."',number = '".$number."', link = 'https://wa.me/".$code.$number."' WHERE id = ".$id;

            // prepare query statement
            $number = $this->conn->prepare($query);

            // execute query
            $number->execute();

            return true;

        } catch(PDOException $exception){
            return false;
        }

    }

}