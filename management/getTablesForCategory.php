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



// Function to generate HTML tables
function generateHtmlTables($resultMainQuestions) {
    $html = '';
    $prevQuestion = null;  // To track when to start a new table

    while ($row = $resultMainQuestions->fetch_assoc()) {
        if ($prevQuestion !== $row['question']) {
            // Start a new table for each unique question
            if ($prevQuestion !== null) {
                $html .= '</tbody></table>';  // Close the previous table
            }
            $html .= '<h3 class="text-base font-bold mb-3">' . $row['question'] . '</h3>'; // Adjusted font size
            $html .= '<div class="overflow-x-auto">';
            $html .= '<table class="questions min-w-full bg-white border border-gray-300 shadow-md">';
            $html .= '<thead><tr class="bg-gray-100"><th class="py-1 px-3 border-b text-sm">Answers</th><th class="py-1 px-3 border-b text-sm">Click Count</th><th class="py-1 px-3 border-b text-sm">Total Sessions</th></tr></thead>'; // Adjusted font size
            $html .= '<tbody>';
            $prevQuestion = $row['question'];
        }

        // Add row for each answer
        $html .= '<tr>';
        $html .= '<td class="py-2 px-3">' . $row['answer'] . '</td>'; // Adjusted font size
        $html .= '<td class="py-2 px-3">' . $row['clickCount'] . '</td>'; // Adjusted font size
        $html .= '<td class="py-2 px-3">' . $row['totalSessions'] . '</td>'; // Adjusted font size
        $html .= '</tr>';
    }

    // Add this line to close the last table
    if ($prevQuestion !== null) {
        $html .= '</tbody></table>';
        $html .= '</div>';
    }

    return $html;
}

// Call the function to generate HTML tables
$tablesHtml = generateHtmlTables($resultMainQuestions);

// Echo the generated HTML tables
echo $tablesHtml;
?>
