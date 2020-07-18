<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    //Runs when POST data is submitted
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = 'SELECT pw, privilege FROM users WHERE username = :un';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(':un' => $username)
        );

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) {
            if (password_verify($password, $row['pw'])) { //Runs if password matches
                $_SESSION['logged-in'] = true;
                $_SESSION['privilege'] = $row['privilege'];
                header('Location: index.php');
                return;
            } else {
                $_SESSION['error'] = "Incorrect username/password";
                header('Location: login.php');
                return;
            }
        } else {
            $_SESSION['error'] = "Incorrect username/password";
            header('Location: login.php');
            return;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sign In</title>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display">
        <link rel="stylesheet" href="css/nav.css">
    </head>
    <body>
        <div class="container">
            <?php require_once "navigation.php" ?>
            <form class="mt-3" method="post">
                <div class="form-row">
                    <div class="col-6">
                        <label for="username">username</label>
                        <input type="text" class="form-control" name="username" id="username">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <label for="password">password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <?php
                            //Prints out an error message if username/pw was incorrect
                            if (isset($_SESSION['error'])) {
                                echo '<p class="text-danger">'.$_SESSION['error'].'</p>';
                                unset($_SESSION['error']);
                            }
                        ?>
                    </div>
                </div>
                <div class="form-row mt-2 align-items-center">
                    <div class="col-auto">
                        <input type="submit" name="submit" value="Log In">
                    </div>
                    <div class="col-auto">
                        <a href="forgot-pw.php">Forgot Password?</a>
                    </div>
                </div>
            </form>
            </div>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>
