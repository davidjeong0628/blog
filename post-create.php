<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    /*
    * Values for title input and textarea. Used when editing
    * posts.
    */
    $title = '';
    $text_entry = '';

    /*
    * Runs if 'aid' GET parameter is set, meaning that the user wants to 
    * edit the post associated with that 'aid'.
    */
    if (isset($_GET['aid'])) {

        $sql = 'SELECT title, entry_text, access FROM articles WHERE article_id = :aid';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(':aid' => $_GET['aid'])
        );

        /*
        * Runs if an article was fetched. 
        */
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /*
            * If the article is set to private and the user is not logged in or does
            * not have the right privileges, sends an error message.
            */
            if ($row['access'] === '0' && (!isset($_SESSION['logged-in'])  || $_SESSION['privilege'] === '2')) {
                $_SESSION['error'] = 'You do not have access to this article!';
                header('Location: post-create.php');
                return;
            }

            /*
            * If the user is not logged in or does not have the right privileges,
            * sends an error message.
            */
            if (!isset($_SESSION['logged-in'])  || $_SESSION['privilege'] !== '0') {
                $_SESSION['error'] = 'You do not have the privilege to edit posts!';
                header('Location: entry.php?aid=' . $_GET['aid']);
                return; 
            }
            
            $title = $row['title'];
            $text_entry = $row['entry_text'];
        }
    }

    /*
    * Handles POST data.
    */
    if (isset($_POST['title']) && isset($_POST['textarea']) && isset($_POST['radio'])) {
        /*
        * If the user is not logged in or does not have permission to submit post,
        * sends a fail message.
        */
        if (!isset($_SESSION['logged-in']) || $_SESSION['privilege'] !== '0') {
            $_SESSION['error'] = 'You do not have the permission to submit posts';
            header('Location: post-create.php');
            return;
        }

        /*
        * Validates that the title-field is not empty.
        */
        if (strlen($_POST['title']) < 1) {
            $_SESSION['error'] = 'Please enter a title';
            header('Location: post-create.php');
            return;
        }

        /*
        * Validates that the title does not exceed 50 characters.
        */
        if (strlen($_POST['title']) > 50) {
            $_SESSION['error'] = 'Title exceeds 50 characters';
            header('Location: post-create.php');
            return;
        }

        /*
        * Validates that the textarea is not empty.
        */
        if (strlen($_POST['textarea']) < 1) {
            $_SESSION['error'] = 'Textarea is empty';
            header('Location: post-create.php');
            return;
        }

        /*
        * If 'aid' GET parameter is set, updates the article associated with that id.
        */
        if (isset($_GET['aid'])) {
            $sql = 'UPDATE articles SET title = :tit, edit_date = :edd, entry_text = :et, 
                access = :ac WHERE article_id = :aid';
            $stmt = $pdo->prepare($sql);
            $query_result = $stmt->execute(
                array(':tit' => $_POST['title'], ':edd' => date('Y-m-d'), ':et' => $_POST['textarea'], ':ac' => $_POST['radio'], ':aid' => $_GET['aid'])
            );
        /*
        * If 'aid' GET parameter is not set, inserts a new article.
        */
        } else {
            $sql = 'INSERT INTO articles(title, pub_date, edit_date, entry_text, access) VALUES(:tit, :pd, :ed, :et, :ac)';
            $stmt = $pdo->prepare($sql);
            $query_result = $stmt->execute(
                array(':tit' => $_POST['title'], ':pd' => date('Y-m-d'), ':ed' => date('Y-m-d'), ':et' => $_POST['textarea'], ':ac' => $_POST['radio'])
            );
        }

        //If insertion query fails, sends an error message.
        if ($query_result === false) {
            $_SESSION['error'] = 'Submission unsuccessful';
            header('Location: post-create.php');
            return;
        }

        /*
        * Runs if all submission was successful. Sends a success message.
        */
        $_SESSION['success'] = 'Submission successful';
        header('Location: post-create.php');
        return;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>New Post</title>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display">
        <link rel="stylesheet" href="css/nav.css">
    </head>
    <body>
        <div class="container">
            <?php require_once "navigation.php" ?>
            <!-- Form -->
            <form class="mt-3" method="post" enctype="multipart/form-data">
                <!-- Server response -->
                <div class="row">
                    <div class="col-auto">
                        <?php
                            /*
                            * Prints out an error msg if there was an error.
                            */
                            if (isset($_SESSION['error'])) {
                                echo '<p class="text-danger">'.$_SESSION['error'].'</p>';
                                unset($_SESSION['error']);
                            }

                            //Prints out a success msg if submission was successful.
                            if (isset($_SESSION['success'])) {
                                echo '<p class="text-success">'.$_SESSION['success'].'</p>';
                                unset($_SESSION['success']);
                            }
                        ?>
                     </div>
                </div>
                <!-- 1st row for title input -->
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?= $title ?>">
                    </div>
                </div>
                <!-- 2nd row for textarea -->
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="textarea">Entry</label>
                        <textarea class="form-control" name="textarea" id="textarea" rows="25"><?= $text_entry ?></textarea>
                    </div>
                </div>
                <!-- 3rd row for radio -->
                <div class="form-row">
                    <div class="col-auto">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radio" id="radio1" value="1" checked>
                            <label class="form-check-label" for="radio1">Public</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radio" id="radio2" value="0">
                            <label class="form-check-label" for="radio2">Private</label>
                        </div>
                    </div>
                </div>
                <!-- Submit button and cancel button -->
                <div class="form-row mt-5 pb-4">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                    <div class="col-auto">
                        <button type="reset" class="btn btn-outline-danger">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>