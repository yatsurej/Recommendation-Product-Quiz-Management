<?php
    include '../db.php';

    $pqID  = $_GET['pqID'];

    $query = "SELECT pq.*, a.*, p.*
              FROM parent_question pq 
              LEFT JOIN category c ON c.categoryID = pq.categoryID
              LEFT JOIN question_answer qa ON qa.pqID = pq.pqID
              LEFT JOIN answer a ON a.answerID = qa.answerID
              LEFT JOIN product_answer pa ON pa.answerID = a.answerID
              LEFT JOIN product p ON p.prodID = pa.prodID
              WHERE pq.pqID = '$pqID'";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)){
        $pqID = $row['pqID'];
        $pqContent = $row['pqContent'];
        $answers = $row['answerContent'];
    }
    echo $pqContent;