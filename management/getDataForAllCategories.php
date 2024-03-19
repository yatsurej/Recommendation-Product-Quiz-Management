<?php 
include '../db.php';
include 'queryParams.php';

// Fetch the total number of sessions and users for each category
$totalSessionsQuery = "
SELECT
    c.categoryName,
    COUNT(DISTINCT CASE WHEN (s.timestamp BETWEEN '$startDate' AND '$endDate') THEN sa.sessionID END) AS totalSessions,
    COUNT(CASE WHEN (s.timestamp BETWEEN '$startDate' AND '$endDate') THEN sa.answerID END) AS users
    FROM category c
    JOIN parent_question pq ON c.categoryID = pq.categoryID
    LEFT JOIN question_answer qa ON pq.pqID = qa.pqID
    LEFT JOIN answer a ON qa.answerID = a.answerID
    LEFT JOIN session_answers sa ON a.answerID = sa.answerID
    LEFT JOIN session s ON sa.sessionID = s.sessionID
    GROUP BY c.categoryName
";

$totalSessionsResult = $conn->query($totalSessionsQuery);

// Check if the query for total sessions was successful
if ($totalSessionsResult === false) {
    die("Error in query: " . $conn->error);
}

// Fetch the total sessions and users for each category
$totalSessionsData = array();

while ($row = $totalSessionsResult->fetch_assoc()) {
    $categoryName = $row['categoryName'];
    $totalSessions = $row['totalSessions'];
    $users = $row['users'];

    $totalSessionsData[] = [
        'Category' => $categoryName,
        'Total Sessions' => $totalSessions,
        'Users' => $users,
    ];
}

// Fetch device type details from the session table ****************************************************************
$deviceTypeQuery = "
SELECT device_type, COUNT(*) AS deviceCount
FROM session
WHERE timestamp BETWEEN '$startDate' AND '$endDate'
GROUP BY device_type
";
$deviceTypeResult = $conn->query($deviceTypeQuery);

// Check if the device type query was successful
if ($deviceTypeResult === false) {
    die("Error in device type query: " . $conn->error);
}

// Fetch device type details into an associative array
$deviceTypes = array();

while ($deviceTypeRow = $deviceTypeResult->fetch_assoc()) {
    $deviceTypes[$deviceTypeRow['device_type']] = $deviceTypeRow['deviceCount'];
}

// Fetch "Event Count"
$eventCountQuery = "
SELECT COUNT(*) AS eventCount 
FROM session 
WHERE prodID IS NOT NULL 
AND timestamp BETWEEN '$startDate' AND '$endDate'
";
$eventCountResult = $conn->query($eventCountQuery);
$eventCountRow = $eventCountResult->fetch_assoc();
$eventCount = $eventCountRow['eventCount'];

// Fetch "Total Users"
$totalUsersQuery = "
SELECT COUNT(DISTINCT guestID) AS totalUsers 
FROM session 
WHERE prodID IS NOT NULL 
AND timestamp BETWEEN '$startDate' AND '$endDate'
";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsersRow = $totalUsersResult->fetch_assoc();
$totalUsers = $totalUsersRow['totalUsers'];

// Fetch "Site Visit Count"
$siteVisitQuery = "
SELECT COUNT(*) AS siteVisit 
FROM session 
WHERE timestamp BETWEEN '$startDate' AND '$endDate'
";
$siteVisitResult = $conn->query($siteVisitQuery);
$siteVisitRow = $siteVisitResult->fetch_assoc();
$siteVisitCount = $siteVisitRow['siteVisit'];

// Fetch recommended products with product names
$recommendedProductsQuery = "
SELECT p.prodName, COUNT(*) AS recommendationCount
FROM session s
INNER JOIN product p ON s.prodID = p.prodID
WHERE s.prodID IS NOT NULL
AND s.timestamp BETWEEN '$startDate' AND '$endDate'
GROUP BY s.prodID
ORDER BY recommendationCount DESC
";

$recommendedProductsResult = $conn->query($recommendedProductsQuery);

// Check if the query for recommended products was successful
if ($recommendedProductsResult === false) {
    die("Error in recommended products query: " . $conn->error);
}

// Fetch recommended products data into an associative array
$recommendedProductsData = array();

while ($row = $recommendedProductsResult->fetch_assoc()) {
    $productName = $row['prodName'];
    $count = $row['recommendationCount'];

    $recommendedProductsData[] = [
        'Product' => $productName,
        'RecommendationCount' => $count,
    ];
}

// Create an associative array to hold all the data
$resultArray = array(
    'TotalSessions' => $totalSessionsData,
    'DeviceTypes' => $deviceTypes,
    'EventCount' => $eventCount,
    'TotalUsers' => $totalUsers,
    'SiteVisitCount' => $siteVisitCount,
    'RecommendedProducts' => $recommendedProductsData
);

// Encode the result array into JSON format
$jsonResult = json_encode($resultArray);

echo $jsonResult; 
?>