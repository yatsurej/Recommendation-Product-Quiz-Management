<?php
    $pageTitle = "Quiz";
    session_start();
    if (!isset($_SESSION['selectedCategory'])) {
        header('Location: index.php');
        exit();
    }
    include 'header.php';
    include 'db.php';
    $selectedCategory = $_SESSION['selectedCategory'];

    $query  = "SELECT pq.pqID, pq.pqContent, a.answerContent
               FROM parent_question pq
               LEFT JOIN question_answer qa ON qa.pqID = pq.pqID 
               LEFT JOIN answer a ON a.answerID = qa.answerID
               WHERE pq.categoryID = '$selectedCategory'";
    $result = mysqli_query($conn, $query);

    $questions = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $pqID = $row['pqID'];
        $pqContent = $row['pqContent'];
        $answerContent = $row['answerContent'];

        if (!isset($questions[$pqID])) {
            $questions[$pqID] = array(
                'content' => $pqContent,
                'answers' => array(),
            );
        }

        if ($answerContent !== null) {
            $questions[$pqID]['answers'][] = $answerContent;
        }
    }
?>

<div class="container w-75 text-center">
    <div id="question-container">
        <?php if (!empty($questions)): ?>
            <?php $firstQuestion = reset($questions); ?>
            <h1><?php echo $firstQuestion['content']; ?></h1>
            <?php if (!empty($firstQuestion['answers'])): ?>
                <?php foreach ($firstQuestion['answers'] as $answer): ?>
                    <button class="btn btn-primary rounded-pill mb-2 w-50"><?php echo $answer; ?></button><br>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No answers found for this question.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>No questions found for this category.</p>
        <?php endif; ?>
    </div>
    
    <button class="btn btn-primary" onclick="nextQuestion()">Next</button>
</div>

<script>
    var currentQuestionIndex = 0;
    var questions = <?php echo json_encode(array_values($questions)); ?>;

    function nextQuestion() {
        currentQuestionIndex++;

        if (currentQuestionIndex < questions.length) {
            var questionContainer = document.getElementById('question-container');
            questionContainer.innerHTML = '<h1>' + questions[currentQuestionIndex]['content'] + '</h1>';

            if (questions[currentQuestionIndex]['answers']) {
                questions[currentQuestionIndex]['answers'].forEach(function(answer) {
                    questionContainer.innerHTML += '<button class="btn btn-primary rounded-pill mb-2 w-50">' + answer + '</button><br>';
                });
            }
        } else {
            alert('End of questions');
        }
    }
</script>
