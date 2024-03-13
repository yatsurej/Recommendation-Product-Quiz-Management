<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/styles.css">
    <title>P&G Product Recommendation Quiz</title>
</head>

<body>
    <?php
    session_start();
    $pageTitle = "Quiz";
    // include 'header.php';
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION['selectedCategory'] = $_POST['category'];
        header("Location: category-page.php");
        exit();
    }

    ?>

    <div class="body-wrapper bg2">
        <div class="wrapper">
            <div class="spacer"></div>
            <div class="header-container">
                <h1>Solutions you're<br>looking for</h1>
            </div>
            <div class="spacer"></div>
            <div class="options-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="post">
                    <?php
                    $categoryQuery = "SELECT * FROM category";
                    $categoryResult = mysqli_query($conn, $categoryQuery);
                    
                    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                        $categoryID = $categoryRow['categoryID'];
                        $categoryName = $categoryRow['categoryName'];
                        ?>
                        <div class="buttons">
                            <button type="submit" name="category" value="<?php echo $categoryID; ?>">
                                <?php echo $categoryName; ?>
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>

</html>