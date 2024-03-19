<?php

include '../db.php';
include 'queryParams.php';

$deviceTypeQuery = "
    SELECT
        s.device_type,
        COUNT(*) AS deviceCount
    FROM
        session s
        LEFT JOIN product p ON p.prodID = s.prodID
        WHERE p.categoryID = ? " . ($startDate && $endDate ? "AND s.timestamp BETWEEN ? AND ?" : "") . "
    GROUP BY
        s.device_type
";

            // Prepare and bind parameters for the deviceType query
            if ($startDate && $endDate) {
                $deviceTypeStmt = $conn->prepare($deviceTypeQuery);
                $deviceTypeStmt->bind_param("iss", $categoryID, $startDate, $endDate);
            } else {
                $deviceTypeStmt = $conn->prepare($deviceTypeQuery);
                $deviceTypeStmt->bind_param("i", $categoryID);
            }

            $deviceTypeStmt->execute();
            $deviceTypeResult = $deviceTypeStmt->get_result();

            // Check if the deviceType query was successful
            if ($deviceTypeResult === false) {
                die("Error in deviceType query: " . $conn->error);
            }

            // Fetch the deviceType result
            $deviceTypeData = [];
            while ($row = $deviceTypeResult->fetch_assoc()) {
                $deviceTypeData[] = $row;
            }

            // Close the deviceType statement
            $deviceTypeStmt->close();



// Convert deviceTypeData array to JSON
$deviceTypeJson = json_encode($deviceTypeData);

// Output JSON
echo $deviceTypeJson;
?>