<?php
    session_start(['cookie_lifetime' => 86400]);
    if (isset($_SESSION['logged-in'])) {
        session_destroy();
    }

    header('Location: index.php');
    return;
?>
