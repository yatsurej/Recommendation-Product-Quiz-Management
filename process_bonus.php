<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bonusAnswer'])) {
        $_SESSION['bonusAnswered'] = true;
    }
    header('Location: result.php');
    exit();
}
