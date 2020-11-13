<?php

class Database 
{

   private $hostname;
   private $dbname;
   private $username;
   private $password;
   private $conn;

   public function connect(){
     $this->hostname = 'localhost';
     $this->dbname = 'rest_php_api';
     $this->username = 'root';
     $this->password = '';
     $this->conn = mysqli_connect($this->hostname,$this->username,$this->password,$this->dbname);
     if(mysqli_connect_errno()){
      echo 'Failed to connect to MYSQL'. mysqli_connect_errno();
     }
     return $this->conn;
   }
  }

  //  $db = new Database();
  //  var_dump($db->connect());



?>