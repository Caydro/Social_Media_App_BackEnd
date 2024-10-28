<?php
//Connection File for PHP 

$dsn = "mysql:host=localhost;dbname=collage";

$user = "root";

$pass = "" ;


$option = array(
PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"  // for arabic Language in db environment
);


try{
    $conn = new PDO($dsn,$user,$pass,$option);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    echo $e->getMessage() . "your database access is restricted";
}

