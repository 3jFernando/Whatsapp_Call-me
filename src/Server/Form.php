<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../Config/Database.php';
include_once '../Models/Number.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$number = new Number($db);

$result = $number->Form($_REQUEST['id'], $_REQUEST['code'], $_REQUEST['number'], $_REQUEST['action']);
echo json_encode($result);