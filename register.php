<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])
        && isset($_POST['con-password']) && isset($_POST['submit'])) {
            //Validates the uniqueness of the email.
            $sql = 'SELECT email FROM users WHERE email = :em';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                array(':em' => $_POST['email'])
            );
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false) {
                $_SESSION['error'] = 'Email already used';
                header('Location: register.php');
                return;
            }

            //Validates the uniqueness of the username.
            $sql = 'SELECT username FROM users WHERE username = :un';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(
                array(':un' => $_POST['username'])
            );
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false) {
                $_SESSION['error'] = 'Username already exists';
                header('Location: register.php');
                return;
            }

            //Validates the chars of the username.
            if (preg_match('/[^a-zA-Z0-9]/', $_POST['username'])) {
                $_SESSION['error'] = 'Invalid characters used for username';
                header('Location: register.php');
                return;
            }

            //Validates the length of the username.
            if (strlen($_POST['username']) < 1 || strlen($_POST['username']) > 11) {
                $_SESSION['error'] = 'Invalid length for username';
                header('Location: register.php');
                return;
            }

            //Validates the chars of the password.
            if (preg_match('/[^ -~]/', $_POST['password'])) {
                $_SESSION['error'] = 'Invalid characters used for password';
                header('Location: register.php');
                return;
            }

            //Validates the length of the password.
            if (strlen($_POST['password']) < 10 || strlen($_POST['password']) > 26) {
                $_SESSION['error'] = 'Invalid length for password';
                header('Location: register.php');
                return;
            }

            //Checks that the password is equal to the confirmed password.
            if (strcmp($_POST['password'], $_POST['con-password']) !== 0) {
                $_SESSION['error'] = 'Passwords did not match';
                header('Location: register.php');
                return;
            }

            //Insert registration data into the database.
            $hashed_pw = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $sql = 'INSERT INTO users(email, username, pw, privilege) VALUES(:em, :un, :pw, :pri)';
            $stmt = $pdo->prepare($sql);
            $query_result = $stmt->execute(
                array(':em' => $_POST['email'], ':un' => $_POST['username'], ':pw' => $hashed_pw,
                ':pri' => '2') //The default privilege is '2'. The user can view only public entries.
            );
            if ($query_result === true) {
                $_SESSION['success'] = 'Registration successful';
                header('Location: register.php');
                return;
            } else {
                $_SESSION['error'] = 'Registration unsuccessful';
                header('Location: register.php');
                return;
            }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Register</title>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
    </head>
    <body>
        <div class="container">
            <div class="row mt-1">
                <a class="col-auto" href="index.php">home</a>
            </div>
            <form class="mt-3" method="post">
                <div class="form-row">
                    <div class="col-6">
                        <label for="email">email</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <label for="username">username</label>
                        <input type="text" class="form-control" name="username" id="username" aria-describedby="un-help">
                        <small id="un-help" class="form-text text-muted">a-z, A-Z, 0-9, no spaces<br>11 chars max</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <label for="password">password</label>
                        <input type="password" class="form-control" name="password" id="password" aria-describedby="pw-help">
                        <small id="pw-help" class="form-text text-muted">a-z, A-Z, 0-9, special characters<br>10-26 chars</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <label for="confirm-password">confirm password</label>
                        <input type="password" class="form-control" name="con-password" id="confirm-password">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <?php
                            //Prints out an error message if registration was unsuccessful.
                            if (isset($_SESSION['error'])) {
                                echo '<p class="text-danger">'.$_SESSION['error'].'</p>';
                                unset($_SESSION['error']);
                            }

                            //Prints out a success message if registration was successful.
                            if (isset($_SESSION['success'])) {
                                echo '<p class="text-success">'.$_SESSION['success'].'</p>';
                                unset($_SESSION['success']);
                            }
                        ?>
                    </div>
                </div>
                <div class="form-row mt-2 align-items-center">
                    <div class="col-auto">
                        <input type="submit" name="submit" value="Register">
                    </div>
                    <div class="col-auto">
                        <a href="register.php">cancel</a>
                    </div>
                </div>
            </form>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>
