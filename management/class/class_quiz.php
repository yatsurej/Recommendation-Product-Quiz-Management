<?php
    require '../db.php';
    if (!class_exists('Quiz')){
        class Quiz {
            // Login
            public function login($username, $password){
                global $conn;
                $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
                $result = mysqli_query($conn, $query);

                if ($row = mysqli_fetch_assoc($result)){
                    $role = $row['role'];
                    return $role;
                } else{
                    return false;
                }
            }
            
            // Main Question CRUD
            public function addMainQuestion($parentQuestion, $numOptions, $numAnswer, $categoryID, $answersData){
                global $conn;
                
                $maxOrderQuery = "SELECT MAX(pqOrder) AS maxOrder FROM parent_question WHERE categoryID = '$categoryID'";
                $maxOrderResult = mysqli_query($conn, $maxOrderQuery);
                $maxOrderRow = mysqli_fetch_assoc($maxOrderResult);
                $nextOrder = $maxOrderRow['maxOrder'] + 1;

                $query = "INSERT INTO parent_question(pqContent, pqNumOptions, pqMaxAnswer, categoryID, pqOrder)
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
            public function updateMainQuestion($pqID, $parentQuestion, $pqOrder, $answers, $pqStatus){
                global $conn;

                $query = "UPDATE parent_question
                          SET pqContent = '$parentQuestion', pqOrder = '$pqOrder'
                          WHERE pqID = '$pqID'";
                $result = mysqli_query($conn, $query);

                if ($result){
                    $answerIDs = array();
                    $answerIDQuery = "SELECT answerID FROM question_answer WHERE pqID = '$pqID'";
                    $answerIDResult = mysqli_query($conn, $answerIDQuery);
                    while ($row = mysqli_fetch_assoc($answerIDResult)) {
                        $answerIDs[] = $row['answerID'];
                    }
            
                    foreach ($answers as $index => $answer) {
                        $answerID = $answerIDs[$index];
                        $answerContent = mysqli_real_escape_string($conn, $answer);
            
                        $updateAnswerQuery = "UPDATE answer 
                                              SET answerContent = '$answerContent' 
                                              WHERE answerID = '$answerID'";
                        mysqli_query($conn, $updateAnswerQuery);
                    }
                    return true;
                } else {
                    return false;
                }
            }
            public function updateMainAnswerProducts($answerID, $answerProducts){
                global $conn;

                mysqli_query($conn, "DELETE FROM product_answer WHERE answerID = '$answerID'"); 
                foreach ($answerProducts as $prodID) {
                    $insertProductQuery = "INSERT INTO product_answer(prodID, answerID) VALUES ('$prodID', '$answerID')";
                    mysqli_query($conn, $insertProductQuery);
                }

                return true;
            }
            public function updateMainStatus($pqID, $pqStatus){
                global $conn;

                $query = "UPDATE parent_question
                          SET isActive = '$pqStatus'
                          WHERE pqID = '$pqID'";
                $result = mysqli_query($conn, $query);

                $pqOrderQuery = "SELECT pqOrder FROM parent_question WHERE pqID = '$pqID'";
                $pqOrderResult = mysqli_query($conn, $pqOrderQuery);
                $row = mysqli_fetch_assoc($pqOrderResult);
                $pqOrder = $row['pqOrder'];

                if ($pqStatus == 0) {
                    $adjustQuery = "UPDATE parent_question
                                    SET pqOrder = pqOrder - 1
                                    WHERE pqOrder >= $pqOrder
                                    AND categoryID = (SELECT categoryID FROM parent_question WHERE pqID = '$pqID')";
                    mysqli_query($conn, $adjustQuery);
                } elseif ($pqStatus == 1) {
                    $adjustQuery = "UPDATE parent_question
                                    SET pqOrder = pqOrder + 1
                                    WHERE pqOrder >= $pqOrder
                                    AND categoryID = (SELECT categoryID FROM parent_question WHERE pqID = '$pqID')";
                    mysqli_query($conn, $adjustQuery);
                } 

                if ($result){
                    return true;
                } else{
                    return false;
                }
            }

            // Conditional Question CRUD
            public function addConditionalQuestion($mainQuestion, $mainQuestionAnswer, $conditionalQuestion, $cqNumOptions, $cqNumAnswer, $answersData){
                global $conn;
                
                $query = "INSERT INTO conditional_question(cqContent, cqNumOptions, cqMaxAnswer)
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

            // Voucher Question CRUD
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
            public function updateBonusStatus($bqID, $bqStatus){
                global $conn;

                $query = "UPDATE bonus_question
                          SET isActive = '$bqStatus'
                          WHERE bqID = '$bqID'";
                $result = mysqli_query($conn, $query);

                if ($result){
                    return true;
                } else{
                    return false;
                }
            }

            public function updateVoucherAnswerProducts($answerID, $answerProducts){
                global $conn;

                mysqli_query($conn, "DELETE FROM product_answer WHERE answerID = '$answerID'"); 
                foreach ($answerProducts as $prodID) {
                    $insertProductQuery = "INSERT INTO product_answer(prodID, answerID) VALUES ('$prodID', '$answerID')";
                    mysqli_query($conn, $insertProductQuery);
                }

                return true;
            }

            // Category CRUD
            public function addCategory($categoryName, $categoryTitle, $categoryDescription){
                global $conn;
                $query = "SELECT * FROM category WHERE categoryName = '$categoryName'";
                $result = mysqli_query($conn, $query);

                if(mysqli_num_rows($result) > 0){
                    echo "<script>alert('Category name is already taken');window.location.href='./categories.php';</script>";
                } else{
                    $categoryTitle = mysqli_real_escape_string($conn, $categoryTitle);
                    $query      = "INSERT INTO category(categoryName, categoryTitle, categoryDescription)
                                   VALUES ('$categoryName', '$categoryTitle', '$categoryDescription')";
                    $result     = mysqli_query($conn, $query);
    
                    if($result){
                        return true;
                    } else{
                        return false;
                    }
                }
            }
            public function updateCategory($categoryID, $categoryName, $categoryTitle, $categoryDescription, $categoryStatus){
                global $conn;
                $categoryTitle = mysqli_real_escape_string($conn, $categoryTitle);
                $query = "UPDATE category
                          SET categoryName = '$categoryName', categoryTitle = '$categoryTitle', categoryDescription = '$categoryDescription', isActive = '$categoryStatus' 
                          WHERE categoryID = '$categoryID'";
                $result = mysqli_query($conn, $query);

                if ($result){
                    return true;
                } else {
                    return false;
                }
            }

            // Voucher CRUD
            public function addVoucher($voucherCode, $categoryID){
                global $conn;
                $query  = "SELECT * FROM voucher WHERE voucherCode = '$voucherCode'";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0){
                    echo "<script>alert('Voucher code already exists');window.location.href='./voucher.php';</script>";
                } else {
                    $query = "INSERT INTO voucher(voucherCode, categoryID)
                              VALUES('$voucherCode', '$categoryID')";
                    $result = mysqli_query($conn, $query);

                    if ($result){
                        return true;
                    } else {
                        return false;
                    }
                }
            }
            public function updateVoucher($voucherID, $voucherCode, $categoryID){
                global $conn;
                $query = "UPDATE voucher
                          SET voucherCode = '$voucherCode', categoryID = '$categoryID'
                          WHERE voucherID = '$voucherID'";
                $result = mysqli_query($conn, $query);

                if($result){
                    return true;
                } else {
                    return false;
                }
            }

            // Product CRUD
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