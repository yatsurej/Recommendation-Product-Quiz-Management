<?php
    require '../db.php';
    if (!class_exists('Quiz')){
        class Quiz {
            public function login($username, $password){
                global $conn;
                $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
                $result = mysqli_query($conn, $query);

                if (mysqli_fetch_assoc($result)){
                    return true;
                } else{
                    return false;
                }
            }
            
            public function addMainQuestion($parentQuestion, $numOptions, $numAnswer, $categoryID, $answersData){
                global $conn;
                
                $maxOrderQuery = "SELECT MAX(pqOrder) AS maxOrder FROM parent_question WHERE categoryID = '$categoryID'";
                $maxOrderResult = mysqli_query($conn, $maxOrderQuery);
                $maxOrderRow = mysqli_fetch_assoc($maxOrderResult);
                $nextOrder = $maxOrderRow['maxOrder'] + 1;

                $query = "INSERT INTO parent_question(pqContent, pqNumOptions, pqMinAnswer, categoryID, pqOrder)
                        VALUES ('$parentQuestion', '$numOptions', '$numAnswer', '$categoryID', '$nextOrder')";
                $result = mysqli_query($conn, $query);
            
                if ($result) {
                    $parentQuestionID = mysqli_insert_id($conn);
            
                    foreach ($answersData as $answerContent => $productIDs) {
                        $answerContent = mysqli_real_escape_string($conn, $answerContent);
                        
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

            public function addConditionalQuestion($mainQuestion, $mainQuestionAnswer, $conditionalQuestion, $cqNumOptions, $cqNumAnswer, $answersData){
                global $conn;
                
                $query = "INSERT INTO conditional_question(cqContent, cqNumOptions, cqMinAnswer)
                        VALUES ('$conditionalQuestion', '$cqNumOptions', '$cqNumAnswer')";
                $result = mysqli_query($conn, $query);
            
                if ($result) {
                    $conditionalQuestionID = mysqli_insert_id($conn);
                    
                    $insertAnswerTriggerQuery = "INSERT INTO trigger_condition(answerID, cqID) VALUES('$mainQuestionAnswer', '$conditionalQuestionID')";
                    mysqli_query($conn,$insertAnswerTriggerQuery);
                    foreach ($answersData as $answerContent => $productIDs) {
                        $answerContent = mysqli_real_escape_string($conn, $answerContent);
                        
                        $answerInsertQuery  = "INSERT INTO answer(answerContent) VALUES ('$answerContent')";
                        $answerResult = mysqli_query($conn, $answerInsertQuery);
                        

                        if ($answerResult) {
                            $answerID = mysqli_insert_id($conn);
            
                            foreach ($productIDs as $prodID) {
                                $productAnswerQuery = "INSERT INTO product_answer(prodID, answerID) VALUES ('$prodID', '$answerID')";
                                mysqli_query($conn, $productAnswerQuery);
                            }
            
                            $questionAnswerQuery = "INSERT INTO question_answer(cqID, answerID) VALUES ('$conditionalQuestionID', '$answerID')";
                            mysqli_query($conn, $questionAnswerQuery);

                        }
                    }
                    return true;
                } else {
                    return false;
                }
            }

            public function addVoucherQuestion($voucherQuestion, $numOptions, $numAnswer, $categoryID, $answersData){
                global $conn;
                
                $query = "INSERT INTO bonus_question(bqContent, bqNumOptions, bqMaxAnswer, categoryID)
                        VALUES ('$voucherQuestion', '$numOptions', '$numAnswer', '$categoryID')";
                $result = mysqli_query($conn, $query);
            
                if ($result) {
                    $voucherQuestionID = mysqli_insert_id($conn);
            
                    foreach ($answersData as $answerContent => $productIDs) {
                        $answerContent = mysqli_real_escape_string($conn, $answerContent);
                        
                        $answerInsertQuery  = "INSERT INTO answer(answerContent) VALUES ('$answerContent')";
                        $answerResult = mysqli_query($conn, $answerInsertQuery);
            
                        if ($answerResult) {
                            $answerID = mysqli_insert_id($conn);
            
                            foreach ($productIDs as $prodID) {
                                $productAnswerQuery = "INSERT INTO product_answer(prodID, answerID) VALUES ('$prodID', '$answerID')";
                                mysqli_query($conn, $productAnswerQuery);
                            }
            
                            $questionAnswerQuery = "INSERT INTO question_answer(bqID, answerID) VALUES ('$voucherQuestionID', '$answerID')";
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
                $prodDescription = mysqli_real_escape_string($conn,$prodDescription);
                
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
            
            public function updateProductWithImage($prodID, $prodName, $prodDescription, $targetFile, $prodURL){
                global $conn;
                $query  = "UPDATE product
                           SET prodName = '$prodName', prodDescription = '$prodDescription', prodImage = '$targetFile', prodURL = '$prodURL'
                           WHERE prodID = '$prodID'";
                $result = mysqli_query($conn, $query);
            
                if ($result){
                    return true;
                } else {
                    return false;
                }
            }
            
            public function updateProductWithoutImage($prodID, $prodName, $prodDescription, $prodURL){
                global $conn;
                $query  = "UPDATE product
                           SET prodName = '$prodName', prodDescription = '$prodDescription', prodURL = '$prodURL'
                           WHERE prodID = '$prodID'";
                $result = mysqli_query($conn, $query);
            
                if ($result){
                    return true;
                } else {
                    return false;
                }
            }
        }
    }