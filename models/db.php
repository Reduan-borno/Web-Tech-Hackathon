<?php
class db{
public function connection(){
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "hotel_management";
    $connection = new mysqli($db_host,$db_user,$db_pass,$db_name);
    if($connection->connect_error){
        die("Connection Error: ".$connection->connect_error);
    }
    return $connection; 
} 
}
  