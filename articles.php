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
        <title>Articles</title>
    </head>
    <body>
        <?php require_once "navigation.php" ?>
        <div class="container mt-3">
            <!-- Pagination -->
            <nav aria-label="Articles pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                </ul>
            </nav>
        </div>
        <!-- Main -->
        <main>
            <div class="container">

            </div>
        </main>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>