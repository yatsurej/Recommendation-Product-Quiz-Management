<?php
    $pageTitle = "Quiz";
    include 'header.php';
    include 'db.php';
    session_start();

    if (!isset($_SESSION['selectedCategory'])) {
        header('Location: index.php');
        exit();
    }

    $_SESSION['userAnswers'] = array(); 
    $selectedCategory = $_SESSION['selectedCategory'];

    $query = "SELECT pq.pqID, pq.pqContent, pq.pqMinAnswer, a.answerID, a.answerContent
            FROM parent_question pq
            LEFT JOIN question_answer qa ON qa.pqID = pq.pqID 
            LEFT JOIN answer a ON a.answerID = qa.answerID
            WHERE pq.categoryID = '$selectedCategory'
            ORDER BY pq.pqOrder";
    $result = mysqli_query($conn, $query);

    $questions = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $pqID = $row['pqID'];
        $pqContent = $row['pqContent'];
        $pqMinAnswer = $row['pqMinAnswer'];
        $answerID = $row['answerID'];
        $answerContent = $row['answerContent'];

        if (!isset($questions[$pqID])) {
            $questions[$pqID] = array(
                'content' => $pqContent,
                'answers' => array(),
                'pqMinAnswer' =>  $pqMinAnswer, 
            );
        }

        if ($answerID !== null) {
            $questions[$pqID]['answers'][] = array(
                'id' => $answerID,
                'content' => $answerContent,
            );
        }
    }
?>

<style>
    .btn-answer.selected {
        background-color: #28a745; 
        color: #fff;
    }
</style>

<div class="container w-75 text-center">
    <div class="mt-5" id="question-container">
        <?php if (!empty($questions)): ?>
            <?php $firstQuestion = reset($questions); ?>
            <h3><?php echo $firstQuestion['content']; ?></h3><br>
            <?php if (!empty($firstQuestion['answers'])): ?>
                <?php foreach ($firstQuestion['answers'] as $answer): ?>
                    <button class="btn btn-primary rounded-pill mb-2 w-100 btn-answer" data-answer-id="<?php echo $answer['id']; ?>" value="<?php echo $answer['id']; ?>"onclick="selectAnswer('<?php echo $answer['id']; ?>')">
                        <?php echo $answer['content']; ?>
                    </button><br>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No answers found for this question.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>No questions found for this category.</p>
        <?php endif; ?>
    </div>
    
    <button class="btn btn-primary" id="next-button" onclick="nextQuestion()" disabled>Next</button>
</div>

<script>
    var currentQuestionIndex = 0;
    var questions = <?php echo json_encode(array_values($questions)); ?>;
    var selectedAnswers = [];

    function selectAnswer(answerID) {
        var questionContainer = document.getElementById('question-container');
        var answerButtons = questionContainer.getElementsByClassName('btn-answer');
        var maxAnswer = questions[currentQuestionIndex]['pqMinAnswer'];

        var answerIndex = selectedAnswers.indexOf(answerID);
        
        if (answerIndex === -1) {
            if (selectedAnswers.length < maxAnswer) {
                selectedAnswers.push(answerID);
            }
        } else {
            selectedAnswers.splice(answerIndex, 1);
        }

        for (var i = 0; i < answerButtons.length; i++) {
            var currentAnswerID = answerButtons[i].getAttribute('data-answer-id');
            if (selectedAnswers.indexOf(currentAnswerID) !== -1) {
                answerButtons[i].classList.add('selected');
            } else {
                answerButtons[i].classList.remove('selected');
            }
        }

        var nextButton = document.getElementById('next-button');
        nextButton.disabled = selectedAnswers.length < maxAnswer;

        var sessionId = '<?php echo session_id(); ?>';
        var selectedAnswersData = JSON.stringify(selectedAnswers);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'store_answers.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('sessionId=' + sessionId + '&selectedAnswers=' + selectedAnswersData);
    }


    function nextQuestion() {
        if (selectedAnswers.length < questions[currentQuestionIndex]['pqMinAnswer']) {
            alert('Please select at least ' + questions[currentQuestionIndex]['pqMinAnswer'] + ' answer(s).');
            return;
        }

        currentQuestionIndex++;

        if (currentQuestionIndex < questions.length) {
            var questionContainer = document.getElementById('question-container');
            questionContainer.innerHTML = '<h3 class="question-container">' + questions[currentQuestionIndex]['content'] + '</h3>';

            if (questions[currentQuestionIndex]['answers']) {
                for (var i = 0; i < questions[currentQuestionIndex]['answers'].length; i++) {
                    var answer = questions[currentQuestionIndex]['answers'][i];
                    questionContainer.innerHTML += '<button class="btn btn-primary rounded-pill mb-2 w-100 btn-answer" data-answer-id="' + answer['id'] + '" onclick="selectAnswer(\'' + answer['id'] + '\')">' + answer['content'] + '</button><br>';
                }
            }

            selectedAnswers = [];
        } else {
            window.location.href = "result.php"; 
        }
    }
</script>
