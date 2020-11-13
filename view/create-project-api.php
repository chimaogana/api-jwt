<?php

require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charst=UTF-8");
header("Access-Control-Allow-Headers, Authorization");




//
include_once("../config/database.php");
include_once("../classes/Users.php");


$db = new Database();
$connection = $db->connect();

$user_obj = new Users($connection);

if($_SERVER['REQUEST_METHOD']==="POST"){
   $data = json_decode(file_get_contents("php://input"));
   $all_headers = getallheaders();
   if(!empty($data->name) && (!empty($data->description)&&(!empty($data->status)))){
     try{
      $jwt = $all_headers['Authorization'];       
      $secret_data = "owt125";
      $decode_data= JWT::decode($jwt,$secret_data,array('HS512'));
      $user_obj->user_id=$decode_data->data->id;
      $user_obj->project_name= $data->name;
      $user_obj->description=$data->description;
      $user_obj->status=$data->status;
      if($user_obj->create_project()){
        http_response_code(200);
          echo json_encode(array(
          "status"=>1,
          "jwt"=>$jwt,
          "message"=>"Project created successfully"
          ));

      }else{
        http_response_code(500);
        echo json_encode(array(
          "status"=>0,
          "message"=> "failed to create project"
        ));

      }     

     }catch(Exception $ex){
       http_response_code(500);
       echo json_encode(array(
         "status"=>0,
         "message"=>$ex->getMessage()
       ));
     }
   }else{
     http_response_code(404);
     echo json_encode(array(
       "status"=>0,
       "message"=>"All data needed"
     ));
    }
}
 