<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    /*
    * Handles POST data.
    */
    if (isset($_POST['title']) && isset($_POST['textarea']) && isset($_POST['radio'])) {
        /*
        * If the user is not logged in, sends a fail message.
        */
        if (!isset($_SESSION['logged-in'])) {
            $_SESSION['error'] = 'You do not have the permission to submit/edit posts';
            header('Location: post-create.php');
            return;
        }

        /*
        * If the logged-in user does not have permission to submit/edit post, sends a fail message.
        */
        if ($_SESSION['privilege'] !== '0') { // A '0' privilege is given to admins.
            $_SESSION['error'] = 'You do not have the permission to submit/edit posts';
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
        * Inserting the article into the article database.
        */
        $sql = 'INSERT INTO articles(title, pub_date, edit_date, entry_text, access) VALUES(:tit, :pd, :ed, :et, :ac)';
        $stmt = $pdo->prepare($sql);
        $query_result = $stmt->execute(
            array(':tit' => $_POST['title'], ':pd' => date('Y-m-d'), ':ed' => date('Y-m-d'), ':et' => $_POST['textarea'], ':ac' => $_POST['radio'])
        );

        //If insertion query fails, sends an error message.
        if ($query_result === false) {
            $_SESSION['error'] = 'Submission unsuccessful';
            header('Location: post-create.php');
            return;
        }

        $article_id = $pdo->lastInsertID(); //Retrieves the previously inserted article's id.

        /*
        * Validating, moving, and inserting uploaded images.
        */
        $num_files = count($_FILES['file']['name']);

        /*
        * If more than 20 files were uploaded, send an error message.
        */
        if ($num_files > 20) {
            $_SESSION['error'] = 'Too many files!';
            header('Location: post-create.php');
            return;
        }

        /*
        * If files were uploaded, validates that all are less than 5 MB.
        * If it exceeds 5 MB, sends an error message.
        */
        if ($_FILES['file']['name'][0] !== '') {
            
            for ($i = 0; $i < $num_files; $i += 1) {
                if ($_FILES['file']['size'][$i] > 5242880) {
                    $_SESSION['error'] = 'A file exceeds 5 MB!';
                    header('Location: post-create.php');
                    return;
                }
            }
        }

        /*
        * If files were uploaded, inserts them into the filesystem and database.
        */
        if ($_FILES['file']['name'][0] !== '') {
            $title_no_spaces = str_replace(' ', '-', $_POST['title']); //Replaces all whitespace in the title with a '-'.
            $img_directory = 'imgs/'.$title_no_spaces.date('Y-m-d'); //Sets up the directory name.

            mkdir($img_directory);  //Creates a directory for the images.
            $uploaddir = $img_directory.'/'; //Directory to move to.

            for ($i = 0; $i < $num_files; $i += 1) {
                $uploadfile = $uploaddir.basename($_FILES['file']['name'][$i]); //File path.

                //If the file failed to move, sends a fail message.
                if (!move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {
                    $_SESSION['error'] = 'File upload unsuccessful';
                    header('Location: post-create.php');
                    return;
                }

                //Inserts the image into the image database.
                $sql = 'INSERT INTO images(url, article_id) VALUES(:url, :aid)';
                $stmt = $pdo->prepare($sql);
                $query_result = $stmt->execute(
                    array(':url' => $uploadfile, ':aid' => $article_id)
                );

                //If the file failed to insert into the database, sends a fail message.
                if ($query_result === false) {
                    $_SESSION['error'] = 'File upload unsuccessful';
                    header('Location: post-create.php');
                    return;
                }
            }
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
                <!-- 1st row for title input -->
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                </div>
                <!-- 2nd row for textarea -->
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="textarea">Entry</label>
                        <textarea class="form-control" name="textarea" id="textarea" rows="25"></textarea>
                    </div>
                </div>
                <!-- 3rd row for files -->
                <div class="form-row">
                    <div class="form-group col-auto">
                        <label for="file">Choose images to upload</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
                        <input type="file" class="form-control-file" name="file[]" id="file" multiple accept="image/*">
                        <small id="file-help" class="form-text text-muted">Each file must be less than 5 MB<br>20 files max</small>
                    </div>
                </div>
                <!-- 4th row for radio -->
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