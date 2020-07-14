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
                        //If logged in, show a dropdown with options. Otherwise, show "login".
                        if (isset($_SESSION['logged-in'])) {
                            echo '<div class="dropdown">'."\n";
                            echo '<a class="dropdown-toggle" href="#" data-toggle="dropdown">account</a>'."\n";
                            echo '<div class="dropdown-menu">'."\n";
                            echo '<a class="dropdown-item" href="account-info.php">account info</a>'."\n";
                            echo '<a class="dropdown-item" href="logout.php">log out</a>'."\n";
                            echo '</div></div>';
                        } else {
                            echo '<a href="login.php">login</a>';
                        }
                    ?>
                </div>
                <div class="col-auto">
                    <?php
                        if (!isset($_SESSION['logged-in'])) {
                            echo '<a href="register.php">register</a>';
                        }
                    ?>
                </div>
                <div class="col-auto">
                    <a href="index.php">home</a>
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
            <div class="row mt-3">
                <?php
                    /*
                    * Without any parameters on index.php, print out the years.
                    */
                    if (count($_GET) === 0) {
                        $sql = 'SELECT DISTINCT DATE_FORMAT(pub_date, "%Y") AS year FROM articles';
                        $stmt = $pdo->query($sql);

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="col-xs-4 col-md-2">';
                            echo '<a href="index.php?year='.htmlentities($row['year']).'">'.htmlentities($row['year']).'</a>';
                            echo '</div>';
                        }
                    }

                    /*
                    * With the 'year' parameter set, print out the months associated with the year.
                    */
                    if (isset($_GET['year']) && !isset($_GET['month'])) {
                        $sql = 'SELECT DISTINCT DATE_FORMAT(pub_date, "%m") AS month FROM articles WHERE pub_date LIKE :yr';
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(
                            array(':yr' => $_GET['year'].'%')
                        );

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="col-xs-4 col-md-2">';
                            echo '<a href="index.php?year='.$_GET['year'].'&month='.htmlentities($row['month']).'">'.htmlentities($row['month']).'</a>';
                            echo '</div>';
                        }
                    }

                    /*
                    * With the 'year' and 'month' parameters set, print out all the dates associated with them.
                    */
                    if (isset($_GET['year']) && isset($_GET['month'])) {
                        $sql = 'SELECT DISTINCT pub_date FROM articles WHERE pub_date LIKE :plc';
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(
                            array(':plc' => $_GET['year'].'-'.$_GET['month'].'%')
                        );

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="col-xs-4 col-md-2">';
                            echo '<a href="entry.php?date='.htmlentities($row['pub_date']).'">'.htmlentities($row['pub_date']).'</a>';
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>
