<?php
include 'header.php';
include 'db.php';
session_start();

$selectedCategory = $_SESSION['selectedCategory'];

$bonusQuery = "SELECT bq.bqID, bq.bqContent, bq.categoryID, a.answerID, a.answerContent
               FROM bonus_question bq
               LEFT JOIN question_answer qa ON qa.bqID = bq.bqID
               LEFT JOIN answer a ON a.answerID = qa.answerID
               WHERE bq.categoryID = '$selectedCategory'
               ORDER BY bq.bqID";
$bonusResult = mysqli_query($conn, $bonusQuery);

$bonusQuestions = array();

while ($bonusRow = mysqli_fetch_assoc($bonusResult)) {
    $bqID = $bonusRow['bqID'];
    $bqContent = $bonusRow['bqContent'];
    $answerID = $bonusRow['answerID'];
    $answerContent = $bonusRow['answerContent'];

    if (!isset($bonusQuestions[$bqID])) {
        $bonusQuestions[$bqID] = array(
            'content' => $bqContent,
            'answers' => array(),
        );
    }

    if ($answerID !== null) {
        $bonusQuestions[$bqID]['answers'][] = array(
            'id' => $answerID,
            'content' => $answerContent,
        );
    }
}
?>

<form method="post" action="process_bonus.php">
    <?php if (!empty($bonusQuestions)): ?>
        <?php foreach ($bonusQuestions as $bonusQuestion): ?>
            <h3><?php echo $bonusQuestion['content']; ?></h3>
            <?php if (!empty($bonusQuestion['answers'])): ?>
                <?php foreach ($bonusQuestion['answers'] as $answer): ?>
                    <input type="radio" name="bonusAnswer" value="<?php echo $answer['id']; ?>">
                    <?php echo $answer['content']; ?><br>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No answers found for this bonus question.</p>
            <?php endif; ?>
        <?php endforeach; ?>
        <button type="submit">Submit</button>
    <?php else: ?>
        <p>No bonus questions found for this category.</p>
    <?php endif; ?>
</form>