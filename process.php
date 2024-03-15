<?php
    session_start();
    if(isset($_POST['home'])){
        session_unset();
        session_destroy();

        header("Location: index.php");
        exit();
    } elseif (isset($_POST['bonus'])){
        header("Location: bonus.php");
    }