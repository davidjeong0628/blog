<?php 
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    /*
    * If the user is not logged in, send an error message.
    */
    if (!isset($_SESSION['logged-in'])) {
        $_SESSION['error'] = 'Please sign in to view this page';
    /*
    * If the user is logged in, fetch user data from the database.
    */
    } else {
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT email, username, privilege FROM users WHERE user_id = $user_id";
        $stmt = $pdo->query($sql);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $email = $row['email'];
        $username = $row['username'];
        $privilege = $row['privilege'];

        if ($privilege === '0') {
            $privilege = 'Administrator';
        } else if ($privilege === '1') {
            $privilege = 'View all posts. Unable to create, update, or delete posts.';
        } else {
            $privilege = 'View only public posts. Unable to create, update, or delete posts.';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Account Information</title>
        <?php require_once "bootstrap/bootstrap-css.php" ?>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display">
        <link rel="stylesheet" href="css/nav.css">
    </head>
    <body>
        <div class="container mt-4">
            <?php 
                require_once "navigation.php"; 
                
                //Prints out an error message if necessary.
                if (isset($_SESSION['error'])) {
                    echo '<p class="text-danger">'.$_SESSION['error'].'</p>';
                    unset($_SESSION['error']);
                }
            ?>
            <div class="row mb-2">
                <div class="col">
                    <h2 class="account-info">Email</h2>
                    <p><?= $email ?></p>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <h2 class="account-info">Username</h2>
                    <p><?= $username ?></p>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <h2 class="account-info">Privilege</h2>
                    <p><?= $privilege ?></p>
                </div>
            </div>
        </div>
        <?php require_once "bootstrap/bootstrap-js.php" ?>
    </body>
</html>