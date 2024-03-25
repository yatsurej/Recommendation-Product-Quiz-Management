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

    if (isset($_SESSION['quizProgress']) || isset($_SESSION['siteVisit'])){
        if (!isset($_SESSION['drop_insertion_done'])) {
            $guestID = $_SESSION['guestID'];
            $device =  $_SESSION['deviceType'];
            $country = $_SESSION['country'];
            $source  = $_SESSION['referrer'];
        
            $query = "UPDATE session
                      SET status = '1'
                      WHERE guestID = '$guestID'";

            $result = mysqli_query($conn, $query);
        
            if ($result) {
                $_SESSION['drop_insertion_done'] = true;
            } 
        }
        unset($_SESSION['quizProgress']); 
        unset($_SESSION['selectedAnswers']);
        unset($_SESSION['currentQuestion']);
        unset($_SESSION['productTally']);
    }
    
    if (isset($_SESSION['visit_']))
    $query = "SELECT * FROM category WHERE categoryID = '$selectedCategory'";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        $categoryDescription    = $row['categoryDescription'];
        $categoryName           = $row['categoryName'];
        $categoryTitle          = $row['categoryTitle'];
?>

<div class="body-wrapper bg3">
    <div class="wrapper justify-center gap20">
        <div class="spacer"></div>
        <div class="header-container">
            <h2>READY FOR A</h2>
            <h1 class="quiz">QUIZ?</h1>
            <h3><?php echo $categoryTitle; ?></h3>
        </div>
        <div class="content-container">
            <h2><?php echo $categoryName; ?></h2>
            <p><?php echo $categoryDescription;?></p>
        </div>
        <button onclick="location.href='quiz.php'">CLICK TO START</button>
        <div class="spacer"></div>
    </div>
</div>

<?php
 }
?>