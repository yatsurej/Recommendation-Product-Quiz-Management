<?php
    session_start();
    $pageTitle = "Quiz";
    include 'header.php';
    include 'db.php';

    // Device Type
    $deviceType = "";
    if (isset($_POST['deviceType'])) {
        $deviceType = $_POST['deviceType'];
        $_SESSION['deviceType'] = $deviceType;
    }

    // Guest ID
    if (!isset($_SESSION['guestID'])) {
        $guestID = rand(10, 999999999);
        $_SESSION['guestID'] = $guestID;
    } else {
        $guestID = $_SESSION['guestID'];
    }

    if (isset($_POST['country'])) {
        $country = $_POST['country'];
        $_SESSION['country'] = $country;
    }

    $referrer = "";
    if (isset($_POST['referrer'])) {
        $referrer = $_POST['referrer'];
        $_SESSION['referrer'] = $referrer;
    }

    var_dump($_SESSION['deviceType']);
    echo "<br>";
    var_dump($_SESSION['guestID']);
    echo "<br>";
    var_dump($_SESSION['country']);
    echo "<br>";
    var_dump($_SESSION['referrer']);
    // Site Visit
    if (!isset($_SESSION['visit_insertion_done'])) {
        $guestID = $_SESSION['guestID'];
        $device = isset($_SESSION['deviceType']) ? $_SESSION['deviceType'] : ""; // Handling undefined key
        $country = isset($_SESSION['country']) ? $_SESSION['country'] : ""; // Handling undefined key
        $source = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : ""; // Handling undefined key

        $query = "INSERT INTO session(guestID, device_type, source, status, locationFrom) VALUES ('$guestID', '$device', '$source', '0', '$country')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $_SESSION['visit_insertion_done'] = true;
        }
    }

    // Check if user dropped off the quiz
    if (isset($_SESSION['quizProgress']) || isset($_SESSION['siteVisit'])) {
        if (!isset($_SESSION['drop_insertion_done'])) {
            $guestID = $_SESSION['guestID'];
            $device = $_SESSION['deviceType'];
            $country = $_SESSION['country'];
            $source = $_SESSION['referrer'];

            $query = "UPDATE session
                    SET status = '1', device_type = '$device', locationFrom = '$country'
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

    // Category Session (DO NOT TOUCH)
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