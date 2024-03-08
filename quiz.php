<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/output.css">
    <link rel="stylesheet" href="./assets/styles.css">

    <style>
        @import url("assets/style.css");
        .custom-bg {
            background-image: url('./assets/images/bg-3.jpg');
        }

        .content {
            max-width: clamp(200px, 80vw, 400px);
            padding: 0px;
        }
        
        .wrapper-qb {
            justify-content: flex-start;
            height: 100%;
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            align-content: center;
            align-items: center;
            gap: 40px;
        }

        .answers {
            gap: 18px;
            display: flex;
            flex-direction: column;
        }
    

        .answers .choices {
            min-height: 8ch;
            padding: 10px 30px;
        }

        .choices.selected {
            background: #fff;
            color: #8E7242;
        }
        
        button {
            width: 100%;
            font-size: clamp(14px, 2vw, 16px);
            padding: 10px;  
        }
        
        
        .next {
            width: 100%;
        }
        
        .question-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
            background-color: #fff;
            border: 2px solid #8E7242;
            padding: 40px 40px 30px 40px;
            border-radius: 10px;
            color: #8E7242;
            position: relative;
            letter-spacing: 1em;
        
        }
    
        .question-box {
            position: relative;
        }

        .question-box p {
            letter-spacing: 1px;
            font-size: clamp(18px, 2vw, 20px);
            font-weight: bold;
        }

        .question-mark {
            position: absolute;
            top: -30px;
        }

        .btn-answer.selected {
        background-color: #fff; 
        color: #8E7242;
    }
    </style>
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


<div class="custom-bg">
    <div class="content" id="question-container">
        <div class="wrapper-qb" >
        <?php if (!empty($questions)): ?>
            <?php $firstQuestion = reset($questions); ?>
            <div class="question-container">
                <div class="question-mark">
                    <img src="./assets/icon-questionmark.svg">
                    
                </div>
                <div class="question-box">
                    <p><?php echo $firstQuestion['content']; ?></p>
                </div>
            </div>
            <div class="answers">
                <?php if (!empty($firstQuestion['answers'])): ?>
                    <?php foreach ($firstQuestion['answers'] as $answer): ?>
                        <button class="choices text-white border-2 rounded-full text-center btn-answer" data-answer-id="<?php echo $answer['id']; ?>" value="<?php echo $answer['id']; ?>"onclick="selectAnswer('<?php echo $answer['id']; ?>')">
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
            <div class="next">
                <button class="next-c choices text-white border-2 rounded-3xl text-center" id="next-button" onclick="nextQuestion()" disabled>NEXT</button>
            </div>
            <div class="spacer"></div>
        </div>
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

        // Create the wrapper div
        var wrapperDiv = document.createElement('div'); 
        wrapperDiv.classList.add('wrapper-qb');
        questionContainer.appendChild(wrapperDiv);

        
        // Create the question box
        var questionBox = document.createElement('div');
        questionBox.classList.add('question-container');
        questionBox.classList.add('question-box');
        questionBox.innerHTML = '<p>' + questions[currentQuestionIndex]['content'] + '</p>';
        wrapperDiv.appendChild(questionBox);


        var questionMark = document.createElement('div');
        questionMark.classList.add('question-mark');
        questionMark.innerHTML =  '<img src="./assets/icon-questionmark.svg">';
        questionBox.appendChild(questionMark);
        
        // Create the answers div
        var answersDiv = document.createElement('div');
        answersDiv.classList.add('answers');
        wrapperDiv.appendChild(answersDiv);

        if (questions[currentQuestionIndex]['answers']) {
            for (var i = 0; i < questions[currentQuestionIndex]['answers'].length; i++) {
                var answer = questions[currentQuestionIndex]['answers'][i];
                var answerButton = document.createElement('button');
                answerButton.classList.add('choices', 'text-white', 'border-2', 'rounded-3xl', 'px-auto', 'py-auto', 'text-center', 'me-2', 'mb-2', 'btn-answer');
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

        // Add the next button
        var nextButtonDiv = document.createElement('div');
        nextButtonDiv.classList.add('next');
        var nextButton = document.createElement('button');
        nextButton.classList.add('next-c', 'choices', 'text-white', 'border-2', 'rounded-3xl', 'px-auto', 'py-auto', 'text-center', 'me-2', 'mb-2');
        nextButton.setAttribute('id', 'next-button');
        nextButton.setAttribute('onclick', 'nextQuestion()');
        nextButton.setAttribute('disabled', 'true');
        nextButton.textContent = 'NEXT';
        nextButtonDiv.appendChild(nextButton);
        wrapperDiv.appendChild(nextButtonDiv);

        // Add the spacer
        var spacerDiv = document.createElement('div');
        spacerDiv.classList.add('spacer');
        wrapperDiv.appendChild(spacerDiv);

        selectedAnswers = [];
    } else {
        window.location.href = "result.php"; 
    }
}


</script>
                    
</body>
</html>