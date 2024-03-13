<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/styles.css">
</head>
<body>
<?php
    $pageTitle = "Quiz";
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


<div class="body-wrapper bg4">
    <div class="wrapper" id="question-container">
        <?php if (!empty($questions)): ?>
            <?php $firstQuestion = reset($questions); ?>
            <div class="spacer"></div>
            <div class="q-container">
                <div class="question-mark">
                    <img src="./assets/icon-questionmark.svg">
                    
                </div>
                <div class="question-box">
                    <h3><?php echo $firstQuestion['content']; ?></h3>
                </div>
            </div>
            <div class="spacer"></div>
            <div class="spacer display-none "></div>
            <div class="options-container">
                <?php if (!empty($firstQuestion['answers'])): ?>
                    <?php foreach ($firstQuestion['answers'] as $answer): ?>
                        <button class="btn-answer" data-answer-id="<?php echo $answer['id']; ?>" value="<?php echo $answer['id']; ?>"onclick="selectAnswer('<?php echo $answer['id']; ?>')">
                            <?php echo $answer['content']; ?>
                        </button>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No answers found for this question.</p>
                    <?php endif; ?>
                            <?php else: ?>
                                <p>No questions found for this category.</p>
                                <?php endif; ?>
            </div>
            <div class="spacer"></div>
            <button id="next-button" class="next" onclick="nextQuestion()" disabled>NEXT</button>
            <div class="spacer"></div>
    </div>
 </div>

 <script defer>
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
    }


    function nextQuestion() {
    if (selectedAnswers.length < questions[currentQuestionIndex]['pqMinAnswer']) {
        alert('Please select at least ' + questions[currentQuestionIndex]['pqMinAnswer'] + ' answer(s).');
        return;
    }

    var sessionId = '<?php echo session_id(); ?>';
    var selectedAnswersData = JSON.stringify(selectedAnswers);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'store_answers.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('sessionId=' + sessionId + '&selectedAnswers=' + selectedAnswersData);
    
    currentQuestionIndex++;

    if (currentQuestionIndex < questions.length) {
        var questionContainer = document.getElementById('question-container');
        
        // Clear existing content
        questionContainer.innerHTML = '';

        var quizSpacer = document.createElement('div');
        quizSpacer.classList.add('spacer');
        questionContainer.appendChild(quizSpacer);
        
        var qContainer = document.createElement('div')
        qContainer.classList.add('q-container');
        questionContainer.appendChild(qContainer);

        var questionMark = document.createElement('div');
        questionMark.classList.add('question-mark');
        questionMark.innerHTML =  '<img src="./assets/icon-questionmark.svg">';
        qContainer.appendChild(questionMark);

        var qBox = document.createElement('div');
        qBox.classList.add('question-box');
        qBox.innerHTML =  '<h3>' + questions[currentQuestionIndex]['content'] + '</h3>';
        qContainer.appendChild(qBox);

        var spacer2 = document.createElement('div')
        spacer2.classList.add('spacer');
        questionContainer.appendChild(spacer2);

        var spacerDN = document.createElement('div');
        spacerDN.classList.add('spacer', 'display-none');
        questionContainer.appendChild(spacerDN);
        
        var answersDiv = document.createElement('div');
        answersDiv.classList.add('options-container');
        questionContainer.appendChild(answersDiv);

        if (questions[currentQuestionIndex]['answers']) {
            for (var i = 0; i < questions[currentQuestionIndex]['answers'].length; i++) {
                var answer = questions[currentQuestionIndex]['answers'][i];
                var answerButton = document.createElement('button');
                answerButton.classList.add('btn-answer');
                answerButton.setAttribute('data-answer-id', answer['id']);
                answerButton.setAttribute('value', answer['id']);
                answerButton.setAttribute('onclick', 'selectAnswer(\'' + answer['id'] + '\')');
                answerButton.textContent = answer['content'];
                answersDiv.appendChild(answerButton);
            }
        } else {
            var noAnswersParagraph = document.createElement('p');
            noAnswersParagraph.textContent = 'No answers found for this question.';
            answersDiv.appendChild(noAnswersParagraph);
        }

        var spacerDiv = document.createElement('div');
        spacerDiv.classList.add('spacer');
        questionContainer.appendChild(spacerDiv);


        // Add the next button
        var nextButton = document.createElement('button');
        nextButton.classList.add('next');
        nextButton.setAttribute('id', 'next-button');
        nextButton.setAttribute('onclick', 'nextQuestion()');
        nextButton.setAttribute('disabled', 'true');
        nextButton.textContent = 'NEXT';
        questionContainer.appendChild(nextButton);

        // Add the spacer
        var spacerDiv = document.createElement('div');
        spacerDiv.classList.add('spacer');
        questionContainer.appendChild(spacerDiv);

        selectedAnswers = [];
    } else {
        window.location.href = "result.php"; 
    }
}


</script>
                    
</body>
</html>