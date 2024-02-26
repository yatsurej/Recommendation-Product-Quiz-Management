<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionId = $_POST['sessionId'];
    $selectedAnswersData = $_POST['selectedAnswers'];

    if (session_id() === $sessionId) {
        include 'db.php'; 

        $selectedAnswers = json_decode($selectedAnswersData, true);

        $productTally = isset($_SESSION['productTally']) ? $_SESSION['productTally'] : array();

        foreach ($selectedAnswers as $answerID) {
            $productQuery = "SELECT p.prodID, p.prodName
                            FROM product p
                            INNER JOIN product_answer pa ON p.prodID = pa.prodID
                            WHERE pa.answerID = '$answerID'";
            
            $productResult = mysqli_query($conn, $productQuery);

            while ($productRow = mysqli_fetch_assoc($productResult)) {
                $prodID = $productRow['prodID'];
                $prodName = $productRow['prodName'];

                if (!isset($productTally[$prodID])) {
                    $productTally[$prodID] = array(
                        'id' => $prodID,
                        'name' => $prodName,
                        'tally' => 1,
                    );
                } else {
                    $productTally[$prodID]['tally']++;
                }
            }
        }

        $_SESSION['productTally'] = $productTally;

        mysqli_close($conn);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid session ID']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>