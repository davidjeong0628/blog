<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    //Runs when POST data is submitted
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = 'SELECT user_id, pw, privilege FROM users WHERE username = :un';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(':un' => $username)
        );

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        /*
        * If a row was fetched, meaning that the username exists in the database,
        * verifies the password.
        */
        if ($row !== false) {
            if (password_verify($password, $row['pw'])) { //Runs if password matches
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['logged-in'] = true;
                $_SESSION['privilege'] = $row['privilege'];

                header('Location: index.php');
                return;
            } else { //Runs if password does not match
                $_SESSION['error'] = "Incorrect username/password";

                header('Location: login.php');
                return;
            }
        /*
        * If a row was not fetched, meaning that the username does not exist in the database,
        * sends an error message.
        */
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
                <!-- Username -->
                <div class="form-row justify-content-center">
                    <div class="col-12 col-md-6">
                        <label for="username">username</label>
                        <input type="text" class="form-control" name="username" id="username">
                    </div>
                </div>
                <!-- Password -->
                <div class="form-row justify-content-center">
                    <div class="col-12 col-md-6">
                        <label for="password">password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>
                <!-- Possible error message -->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <?php
                            //Prints out an error message if username/pw was incorrect
                            if (isset($_SESSION['error'])) {
                                echo '<p class="text-danger">'.$_SESSION['error'].'</p>';
                                unset($_SESSION['error']);
                            }
                        ?>
                    </div>
                </div>
                <!-- Sign in button -->
                <div class="form-row mt-2 align-items-center justify-content-center">
                    <div class="col-auto">
                        <input type="submit" name="submit" value="Log In">
                    </div>
                </div>
            </form>
            </div>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>
