<?php
    $pageTitle = "Quiz";
    session_start();

    if (!isset($_SESSION['selectedCategory'])) {
        header('Location: index.php');
        exit();
    }

    include 'db.php';
    include 'header.php';

    if (isset($_SESSION['user_answers'])) {
        unset($_SESSION['user_answers']);
    }

    $selectedCategory = $_SESSION['selectedCategory'];

    $query = "SELECT pq.pqID, pq.pqContent, pq.pqMinAnswer, a.answerID, a.answerContent
            FROM parent_question pq
            LEFT JOIN question_answer qa ON qa.pqID = pq.pqID 
            LEFT JOIN answer a ON a.answerID = qa.answerID
            WHERE pq.categoryID = '$selectedCategory'
            ORDER BY pq.pqID, a.answerID"; 
    $result = mysqli_query($conn, $query);
?>

<div class="container text-center w-75">
    <form method="post" action="functions.php">
        <?php
        $currentQuestionID = null;

        while ($row = mysqli_fetch_assoc($result)) {
            $pqID           = $row['pqID'];
            $pqContent      = $row['pqContent'];
            $pqMinAnswer    = $row['pqMinAnswer'];
            $answerID       = $row['answerID'];
            $answerContent  = $row['answerContent'];

            if ($currentQuestionID !== $pqID) {
                echo '<h3>' . $pqContent . '</h3>';
                $currentQuestionID = $pqID;
            }
        ?>
            <input id="answer" type="radio" name="question_<?php echo $pqID;?>[]" value="<?php echo $answerID; ?>"><?php echo $answerContent;?>
            <br>
        <?php
        }
        ?>
        <button type="submit" class="btn btn-success" value="Submit Quiz">Submit</button>
    </form>
</div>
