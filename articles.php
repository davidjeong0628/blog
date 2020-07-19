<?php 
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    /*
    * If the page argument is not set or it is less than 1, redirect to page 1.
    */
    if (!isset($_GET['pg']) || $_GET['pg'] < 1) {
        header('Location: articles.php?pg=1');
        return;
    }

    $offset = ($_GET['pg'] - 1) * 3; //Calculates offset based on page number.

    /*
    * Runs if the user is not logged in or does not have the right privileges. 
    * Fetches 3 public access articles with offset based on the page number.
    */
    if (!isset($_SESSION['logged-in']) || $_SESSION['privilege'] === '2') {
        $sql = 'SELECT article_id, title, pub_date, edit_date, entry_text FROM articles WHERE access="1" 
            ORDER BY pub_date DESC LIMIT '. $offset . ', 3';
        $stmt = $pdo->query($sql);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /*
    * Runs if the user is logged in and has the right privileges.
    * Fetches 3 public and private access articles with offset based
    * on the page number. 
    */
    } else {
        $sql = 'SELECT article_id, title, pub_date, edit_date, entry_text FROM articles 
            ORDER BY pub_date DESC LIMIT '. $offset . ', 3';
        $stmt = $pdo->query($sql);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $prev_pg = $_GET['pg'] - 1;
    $curr_pg = $_GET['pg'];
    $next_pg = $_GET['pg'] + 1;


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="css/articles.css">
        <title>Articles</title>
    </head>
    <body>
        <?php require_once "navigation.php" ?>
        <!-- Main -->
        <main>
            <div class="container mt-5">
                <!-- Pagination -->
                <nav aria-label="Articles pagination">
                    <ul class="pagination justify-content-center">
                        <!-- Previous page -->
                        <li class="page-item">
                            <a class="page-link" href="articles.php?pg=<?= $prev_pg ?>" >Prev</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="articles.php?pg=<?= $curr_pg ?>"><?= $curr_pg ?></a>
                        </li>
                        <!-- Next page -->
                        <li class="page-item">
                            <a class="page-link" href="articles.php?pg=<?= $next_pg ?>">Next</a>
                        </li>
                    </ul>
                </nav>
                <!-- Cards -->
                <div class="row justify-content-center mt-5">
                    <?php
                        /*
                        * Prints out blog entries in cards.
                        */ 
                        for ($i = 0; $i < count($row); $i += 1) {
                            echo '<div class="col-12 col-md-6 mb-3">';
                            echo '<div class="card">';
                            echo '<div class="card-body">';
                            echo '<h2 class="card-title">'. $row[$i]['title'] .'</h2>';
                            echo '<h3 class="card-subtitle mb-2 text-muted"> Published '. $row[$i]['pub_date'] . '</h3>';
                            echo '<p class="card-text">'. substr($row[$i]['entry_text'], 0, 200) .'...</p>';
                            echo '<a class="card-link" href="entry.php?aid=' . $row[$i]['article_id'] . '">Read more</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
        </main>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>