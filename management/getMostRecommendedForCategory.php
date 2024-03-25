<?php

include '../db.php';
include 'queryParams.php';

$mostRecommendedProductQuery = "
    SELECT p.categoryID, p.prodName,
    COUNT(*) AS recommendationCount
    FROM session s
    INNER JOIN product p ON s.prodID = p.prodID
    WHERE p.categoryID = ? " . ($startDate && $endDate ? "AND s.timestamp BETWEEN ? AND ?" : "") ."
    GROUP BY p.categoryID, p.prodName
    ORDER BY p.categoryID,
    recommendationCount DESC;
    ";

    // Prepare and bind parameters for the mostRecommendedProduct query
    if ($startDate && $endDate) {
        $mostRecommendedProductStmt = $conn->prepare($mostRecommendedProductQuery);
        $mostRecommendedProductStmt->bind_param("iss", $categoryID, $startDate, $endDate);
    } else {
        $mostRecommendedProductStmt = $conn->prepare($mostRecommendedProductQuery);
        $mostRecommendedProductStmt->bind_param("i", $categoryID);
    }

    $mostRecommendedProductStmt->execute();
    $mostRecommendedProductResult = $mostRecommendedProductStmt->get_result();

    // Check if the mostRecommendedProduct query was successful
    if ($mostRecommendedProductResult === false) {
        die("Error in mostRecommendedProduct query: " . $conn->error);
    }

    // Fetch the mostRecommendedProduct result
    $mostRecommendedProductData = [];
    while ($row = $mostRecommendedProductResult->fetch_assoc()) {
        $mostRecommendedProductData[] = $row;
    }

    // Close the mostRecommendedProduct statement
    $mostRecommendedProductStmt->close();

// Convert the data to JSON
$mostRecommendedProductJson = json_encode($mostRecommendedProductData);

// Output JSON
echo $mostRecommendedProductJson;
?>