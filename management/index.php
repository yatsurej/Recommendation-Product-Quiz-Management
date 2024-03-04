<?php
    session_start();

    if (!isset($_SESSION['user_authenticated'])) {
        header('Location: login.php');
        exit();
    }

    include '../db.php';
    $username   = $_SESSION['username'];
    $userQuery  = "SELECT * FROM user WHERE username = '$username'";
    $userResult = mysqli_query($conn, $userQuery);

    while($userRow = mysqli_fetch_assoc($userResult)){
        $userID        = $userRow['userID'];
        $userFirstName = $userRow['firstName'];
        $userLastName  = $userRow['lastName'];    
    }
    $pageTitle = "Quiz Management";
    include '../header.php';
    include 'navbar.php';
?>

<div class="container text-center mt-5">
    <p class="fs-3">Welcome, 
        <?php 
            echo $userFirstName . ' ' . $userLastName;
        ?>
    </p>
</div>
