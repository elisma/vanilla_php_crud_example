<?php
include_once '../Models/ProfileCRUD.php';

use Models\ProfileCRUD;

// LOS HEADERS

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$crud = new ProfileCRUD();
$request = $crud->delete();

if($request){
    $response= ['status'=>'User deleted'];
}else{
    $response= ['status'=>'User delete failed'];

}
echo json_encode($response);;


?>