<?php

include_once '../Utils/Database.php';
include_once '../Models/Profile.php';
include_once '../Models/ProfileCRUD.php';

use Models\ProfileCRUD;

// LOS HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$crud = new ProfileCRUD();
$request = $crud->create();
if($request){
    $response= ['status'=>'User created','user_id'=>$request];
}else{
    $response= ['status'=>'User creation failed'];

}

//ECHO DATA IN JSON FORMAT
echo  json_encode($response);
?>