<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>David Jeong's Blog</title>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
    </head>
    <body>
        <div class="container">
            <div class="row mt-1">
                <div class="col-auto">
                    <?php
                        if ($_SESSION['logged-in'] === true) {

                        } else {
                            echo '<a href="login.php">login</a>';
                        }
                    ?>
                </div>
                <div class="col-auto">
                    <?php
                        if ($_SESSION['logged-in'] === true) {

                        } else {
                            echo '<a href="register.php">register</a>';
                        }
                    ?>
                </div>
                <div class="col-auto">
                    <a href="search.php">search</a>
                </div>
                <div class="col-auto">
                    <a href="post-create-edit.php">new/edit post</a>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <h1>David Jeong's Blog</h1>
                </div>
            </div>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>
