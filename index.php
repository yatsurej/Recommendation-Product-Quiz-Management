<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/output-min.css">
    <link rel="stylesheet" href="./assets/styles-min.css">
    <title>P&G Product Recommendation Quiz</title>
    <style>
        .custom-bg {
            background-image: url('./assets/images/bg.jpg');
        }

        .choices {
            margin-bottom: clamp(25px, 2vw, 40px);
        }
    </style>
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

    <div class="custom-bg">
        <div class="content">
            <div class="wrapper">
                <div class="title">Solutions you're<br>looking for</div>
                <div class="selection">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="post">
                        <?php
                        $categoryQuery = "SELECT * FROM category";
                        $categoryResult = mysqli_query($conn, $categoryQuery);
                        
                        while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                            $categoryID = $categoryRow['categoryID'];
                            $categoryName = $categoryRow['categoryName'];
                            ?>
                            <div class="buttons">
                                <button type="submit" class="choices text-white border-2 rounded-3xl px-auto py-auto text-center me-2 mb-2" name="category" value="<?php echo $categoryID; ?>">
                                    <?php echo $categoryName; ?>
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                    </form>
                </div>
                <div class="spacer"></div>
            </div>
        </div>
    </div>
</body>

</html>