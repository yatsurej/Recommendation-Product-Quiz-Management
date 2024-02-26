<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $questionID = substr($key, 9);

            $_SESSION['user_answers'][$questionID] = is_array($value) ? $value : [$value];
        }
    }

    header('Location: result.php');
    exit();
}
?>
