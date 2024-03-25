<?php
    session_start();
    $pageTitle = "Quiz";
    include 'header.php';
    include 'db.php';
    
    // Device Type
    $deviceType = "";
    if(isset($_POST['deviceType'])) {
        $deviceType = $_POST['deviceType'];
        $_SESSION['deviceType'] = $deviceType; 
        exit(); 
    }
    // unset($_SESSION['guestID']);
    // Guest ID
    if (!isset($_SESSION['guestID'])) {
        $guestID = rand(10, 999999999);
        $_SESSION['guestID'] = $guestID;
    } else {
        $guestID = $_SESSION['guestID'];
    }

    // echo $_SESSION['deviceType'] . $_SESSION['guestID'];

    // Check if user dropped off the quiz 
    if (isset($_SESSION['quizProgress'])) {
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
<script>
    var userAgent = navigator.userAgent.toLowerCase(),
    width = screen.availWidth,
    height = screen.availHeight,
    userIsOnMobileDevice = checkIfUserIsOnMobileDevice(userAgent);
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log("Device type sent successfully.");
        }
    };
    xhttp.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("deviceType=" + (userIsOnMobileDevice ? "mobile" : "desktop"));

    function checkIfUserIsOnMobileDevice(userAgent) {
        if(userAgent.includes('mobi') || userAgent.includes('tablet')) {
            return true;
        }
        if(userAgent.includes('android')) {
            if(height > width && width < 800) {
                return true;
            }
            if(width > height && height < 800) {
                return true;
            }
        }
        return false;
    }
</script>
