<?php
global $mysql;
session_start();
if(!isset($_SESSION["username"])){
    header("Location: login.html");
    exit();
}

require('./rankManager.php');
if(getRank($_SESSION['username']) < 1){
    header("Location: index.php");
    exit();
}

function addPost()
{
    require('./mysql.php');
    global $mysql;
    $authorID = $_SESSION["id"];
    $stmt = $mysql->prepare("INSERT INTO posts (`title`, `content`, `author_id`) VALUES (:title, :content, $authorID)");
    $stmt->bindParam(':title', $_POST["title"]);
    $stmt->bindParam(':content', $_POST["content"]);
    $stmt->execute();
}

if(isset($_POST["submit"])) {
    if(isset($_POST["thumbnailCheck"])){
        $uploadThumbnail = $_POST["thumbnailCheck"];
    } else {
        $uploadThumbnail = false;
    }
    if (!$uploadThumbnail) {
        addPost();
    } else {
        require('./mysql.php');
        $authorID = $_SESSION["id"];
        $file = $_FILES["thumbnail"];

        $fileName = $file["name"];
        $fileTmpName = $file["tmp_name"];
        $fileSize = $file["size"];
        $fileError = $file["error"];
        $fileType = $file["type"];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');
        if(in_array($fileActualExt, $allowed)) {
            if($fileError === 0) {
                if($fileSize < 1000000) {
                    $stmtNextID = $mysql->prepare("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = 'posts' AND table_schema = DATABASE();");
                    $stmtNextID->execute();
                    $rowNextID = $stmtNextID->fetch();
                    $nextID = $rowNextID["AUTO_INCREMENT"];

                    $fileNameNew = $nextID . "." . $fileActualExt;
                    $fileDestination = './images/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);

                    addPost();
                    $id = $mysql->lastInsertId();

                    $stmtUpdatePost = $mysql->prepare("UPDATE posts SET pic = true WHERE id = '$id'");
                    $stmtUpdatePost->execute();
                } else {
                    echo "Your file is too big.";
                }
            } else {
                echo "There was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
        }
    }
}
?>



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="images/Placeholder.png" type="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="default.css">
    <link rel="stylesheet" href="newPost.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,100&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


</head>

<body>
<?php
include "./header.html";
?>

<main>
    <div class=" row">
        <div class="col-12 col-sm-0 col-md-0 col-xl-3"></div>
        <div class="col-12 col-sm-0 col-md-0 col-xl-6 newPost">
            <form action="newPost.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <br>
                        <br>
                        <input type="checkbox" name="thumbnailCheck" value="1" onclick="showFileUpload()" id="thumbnailCheck"> Upload a thumbnail
                    </div>
                    <div class="col-md-4" id="fileDiv" style="display: none">
                        <label class="form-label" for="thumbnail">Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control" accept=".jpg,.jpeg,.png">

                    </div>
                    <div class="col-12">
                        <label class="form-label" for="content">Content</label>
                        <textarea name="content" minlength="20" class="form-control" required></textarea>
                    </div>
                </div>
                <button type="submit" name="submit" class="btn btn-success">Submit</button>

            </form>
        </div>
        <div class="col-12 col-sm-0 col-md-0 col-xl-3"></div>

    </div>


</main>
<?php
include "./footer.html";
?>
</body>


<script>
    const thumbnailCheck = document.getElementById('thumbnailCheck');
    const fileDiv = document.getElementById('fileDiv').style;

    function showFileUpload() {
        if (thumbnailCheck.checked){
            fileDiv.display = "block"
        } else {
            fileDiv.display = "none"
        }
    }
</script>
</html>
