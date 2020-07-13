<?php
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=blog', 'alexa', 'seoul0628!');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
