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

    // guardar y editar
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

    // activar numero
    public function ActiveNumber($state)
    {

        $numbersQuery = $this->querySelect(true, null);

        $number = null;
        $numberActive = false;
        $numberActiveState = false;
        while ($row = $numbersQuery->fetch(PDO::FETCH_ASSOC)){
            if($state) {
                $numberActiveState = true;
                $numberActive = true;

                $this->queryUpdate(1);
                $number = $this->querySelect(false, 1);
            }
            if(!$numberActiveState) {
                if($row['active'] == 1) {

                    $cant = $this->queryCount();
                    $newItemActive = (int)($row['id'] + 1);

                    $numberActive = true;
                    $number = $row['link'];

                    $this->queryUpdate(($newItemActive > $cant) ? 1 : $newItemActive);
                }
            }
        }

        return ($numberActive) ? $number : $this->activeOtherNumber(true);

    }

    public function activeOtherNumber($state)
    {
        return $this->ActiveNumber($state);
    }

    public function querySelect($all, $param)
    {
        // select all query
        $query = ($all) ? "SELECT * FROM {$this->table}" : "SELECT * FROM {$this->table} WHERE id = {$param};";

        // prepare query statement
        $numbersQuery = $this->conn->prepare($query);

        // execute query
        $numbersQuery->execute();

        return $numbersQuery;
    }

    public function queryUpdate($param)
    {

        try {
            // select all query
            $queryAll = "UPDATE {$this->table} SET active = 0 WHERE id > 0;";
            $query = "UPDATE {$this->table} SET active = 1 WHERE id = {$param};";

            // prepare query statement
            $this->conn->prepare($queryAll)->execute();
            $this->conn->prepare($query)->execute();

        } catch (\Exception $e) {
            // select all query
            $queryAll = "UPDATE {$this->table} SET active = 0 WHERE id > 0;";
            $query = "UPDATE {$this->table} SET active = 1 WHERE id = 1;";

            // prepare query statement
            $this->conn->prepare($queryAll)->execute();
            $this->conn->prepare($query)->execute();
        }

    }

    public function queryCount()
    {
        $queryCount = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table};");
        $queryCount->execute();
        $cant = $queryCount->fetchColumn();

        return $cant;
    }

}