<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../Config/Database.php';
include_once '../Models/Number.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$numbers = new Number($db);

// query numeros
$number = $numbers->ActiveNumber(false);

echo json_encode($number);