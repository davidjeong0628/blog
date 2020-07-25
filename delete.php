<?php
    require_once "pdo.php";
    session_start(['cookie_lifetime' => 86400]);

    /*
    * If a user with no admin privileges tries to delete articles,
    * sends an error message.
    */
    if (!isset($_SESSION['logged-in']) || $_SESSION['privilege'] !== '0') {
        $_SESSION['error'] = 'You do not have the privilege to delete articles!';
        
        $loc_header = 'Location: entry.php?aid=' . $_GET['aid'];
        header($loc_header);
        
        return;
    }

    $sql = 'DELETE FROM articles WHERE article_id = :aid';
    $stmt = $pdo->prepare($sql);
    $query_result = $stmt->execute(
        array(':aid' => $_GET['aid'])
    );

    if ($query_result === true) {
        header('Location: articles.php?pg=1');
        return;
    } else {
        $loc_header = 'Location: entry.php?aid=' . $_GET['aid'];
        header($loc_header);
        
        return;
    }
?>