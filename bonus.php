<style>
    .bonus-answer-btn.selected{
        background-color: green;
    }
</style>

<?php
    $pageTitle = "Bonus Question";
    include 'header.php';
    include 'db.php';
    session_start();

    $selectedCategory = $_SESSION['selectedCategory'];
    if (!isset($_SESSION['selectedCategory']) || !isset($_SESSION['prodID'])) {
        header('Location: index.php');
        exit();
    }
    
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

<div class="container w-75 text-center">
    <form method="post" action="process_bonus.php">
        <?php if (!empty($bonusQuestions)): ?>
            <?php foreach ($bonusQuestions as $bonusQuestion): ?>
                <h3><?php echo $bonusQuestion['content']; ?></h3>
                <?php if (!empty($bonusQuestion['answers'])): ?>
                    <?php foreach ($bonusQuestion['answers'] as $answer): ?>
                        <button type="button" class="bonus-answer-btn btn btn-primary mb-2 rounded-pill w-100" data-answer-id="<?php echo $answer['id']; ?>" onclick="selectBonusAnswer(<?php echo $answer['id']; ?>)">
                            <?php echo $answer['content']; ?>
                        </button><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No answers found for this bonus question.</p>
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="hidden" name="bonusAnswer" id="selectedBonusAnswer" value="">
            <button class="btn btn-primary" type="submit">Submit</button>
        <?php else: ?>
            <p>No bonus questions found for this category.</p>
        <?php endif; ?>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    var selectedBonusAnswer = '';

    function selectBonusAnswer(answerID) {
        selectedBonusAnswer = answerID;
        $('.bonus-answer-btn').removeClass('selected');
        $('[data-answer-id="' + answerID + '"]').addClass('selected');
    }

    $(document).ready(function() {
        $('form').on('submit', function() {
            $('#selectedBonusAnswer').val(selectedBonusAnswer);
        });
    });
</script>
