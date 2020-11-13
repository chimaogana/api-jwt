<?php
ini_set("display_errors",1);
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charst=UTF-8");



//
include_once("../config/database.php");
include_once("../classes/Users.php");


$db = new Database();
$connection = $db->connect();

$user_obj = new Users($connection);

if($_SERVER['REQUEST_METHOD']==="POST"){
  $data = json_decode(file_get_contents("php://input"));

  if(!empty($data->email)&&(!empty($data->password))){
     $user_obj->email = $data->email;
     $user_data = $user_obj->check_login();
     if(!empty($user_data)){
        $name = $user_data['name'];
        $email = $user_data['email'];
        $password = $user_data['password'];
        if(password_verify($data->password, $password)){
          $iss= "localhost";
          $iat=time();
          $nbf = $iat+10;
          $exp = $iat+180;
          $aud = "myusers";
          $user_arr_data = array(
            'id'=>$user_data[id],
            'name'=>$user_data['name'],
            'email'=>$user_data['email'],
          );
          $secret_key = "owt125";
          $payload_info = array(
           "iss"=>$iss,
           "iat"=>$iat,
           "nbf"=>$nbf,
           "exp"=>$exp,
           "aud"=>$aud,
           "data"=> $user_arr_data
          );
           $jwt = JWT::encode($payload_info, $secret_key, 'HS512');
          http_response_code(200);
          echo json_encode(array(
          "status"=>1,
          "jwt"=>$jwt,
          "message"=>"User login successfully"
          ));

        }else{

          http_response_code(404);
          echo json_encode(array(
          "status"=>0,
          "message"=>"invalid credential"
          )); 
        }
     }else{
        
          http_response_code(404);
          echo json_encode(array(
          "status"=>0,
          "message"=>"invalid credential"
          )); 
     }

  }else{

          http_response_code(404);
          echo json_encode(array(
          "status"=>0,
          "message"=>"All data needed"
          )); 
  }
}else{
  http_response_code(503);
  echo json_encode(array(
  "status"=>0,
  "message"=>"Access denied"
  )); 
}




?>