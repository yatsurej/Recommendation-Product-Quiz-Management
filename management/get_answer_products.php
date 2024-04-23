<?php
include '../db.php';

if (isset($_GET['pqID']) && isset ($_GET['categoryID'])) {
    $pqID = $_GET['pqID'];
    $categoryID = $_GET['categoryID'];

    $query = "SELECT a.answerID, a.answerContent, pa.prodID 
              FROM answer a
              LEFT JOIN question_answer qa ON a.answerID = qa.answerID
              LEFT JOIN product_answer pa ON pa.answerID = a.answerID
              WHERE qa.pqID = '$pqID'";
    $result = mysqli_query($conn, $query);

    $answersWithProducts = [];
    $selectedProducts = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $answerID       = $row['answerID'];
        $answerContent  = $row['answerContent'];
        $prodID         = $row['prodID'];

        $answersWithProducts[$answerID]['answer'] = $answerContent;

        if (!isset($answersWithProducts[$answerID]['products'])) {
            $answersWithProducts[$answerID]['products'] = [];
        }
        if (!empty($prodID)) {
            $answersWithProducts[$answerID]['products'][] = $prodID;
            $selectedProducts[] = $prodID;
        }
    }

    $productsQuery = "SELECT * FROM product WHERE categoryID = $categoryID";
    $productsResult = mysqli_query($conn, $productsQuery);

    $answersHTML = '';
    $productsHTML = '';

    foreach ($answersWithProducts as $answerID => $answerData) {
        $answerContent = $answerData['answer'];
        $answersHTML .= "<input type='text' class='form-control' value='$answerContent' name='answers[]' id='answers'>";

        $productsHTML .= "<select class='form-control chosen-select answerProducts' name='answerProducts[]' multiple>";
        while ($productRow = mysqli_fetch_assoc($productsResult)) {
            $productID   = $productRow['prodID'];
            $productName = $productRow['prodName'];
            $selected = in_array($productID, $answerData['products']) ? 'selected' : '';
            $productsHTML .= "<option value='$productID' $selected>$productName</option>";
        }
        mysqli_data_seek($productsResult, 0); 
        $productsHTML .= "</select><br>";
    }

    echo $answersHTML . $productsHTML;
} else {
    echo 'Invalid request';
}
?>
