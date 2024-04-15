<?php
// Include your database connection file if not already included
include_once 'db_connection.php';

// Check if pqID is provided in the GET request
if(isset($_GET['pqID'])) {
    // Sanitize the input to prevent SQL injection
    $pqID = mysqli_real_escape_string($conn, $_GET['pqID']);

    // Query to fetch the existing answers and associated products for the specified question ID
    $query = "SELECT a.answerContent, GROUP_CONCAT(pa.prodID) AS associatedProducts
              FROM answer a
              LEFT JOIN product_answer pa ON a.answerID = pa.answerID
              WHERE a.pqID = '$pqID'
              GROUP BY a.answerID";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if any rows were returned
    if(mysqli_num_rows($result) > 0) {
        // Start building the HTML markup for the form fields
        $html = '';
        while($row = mysqli_fetch_assoc($result)) {
            $answerContent = $row['answerContent'];
            $associatedProducts = $row['associatedProducts'];

            // Append the HTML markup for each answer and its associated products
            $html .= '<div class="row mb-3">';
            $html .= '<div class="col">';
            $html .= '<div class="form-floating">';
            $html .= '<input type="text" name="answer[]" value="' . $answerContent . '" class="form-control" required>';
            $html .= '<label for="answerOption">Answer Option</label>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col">';
            $html .= '<label for="answerProduct" class="text-muted">Product/s</label>';
            $html .= '<select class="form-control chosen-select" name="answer_type[]" multiple>';
            
            // Split the associated product IDs and populate the select options
            $productIDs = explode(',', $associatedProducts);
            $productOptions = '';
            foreach($productIDs as $productID) {
                // Query to fetch product name based on product ID
                $productQuery = "SELECT prodName FROM product WHERE prodID = '$productID'";
                $productResult = mysqli_query($conn, $productQuery);
                $productRow = mysqli_fetch_assoc($productResult);
                $productName = $productRow['prodName'];

                // Append option markup for each associated product
                $productOptions .= '<option value="' . $productID . '" selected>' . $productName . '</option>';
            }
            $html .= $productOptions;
            $html .= '</select>';
            $html .= '</div>';
            $html .= '</div>';
        }

        // Send the generated HTML markup back as the response
        echo $html;
    } else {
        // If no answers found for the specified question ID, return an empty response
        echo 'No answers found.';
    }
} else {
    // If pqID is not provided in the GET request, return an error message
    echo 'Question ID (pqID) is required.';
}
?>
