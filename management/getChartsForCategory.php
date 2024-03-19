<?php

include '../db.php';
include 'queryParams.php';

// Query to get data for the selected category with optional date filtering
$queryMainQuestions = "
    SELECT
        pq.pqID,
        pq.pqContent AS question,
        a.answerContent AS answer,
        COUNT(DISTINCT CASE WHEN (s.timestamp BETWEEN '$startDate' AND '$endDate') THEN sa.sessionID END) AS totalSessions,
        COUNT(CASE WHEN (s.timestamp BETWEEN '$startDate' AND '$endDate') THEN sa.answerID END) AS clickCount
    FROM
        parent_question pq
    LEFT JOIN question_answer qa ON pq.pqID = qa.pqID
    LEFT JOIN answer a ON qa.answerID = a.answerID
    LEFT JOIN session_answers sa ON a.answerID = sa.answerID
    LEFT JOIN session s ON sa.sessionID = s.sessionID
    WHERE
        pq.categoryID = $categoryID
    GROUP BY
        pq.pqID, pq.pqContent, a.answerID, a.answerContent
    ORDER BY
        pq.pqID, a.answerID
";


            $resultMainQuestions = $conn->query($queryMainQuestions);

            // Check if the query was successful
            if ($resultMainQuestions === false) {
                die("Error in query: " . $conn->error);
            }



// Function to fetch data for charts
function getDataForCharts($resultMainQuestions) {
    $data = [];

    while ($row = $resultMainQuestions->fetch_assoc()) {
        $question = $row['question'];
        $answer = $row['answer'];
        $clickCount = (int)$row['clickCount'];
        $totalSessions = (int)$row['totalSessions'];

        // Add data to the array
        $data[$question][] = [
            'answer' => $answer,
            'clickCount' => $clickCount,
            'totalSessions' => $totalSessions,
        ];
    }

    return $data;
}

// Call the function to get data for charts
$chartData = getDataForCharts($resultMainQuestions);

// Convert data to JSON format
$jsonChartData = json_encode($chartData);

// Set the content type to JSON
header('Content-Type: application/json');

echo $jsonChartData;

?>
