<?php
    session_start();
    unset($_SESSION['user_authenticated']);

    header("Location: index.php");
    exit();
?>
