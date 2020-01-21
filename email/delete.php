<?php
include_once '../Models/EmailCRUD.php';

use Models\EmailCRUD;

// LOS HEADERS

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$crud = new EmailCRUD();
$request = $crud->delete();

if($request){
    $response= ['status'=>'Email deleted'];
}else{
    $response= ['status'=>'Email delete failed'];

}
echo json_encode($response);;


?>