<?php
include_once '../Models/EmailCRUD.php';

use Models\EmailCRUD;


// LOS HEADERS

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//
$crud = new EmailCRUD();
$request = $crud->update();
if($request){
    $response= ['status'=>'Email updated'];
}else{
    $response= ['status'=>'Email update failed'];

}
echo json_encode($response);


?>