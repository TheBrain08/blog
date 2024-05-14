<?php
global $mysql;
session_start();
require('./rankManager.php');
require('./mysql.php');
$stmt = $mysql->prepare("SELECT * FROM posts");
$stmt->execute();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="images/Placeholder.png" type="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="default.css">
    <link rel="stylesheet" href="allPosts.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,100&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


<body>
<?php
include "./header.html";
?>
<main>

        <?php
        while ($row = $stmt->fetch()) {
            $title = $row["title"];
            $content = $row["content"];
            $created = $row["created_at"];
            $author = $row["author_id"];
            $id = $row["id"];
            $pic = $row["pic"];

            $user_query = $mysql->prepare("SELECT `username` FROM users WHERE id = :author_id");
            $user_query->bindParam(':author_id', $author);
            $user_query->execute();
            $user_row = $user_query->fetch();
            $username = $user_row["username"];


            echo "<div class='posts'>";


            echo "<h1 class='text-center title'> {$title} ";
            if (isset($_SESSION["username"])) {
                if (getRank($_SESSION['username']) > 0) {
                    echo "<a href='./deletePost.php?postID={$id}' style='color: red; font-size: 25px'><i class='bi bi-slash-circle'></i></a>";
                }
            }
            echo "</h1>";

            if ($pic) {
                echo "<img class='float-end rounded-4 img-fluid' src='images/{$id}.png'>";
            }
            echo "<p class='content'>" . nl2br($content) . "</p>";
            echo "<p class='timestamp align-self-end'> Created by {$username} at {$created}.</p>";
            echo "</div>";

        }
        ?>

</main>

<?php
include "./footer.html";
?>
</body>
</html>
