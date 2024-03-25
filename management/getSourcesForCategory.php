<?php
include '../db.php';
include 'queryParams.php';

$sourceQuery = "
    SELECT
        s.source,
        COUNT(DISTINCT s.guestID) AS userCount,
        COUNT(*) AS sourceCount
    FROM
        session s
    INNER JOIN product p ON s.prodID = p.prodID
    WHERE
        p.categoryID = ? " . ($startDate && $endDate ? "AND s.timestamp BETWEEN ? AND ?" : "") . "
    GROUP BY
        s.source
";

            // Prepare and bind parameters for the source query
            if ($startDate && $endDate) {
                $sourceStmt = $conn->prepare($sourceQuery);
                $sourceStmt->bind_param("iss", $categoryID, $startDate, $endDate);
            } else {
                $sourceStmt = $conn->prepare($sourceQuery);
                $sourceStmt->bind_param("i", $categoryID);
            }

            $sourceStmt->execute();
            $sourceResult = $sourceStmt->get_result();

            // Check if the source query was successful
            if ($sourceResult === false) {
                die("Error in source query: " . $conn->error);
            }
            // Fetch the source result
            $sourceData = [];
            while ($row = $sourceResult->fetch_assoc()) {
                $sourceData[] = $row;
            }

            $sourceStmt->close();


// Convert sourceData array to JSON format
$sourceJson = json_encode($sourceData);

// Output JSON
echo $sourceJson;
?>