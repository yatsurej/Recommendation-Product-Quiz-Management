<?php
    session_start();
    $pageTitle = "Result";

    include 'header.php';
    include 'db.php'; 

    if (isset($_SESSION['user_answers'])) {
        $userAnswers = $_SESSION['user_answers'];

        $selectedAnswerIDs = [];

        foreach ($userAnswers as $selectedAnswers) {
            $selectedAnswerIDs = array_merge($selectedAnswerIDs, $selectedAnswers);
        }

        $selectedAnswerIDs = array_unique($selectedAnswerIDs);

        foreach ($selectedAnswerIDs as $selectedAnswerID) {
            $query = "SELECT pa.paID, p.prodName, p.prodDescription, p.prodImage, p.prodURL
                    FROM product_answer pa
                    JOIN product p ON pa.prodID = p.prodID
                    WHERE pa.answerID = '$selectedAnswerID'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $prodName   = $row['prodName'];
                    $prodImage  = $row['prodImage'];
                    $prodURL    = $row['prodURL'];

            ?>  
                <div class="container text-center w-75">
                    <div class="card">
                        <div class="card-body">
                            <h3><?php echo $prodName;?></h3>
                            <a href="<?php echo $prodURL;?>">
                                <img src="management/<?php echo $prodImage;?>" style="width: 50%;"alt="Product Image">
                            </a>
                        </div>
                    </div>
                </div>
            <?php
                }
            } 
        }
    }
    var_dump( $_SESSION['user_answers']);
?>
