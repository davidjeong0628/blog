<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    //If the date parameter is not set, send an error message.
    if (!isset($_GET['date'])) {
        $_SESSION['error'] = 'A date has not been selected!';
    //Otherwise, run a query for the article associated with 'date'.
    } else {
        $sql = 'SELECT * FROM articles WHERE pub_date = :pd';
        $stmt = $pdo->prepare($sql);
        $query_result = $stmt->execute(
            array(':pd' => $_GET['date'])
        );

        //If query failed, meaning that there is no post associated with the date, send an error message.
        if ($query_result === false) {
            $_SESSION['error'] = 'Failed to retrieve the entry associated with the date';
        //If query succeeds, fetch article data.
        } else {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $article_id = $row['article_id'];
            $date = DateTime::createFromFormat('Y-m-d', $row['pub_date']); //Creates a DateTime object of the returned 'pub_date'.
            $text = explode("\n", $row['entry_text']);
            $access = $row['access'];

            //If the retrieved post is private and the user is not logged in, send an error message.
            if ($access === '0' && !isset($_SESSION['logged-in'])) {
                $_SESSION['error'] = 'You do not have the permission to view this entry.';
            //If the retrieved post is private and the user does not have the right privilege, send an error message.
            } else if ($access === '0' && $_SESSION['privilege'] === '2') {
                $_SESSION['error'] = 'You do not have the permission to view this entry.';
            //Retrieve images associated with the post.
            } else {
                $sql = 'SELECT url FROM images JOIN articles ON images.article_id=articles.article_id WHERE images.article_id=:aid';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(
                    array(':aid' => $article_id)
                );
                $images = $stmt->fetchAll(PDO::FETCH_NUM); //An array with the urls of the images associated with the post.
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php  ?></title>
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
            <!-- Prints error message OR the date of the entry -->
            <div class="row mt-md-5 justify-content-center">
                <div class="col-md-8">
                    <?php
                        //Print an error message if exists.
                        if (isset($_SESSION['error'])) {
                            echo '<p class="text-danger">'.$_SESSION['error'].'</p>';
                            unset($_SESSION['error']);
                        } else {
                            echo '<h1>'.$date->format('j F Y (D)').'</h1>';
                        }
                    ?>
                </div>
            </div>
            <!-- Text associated with the entry -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php
                        //Outputs the text entry if there are no errors.
                        if (!isset($_SESSION['error'])) {
                            for ($i = 0; $i < count($text); $i += 1) {
                                //TODO: No <p></p> allowed! Skip empty lines!
                                if (strcmp(" ", $text[$i]) === 0) {
                                    continue;
                                }

                                echo '<p>'.htmlentities($text[$i]).'</p>';
                            }
                        }
                    ?>
                </div>
            </div>
            <!-- Images -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php
                        //Outputs images if there are no errors.
                        if (!isset($_SESSION['error'])) {
                            for ($i = 0; $i < count($images); $i += 1) {
                                echo '<img src="'.$images[$i][0].'" class="img-fluid mb-4">';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>
