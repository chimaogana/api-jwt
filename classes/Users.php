<?php

class Users {



  public $name;
  public $email;
  public $password;
  public $user_id;
  public $project_name;
  public $description;
  public $status;


  private $conn;
  private $users_tbl;
  private $projects_tbl;

  public function __construct($db){
    $this->conn = $db;
    $this->users_tbl =  'tbl_users';
    $this->projects_tbl = 'tbl_projects';
  }
  public function create_user(){
    $user_query = "INSERT INTO tbl_users SET name = ?, email = ?, password = ?";

    $user_obj = $this->conn->prepare($user_query);
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = htmlspecialchars(strip_tags($this->password));
    $user_obj->bind_param("sss", $this->name, $this->email, $this->password);

    if($user_obj->execute()){
      return true;
    }
    return false;
  }

  public function check_email(){
    $email_query = "SELECT * from  tbl_users WHERE email = ?";
    $user_obj = $this->conn->prepare($email_query);
    
    $user_obj->bind_param("s",$this->email);
    if($user_obj->execute()){
      $data = $user_obj->get_result();

      return $data->fetch_assoc();
    }return array();
    
  }
  public function check_login(){
    $query = "SELECT * from  tbl_users WHERE email = ?";
    $user_obj = $this->conn->prepare($query);
    
    $user_obj->bind_param("s",$this->email);
    if($user_obj->execute()){
      $data = $user_obj->get_result();

      return $data->fetch_assoc();
    }return array();
  }
  public function create_project(){
    $project_query = "INSERT into ". $this->projects_tbl." SET user_id = ?, name=?, description=?,status=?";
    $project_obj = $this->conn->prepare($project_query);
    $this->project_name = htmlspecialchars(strip_tags($this->project_name));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->status = htmlspecialchars(strip_tags($this->status));
    $project_obj->bind_param("isss", $this->user_id,$this->project_name,$this->description,$this->status);
    
    if($project_obj->execute()){
      return true;
    }
    return false;

  }
  
  public function get_all_projects(){

    $projects_query="SELECT * from ". $this->projects_tbl." ORDER BY id DESC";
    $projects_obj = $this->conn->prepare($projects_query);
    $projects_obj->execute();
    return $projects_obj->get_result();
    
  }
  public function get_user_all_projects(){

    $projects_query="SELECT * from ". $this->projects_tbl." WHERE user_id=? ORDER BY id DESC";
    $projects_obj = $this->conn->prepare($projects_query);
    $projects_obj->bind_param("i", $this->user_id);
    $projects_obj->execute();
    return $projects_obj->get_result();
    
  }
}































?>