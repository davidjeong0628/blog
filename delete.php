<?php 
    if (!isset($_SESSION['logged-in']) || $_SESSION['privilege'] !== '0') {
        $_SESSION['error'] = 'You do not have the privilege to delete articles!';
        header('Location: ')
    }
?>