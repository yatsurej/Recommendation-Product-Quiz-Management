<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/output-min.css">
    <link rel="stylesheet" href="./assets/styles.css">
    <title>Categories</title>
    <style>
        .content{
            justify-content: center;
        }

        .custom-bg {
            background-image: url('./assets/images/bg-2.jpg');
        }

        .spacer {
            height:clamp(25px, 2.5vh, 40px);
        }
        
        p {
            color: #8E7242;
            font-size: clamp(14px, 2vw, 18px);
            font-weight: normal;
        }

    </style>
</head>

<body>
    <?php
        $pageTitle = "Quiz";
        session_start();
        if (!isset($_SESSION['selectedCategory'])) {
            header('Location: index.php');
            exit();
        }
        // include 'header.php';
        include 'db.php';
        $selectedCategory = $_SESSION['selectedCategory'];

        $query = "SELECT * FROM category WHERE categoryID = '$selectedCategory'";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result)){
            $categoryDescription    = $row['categoryDescription'];
            $categoryName           = $row['categoryName'];
    ?>
    <div class="custom-bg">
        <div class="content">
            <div class="wrapper">
                <div class="spacer"></div>
                <div class="title">
                    <h3>READY FOR A</h3>
                    <h1>QUIZ?</h1>
                    <h3><?php echo $categoryName; ?></h3>
                    <div class="description">
                        <p><?php echo $categoryDescription;?></p>
                    </div>
                </div>
                <button onclick="location.href='quiz.php'" class="choices text-white border-2 rounded-3xl px-auto py-auto text-center me-2 mb-2">CLICK TO START</button>
                <div class="spacer"></div>
            </div>
        </div>
    </div>

    <?php
    }
    ?>
</body>
</html>