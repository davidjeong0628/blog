<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="css/footer.css">
        <link rel="stylesheet" href="css/index.css">
        <title>David Jeong's Blog</title>
    </head>
    <body>
        <?php require_once "navigation.php" ?>
        <!-- Main -->
        <main>
            <!-- Jumbotron -->
            <div class="jumbotron jumbotron-fluid bg-white">
                <div class="container">
                    <h1 id="jumbotron-heading">vida de david</h1>
                    <p class="lead">This is my blog where I write down my thoughts.</p>
                    <hr class="my-2">
                    <p>Click on the button below to check out my posts.</p>
                    <a href="articles.php?pg=1"><button class="btn btn-outline-dark">Read!</button></a>
                </div>
            </div>
        </main>
        <?php require_once "footer.php" ?>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>