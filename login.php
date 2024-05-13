<?php
global $mysql;
if (isset($_POST['submit'])) {
    require('./mysql.php');
    $stmt = $mysql->prepare("SELECT * FROM users WHERE `username` = :user");
    $stmt->bindParam(':user', $_POST['username']);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count == 1) {
        $row = $stmt->fetch();
        if (password_verify($_POST["pw"],$row["password"])) {
            session_start();
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id"];
            $_SESSION["rank"] = $row["rank"];

            header("Location: ./index.php");
        } else {
            echo "Login failed";
            echo "Password doesnt match with the Database";
        }
    } else {
        echo "Login failed";
        echo "User doesn't exist";
    }
}