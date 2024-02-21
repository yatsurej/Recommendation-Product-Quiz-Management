<?php
    require '../db.php';
    if (!class_exists('Quiz')){
        class Quiz {
            public function login($username, $password){
                global $conn;
                $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
                $result = mysqli_query($conn, $query);

                if ($result){
                    return true;
                } else{
                    return false;
                }
            }
            
            public function addQuestion($parentQuestion, $numOptions, $numAnswer, $categoryID, $answersData){
                global $conn;
            
                $query = "INSERT INTO parent_question(pqContent, pqNumOptions, pqMinAnswer, categoryID)
                        VALUES ('$parentQuestion', '$numOptions', '$numAnswer', '$categoryID')";
                $result = mysqli_query($conn, $query);
            
                if ($result) {
                    $parentQuestionID = mysqli_insert_id($conn);
            
                    foreach ($answersData as $answerContent => $productIDs) {
                        $answerInsertQuery  = "INSERT INTO answer(answerContent) VALUES ('$answerContent')";
                        $answerResult = mysqli_query($conn, $answerInsertQuery);
            
                        if ($answerResult) {
                            $answerID = mysqli_insert_id($conn);
            
                            foreach ($productIDs as $prodID) {
                                $productAnswerQuery = "INSERT INTO product_answer(prodID, answerID) VALUES ('$prodID', '$answerID')";
                                mysqli_query($conn, $productAnswerQuery);
                            }
            
                            $questionAnswerQuery = "INSERT INTO question_answer(pqID, answerID) VALUES ('$parentQuestionID', '$answerID')";
                            mysqli_query($conn, $questionAnswerQuery);
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            }

            public function addProduct($prodName, $prodDescription, $prodImage, $prodURL, $categoryID){
                global $conn;
                $query  = "INSERT INTO product(prodName, prodDescription, prodImage, prodURL, categoryID)
                                  VALUES('$prodName', '$prodDescription', '$prodImage', '$prodURL', '$categoryID')";
                $result = mysqli_query($conn, $query);
            
                if ($result){
                    return true;
                } else {
                    return false;
                }
            }
        
            public function addCategory($categoryName){
                global $conn;
                $query      = "INSERT INTO category(categoryName)
                               VALUES ('$categoryName')";
                $result     = mysqli_query($conn, $query);

                if($result){
                    return true;
                } else{
                    return false;
                }
            }
        }
    }