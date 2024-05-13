<?php
$db_host = "localhost";
$db_name = "blog_db";
$db_user = "root";
$db_pass = "";

try{
    $mysql = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
} catch (PDOException $e){
    echo "SQL Error: ".$e->getMessage();
}
