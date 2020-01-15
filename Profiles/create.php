<?php

include_once '../Utils/Database.php';
include_once '../Models/Profile.php';

use \Models\Profile;
use \Utils\Database;

// LOS HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
$db_connection = new Database();
$conn = $db_connection->get_connection();

//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>