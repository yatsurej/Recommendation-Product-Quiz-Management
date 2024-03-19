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

$totalSessions = $totalSessionsRow['totalSessions'];
$totalUsers = $totalUsersRow['totalUsers'];

/// Generate HTML for the total sessions and total users in the specified style
$html = '<div id="quizCompleted" class="mx-auto text-center">';
$html .= '<h4 class="text-lg font-bold mb-2">Quiz Completed</h4>';
$html .= '<table class="totalQuiz mx-auto mb-2">';
$html .= '<tr>';
$html .= '<td class="label text-center pr-4 pb-2 border-none">Total Sessions</td>';
$html .= '<td class="label text-center pb-2 border-none">Total Users</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td class="border-none pr-4 text-center">' . $totalSessions . '</td>';
$html .= '<td class="border-none text-center">' . $totalUsers . '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';

echo $html;
?>