<?php
global $mysql;
if (isset($_POST['submit'])) {
    require('./mysql.php');
    $stmt = $mysql->prepare("SELECT * FROM users WHERE `username` = :user");
    $stmt->bindParam(':user', $_POST['username']);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count == 0) {
        if($_POST['pw'] == $_POST['pw2']) {
            $stmt = $mysql->prepare("INSERT INTO users (`username`, `password` , `email` , `rank`) VALUES (:username,:pw, :email, 0)");
            $stmt->bindParam(':username', $_POST['username']);
            $hash = password_hash($_POST['pw'], PASSWORD_DEFAULT);
            $stmt->bindParam(':pw', $hash);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->execute();
            echo "User has been created";
        } else {
            echo "Passwords do not match!";
        }
    } else {
        echo "Username already taken";
    }
}