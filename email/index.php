<?php

include_once '../Models/Profile.php';
include_once '../Models/EmailCRUD.php';

use Models\EmailCRUD;

// LOS HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");



$crud= new EmailCRUD();
$request = $crud->read();
//encodes to JSON
echo json_encode($request);
?>

