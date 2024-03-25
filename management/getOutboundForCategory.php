<?php
include '../db.php';
include 'queryParams.php';

$outboundQuery = "
    SELECT p.prodURL,
        COUNT(s.outbound) AS clickCount
    FROM session s
    INNER JOIN product p ON s.prodID = p.prodID
    WHERE p.categoryID = ? " . ($startDate && $endDate ? "AND s.timestamp BETWEEN ? AND ?" : "") . "
    AND s.outbound IS NOT NULL
    GROUP BY p.prodURL
";

            // Prepare and bind parameters for the outbound query
            if ($startDate && $endDate) {
                $outboundStmt = $conn->prepare($outboundQuery);
                $outboundStmt->bind_param("iss", $categoryID, $startDate, $endDate);
            } else {
                $outboundStmt = $conn->prepare($outboundQuery);
                $outboundStmt->bind_param("i", $categoryID);
            }

            $outboundStmt->execute();
            $outboundResult = $outboundStmt->get_result();

            // Check if the outbound query was successful
            if ($outboundResult === false) {
                die("Error in outbound query: " . $conn->error);
            }

            // Fetch the outbound result
            $outboundData = [];
            while ($row = $outboundResult->fetch_assoc()) {
                $outboundData[] = $row;
            }

            $outboundStmt->close();


// Convert outboundData array to JSON format
$outboundJson = json_encode($outboundData);

// Output JSON
echo $outboundJson;
?>