<?php
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");


//
include_once("../config/database.php");
include_once("../classes/Users.php");


$db = new Database();
$connection = $db->connect();

$user_obj = new Users($connection);
if($_SERVER['REQUEST_METHOD']==="GET"){
  $all_headers = getallheaders();
  $jwt = $all_headers['Authorization'];
try{
  $secret_data = "owt125";
  $decode_data= JWT::decode($jwt,$secret_data,array('HS512'));
  $user_obj->user_id = $decode_data->data->id;

  $projects = $user_obj->get_user_all_projects();
 
 if($projects->num_rows>0){
   $projects_arr = array();
   while($row = $projects->fetch_assoc()){
     $projects_arr[] = array(
       'id' => $row['id'],
       'name'=>$row['name'],
       "description"=>$row['description'],
       "status"=>$row['status'],
       "created_at"=>$row['created_at']
     );
   }
   http_response_code(200);
   echo json_encode(array(
     "status"=>1,
     "project"=>$projects_arr
   ));
 
 }else{
   http_response_code(404);
   echo json_encode(array(
     "status"=>0,
     "message"=>"no data found"
   ));
 }
}catch(Exception $ex){
  http_response_code(500);
   echo json_encode(array(
     "status"=>0,
     "message"=>$ex->getMessage(),
   ));
}
 

}