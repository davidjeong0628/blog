<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    //If the 'aid' (article id) parameter is not set, send an error message.
    if (!isset($_GET['aid'])) {
        $_SESSION['error'] = 'No article has been selected!';
    //Otherwise, run a query for the article associated with the 'aid'.
    } else {
        $sql = 'SELECT * FROM articles WHERE article_id = :aid';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(':aid' => intval($_GET['aid']))
        );

        //If a row is not returned, meaning that there is no post associated with the 'aid', send an error message.
        if (!($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            $_SESSION['error'] = 'Failed to retrieve the entry associated with the article ID';
        //If a row is returned, fetch article data.
        } else {
            $title = $row['title'];
            $pub_date = DateTime::createFromFormat('Y-m-d', $row['pub_date']); //Creates a DateTime object of the returned 'pub_date'.
            $edit_date = DateTime::createFromFormat('Y-m-d', $row['edit_date']); //Creates a DateTime object of the returned 'edit_date'.
            $text = explode("\n", $row['entry_text']); 
            $access = $row['access'];

            //If the retrieved post is private and the user is not logged in, send an error message.
            if ($access === '0' && !isset($_SESSION['logged-in'])) {
                $_SESSION['error'] = 'You do not have the permission to view this entry.';
            //If the retrieved post is private and the user does not have the right privilege, send an error message.
            } else if ($access === '0' && $_SESSION['privilege'] === '2') {
                $_SESSION['error'] = 'You do not have the permission to view this entry.';
            } 
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $title ?></title>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="css/entry.css">
    </head>
    <body>
        <?php require_once "navigation.php" ?>
        <div class="container mt-3" id="entry-container">
            <!-- Back, edit, delete links -->
            <div class="row mb-4">
                <div class="col mr-auto">
                    <a href="articles.php?pg=1">Back</a>
                </div>
                <div class="col-auto">
                    <a href="post-create.php?aid=<?= $_GET['aid'] ?>">Edit</a>
                </div>
                <div class="col-auto">
                    <a href="delete.php?aid=<?= $row['article_id'] ?>">Delete</a>
                </div>
            </div>
            <?php
                //Prints an error message if $_SESSION['error'] is set. 
                if (isset($_SESSION['error'])) {
                    echo '<p class="text-danger">'.$_SESSION['error'].'</p>';
                    unset($_SESSION['error']);
                    return;
                }
            ?>
            <!-- Article title -->
            <div class="row">
                <div class="col">
                    <h2><?= $title ?></h2>
                </div>
            </div>
            <!-- Article published and last edited dates -->
            <div class="row">
                <div class="col">
                   <p class="text-muted">
                        <?= 'Published ' . $pub_date->format('j F Y') . 
                            '<br>' . 'Last edited ' . $edit_date->format('j F Y') ?>
                   </p>
                </div>
            </div>
            <!-- Article entry -->
            <div class="row mt-2">
                <?php 
                    for ($i = 0; $i < count($text); $i += 1) {
                        /*
                        * If the first character of the 'text' array is not a carriage return,
                        * print text.
                        */
                        if (ord($text[$i]) !== 13) {
                            echo '<div class="col-12">';
                            echo '<p>' . $text[$i] . '</p>';
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>
