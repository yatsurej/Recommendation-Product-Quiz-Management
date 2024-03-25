<?php
include '../db.php';
include 'queryParams.php';

// // Query for bonus questions
// $queryBonus = "
//     SELECT
//         bq.bqContent AS question,
//         a.answerContent AS answer,
//     COUNT(DISTINCT CASE WHEN (s.timestamp BETWEEN '$startDate' AND '$endDate') THEN sa.sessionID END) AS totalSessions,
//     COUNT(CASE WHEN (s.timestamp BETWEEN '$startDate' AND '$endDate') THEN sa.answerID END) AS clickCount
//     FROM bonus_question bq
//     LEFT JOIN question_answer qa ON bq.bqID = qa.bqID
//     LEFT JOIN answer a ON qa.answerID = a.answerID
//     LEFT JOIN session_answers sa ON a.answerID = sa.answerID
//     LEFT JOIN session s ON sa.sessionID = s.sessionID
//     WHERE bq.categoryID = $categoryID
//     GROUP BY bq.bqID, bq.bqContent, a.answerID, a.answerContent
//     ORDER BY bq.bqID, a.answerID
// ";

//             $resultBonus = $conn->query($queryBonus);

//             // Check if the query was successful
//             if ($resultBonus === false) {
//                 die("Error in query: " . $conn->error);
//             }

// function getDataForBonusCharts($resultBonus) {
//     $data = [];

//     while ($row = $resultBonus->fetch_assoc()) {
//         $question = $row['question'];
//         $answer = $row['answer'];
//         $clickCount = (int)$row['clickCount'];
//         $totalSessions = (int)$row['totalSessions'];

//         // Add data to the array
//         if (!isset($data[$question])) {
//             $data[$question] = array();
//         }
//         $data[$question][] = array(
//             'answer' => $answer,
//             'clickCount' => $clickCount,
//             'totalSessions' => $totalSessions,
//         );
//     }

//     return $data;
// }

// // Call the function to get data for charts
// $bonusData = getDataForBonusCharts($resultBonus);

// // Convert data to JSON format
// $jsonBonusData = json_encode($bonusData);

// // Set the content type to JSON
// header('Content-Type: application/json');

// // Output the JSON data
// echo $jsonBonusData;

?>
