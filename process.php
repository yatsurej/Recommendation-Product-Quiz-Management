<?php
    session_start();

    if(isset($_POST['home'])){
        unset($_SESSION['selectedCategory']);
        unset($_SESSION['selectedAnswers']);
        unset($_SESSION['currentQuestion']);
        unset($_SESSION['productTally']);
        unset($_SESSION['prodID']);
        unset($_SESSION['finish_insertion_done']);
        unset($_SESSION['deviceType']);
        
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['bonus'])){
        header("Location: bonus.php");
    } elseif (isset($_POST['outbound'])){
        include 'db.php';
        $guestID    = $_SESSION['guestID'];
        $prod       = $_SESSION['prodID'];
        $lastID     = $_SESSION['last_session_id'];

        $query = "UPDATE session
                  SET outbound = '1'
                  WHERE sessionID = '$lastID'";
        $result = mysqli_query($conn, $query);
        header("Location: result.php");
    }
?>