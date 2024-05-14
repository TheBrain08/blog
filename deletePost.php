<?php
global $mysql;
session_start();
require('./rankManager.php');

require('./mysql.php');

if(isset($_SESSION['username']) && getRank($_SESSION['username']) > 0) {
    if(isset($_GET['postID'])) {
        $postID = $_GET['postID'];

        $delete_stmt = $mysql->prepare("DELETE FROM posts WHERE id = :postID");
        $delete_stmt->bindParam(':postID', $postID);
        $delete_stmt->execute();

        header("Location: allPosts.php");
        exit();
    } else {
        echo "Post ID not provided!";
    }
} else {
    echo "You do not have permission to delete posts!";
}
?>
