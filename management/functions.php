<?php
    require 'class/class_quiz.php';

    $classQuiz = new Quiz;
    session_start();
    if (isset($_POST['login'])){
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $result = $classQuiz->login($username, $password);

            if ($result){
                $_SESSION['user_authenticated'] = true;
                $_SESSION['username'] = $username;

                header("Location: index.php");
                exit();
            } else{
                echo "<script>alert('Invalid login details');window.location.href='login.php';</script>";
            }
        } 
    } elseif (isset($_POST['addQuestion'])) {
        $parentQuestion = $_POST['parentQuestion'];
        $numOptions     = $_POST['numOptions'];
        $numAnswer      = $_POST['numAnswer'];
        $categoryID     = $_POST['category'];
    
        $answersData = array();
        for ($i = 0; $i < $numOptions; $i++) {
            $answerContent = $_POST['answer'][$i];
            $productIDs = $_POST['answer_type'][$i];
            $answersData[$answerContent] = $productIDs;
        }

        $result = $classQuiz->addQuestion($parentQuestion, $numOptions, $numAnswer, $categoryID, $answersData);
    
        if ($result){
            header("Location: question.php");
        } else{
            echo "Failed to add question.";
        }
    } elseif (isset($_POST['addProduct'])) {
        $prodName           = $_POST['prodName'];
        $prodDescription    = $_POST['prodDescription'];
        $prodURL            = $_POST['prodURL'];
        $prodCategory       = $_POST['prodCategory'];
    
        $targetDirectory = "uploads/";
        $targetFile = $targetDirectory . basename($_FILES["prodImage"]["name"]);
        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["prodImage"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if (file_exists($targetFile)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        if ($_FILES["prodImage"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["prodImage"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["prodImage"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    
        $result = $classQuiz->addProduct($prodName, $prodDescription, $targetFile, $prodURL, $prodCategory);
    
        if ($result) {
            header("Location: product.php");
        } else {
            $result->error;
        }
    } elseif(isset($_POST['addCategory'])){
        $categoryName = $_POST['categoryName'];

        $result = $classQuiz->addCategory($categoryName);

        if($result){
            header("Location: categories.php");
        } else{
            $result->error;
        }
    }
    