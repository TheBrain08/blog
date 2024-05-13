<?php
function getRank($username){
    global $mysql;
    require("mysql.php");
    $stmt = $mysql->prepare('SELECT `rank` FROM `users` WHERE `username` =  :user');
    $stmt->bindParam(":user", $username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row["rank"];
}