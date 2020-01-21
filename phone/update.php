<?php
include_once '../Models/PhoneCRUD.php';

use Models\PhoneCRUD;


// LOS HEADERS

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//
$crud = new PhoneCRUD();
$request = $crud->update();
if($request){
    $response= ['status'=>'Phone updated'];
}else{
    $response= ['status'=>'Phone update failed'];

}
echo json_encode($response);


?>