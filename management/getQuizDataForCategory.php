<?php
// Include queries file
include '../db.php';
include 'queryParams.php';
// Query to get the total number of sessions for the selected category
$totalSessionsQuery = "
    SELECT COUNT(DISTINCT s.sessionID) AS totalSessions
    FROM session s
    INNER JOIN session_answers sa ON s.sessionID = sa.sessionID
    LEFT JOIN product p ON s.prodID = p.prodID
    WHERE p.categoryID = $categoryID " . ($startDate && $endDate ? "AND s.timestamp BETWEEN '$startDate' AND '$endDate'" : "");

// Query to get the total number of users for the selected category
$totalUsersQuery = "
    SELECT COUNT(DISTINCT s.guestID) AS totalUsers
    FROM session s
    INNER JOIN session_answers sa ON s.sessionID = sa.sessionID
    LEFT JOIN product p ON s.prodID = p.prodID
    WHERE p.categoryID = $categoryID " . ($startDate && $endDate ? "AND s.timestamp BETWEEN '$startDate' AND '$endDate'" : "");

// Execute queries
$totalSessionsResult = $conn->query($totalSessionsQuery);
$totalUsersResult = $conn->query($totalUsersQuery);

            // Check if the queries were successful
            if ($totalSessionsResult === false || $totalUsersResult === false) {
                die("Error in query: " . $conn->error);
            }

// Fetch the total sessions and total users for the selected category
$totalSessionsRow = $totalSessionsResult->fetch_assoc();
$totalUsersRow = $totalUsersResult->fetch_assoc();

// Store the results in an array
$data = array(
    'totalSessions' => $totalSessionsRow['totalSessions'],
    'totalUsers' => $totalUsersRow['totalUsers']
);

// Convert the array to JSON
$jsonData = json_encode($data);

// Output the JSON
echo $jsonData;
?>