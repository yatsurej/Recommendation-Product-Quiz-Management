<?php
    $pageTitle = "Quiz Result";
    include 'header.php';
    include 'db.php';
    session_start();

    if (!isset($_SESSION['selectedCategory']) || !isset($_SESSION['productTally'])) {
        header('Location: index.php');
        exit();
    }
    
    $selectedCategory = $_SESSION['selectedCategory'];
    $currentQuestion = $_SESSION['currentQuestion'];
    $productTally = $_SESSION['productTally'];

    $bonusQuestionQuery = "SELECT bqID, bqContent FROM bonus_question WHERE categoryID = '$selectedCategory'";
    $bonusQuestionResult = mysqli_query($conn, $bonusQuestionQuery);
    $bonusQuestion = mysqli_fetch_assoc($bonusQuestionResult);

    $maxTallyProducts = array();
    $maxTally = 0;

    foreach ($productTally as $product) {
        if ($product['tally'] == $maxTally) {
            $maxTallyProducts[] = $product;
        } elseif ($product['tally'] > $maxTally) {
            $maxTally = $product['tally'];
            $maxTallyProducts = array($product);
        }
    }
?>

<div class="body-wrapper bg5">
    <div class="wrapper justify-center gap20">
        <div class="result-title">
            <h3>Thanks for waiting</h3>
            <p>Here are the products that might interest you.</p>
        </div>
        <div class="suggested-products masked-overflow">
            <?php if (!empty($maxTallyProducts)): ?>
                <?php foreach ($maxTallyProducts as $maxTallyProduct): ?>
                    <div class="product-container">
                        <div class="product-body">
                            <?php
                            $prodID = $maxTallyProduct['id'];
                            $productDetailsQuery = "SELECT prodImage, prodURL, prodDescription FROM product WHERE prodID = '$prodID' AND categoryID = '$selectedCategory'";
                            $productDetailsResult = mysqli_query($conn, $productDetailsQuery);

                            if ($productDetailsRow  = mysqli_fetch_assoc($productDetailsResult)) {
                                $prodImage          = $productDetailsRow['prodImage'];
                                $prodURL            = $productDetailsRow['prodURL'];
                                $prodDescription    = $productDetailsRow['prodDescription'];

                                $_SESSION['prodID'] = $prodID;
                                ?>
                                <img src="management/<?php echo $prodImage; ?>" class="suggested-image" alt="Product Image" class="img-fluid">
                                <p><?php echo $maxTallyProduct['name']; ?></p>
                                <button onclick="window.open('<?php echo $prodURL; ?>', '_blank')" class="view-product-button">VIEW PRODUCT</button>
                            <?php } ?>
                        </div>
                    </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No favorable product found.</p>
        <?php endif; ?>
        </div>
        <div class="voucher">
            <?php if ($bonusQuestion && isset($_SESSION['bonusAnswered'])): ?>
                <?php
                if ($_SESSION['bonusAnswered']) {
                    $voucherQuery = "SELECT voucherCode FROM voucher WHERE categoryID = '$selectedCategory'";
                    $voucherResult = mysqli_query($conn, $voucherQuery);

                    if ($voucherResult && $voucher = mysqli_fetch_assoc($voucherResult)['voucherCode']) {
                        echo "<h4><strong>Congratulations!</strong><br>Here's your voucher code</h4>";
                        echo "<h1 id='voucher'>$voucher</h1>";
                        ?>
                        <div class="nav-buttons">
                            <button onclick="copyToClipboard('#voucher')">COPY VOUCHER</button>
                            <button onclick="window.location.href = 'index.php';">BACK TO HOME</button>
                        </div>
                    <?php
                    } 
                } else {
                    echo "<p>Thanks for trying! Unfortunately, you didn't get the correct answer.</p>";
                    ?>
                    <div class="nav-buttons">
                        <button onclick="window.location.href = 'index.php';">BACK TO HOME</button>
                    </div>
                <?php
                }

                session_unset();
                session_destroy(); 
            ?>
        </div>
    <?php elseif ($bonusQuestion): ?>
        <div class="nav-buttons">
            <button onclick="window.location.href='bonus.php'">GET VOUCHER</button>
            <button onclick="window.location.href = 'index.php';">BACK TO HOME</button>
        </div>
    <?php else: ?>
        <div class="nav-buttons">
            <button onclick="window.location.href = 'index.php';">BACK TO HOME</button>
        </div>
        <?php
        session_unset();
        session_destroy(); 
        ?>
    <?php endif;
    // session_unset();
    // session_destroy();  ?>
    </div>
</div>

<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p id="modalMessage"></p>
  </div>
</div>

<script>
    document.getElementById('voucher').style.cursor = 'pointer'; // Change cursor to pointer when hovering over the voucher code
    document.getElementById('voucher').addEventListener('click', function() {
        copyToClipboard('#voucher');
    });

    function copyToClipboard(elementId) {
        var voucherCode = document.querySelector(elementId).innerText;
        var tempInput = document.createElement("input");
        tempInput.value = voucherCode;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        
        // Show modal
        var modal = document.getElementById("myModal");
        var modalMessage = document.getElementById("modalMessage");
        modalMessage.innerHTML = "Voucher code copied to clipboard: <strong>" + voucherCode + "</strong>";
        modal.style.display = "flex";

        // Close the modal when the user clicks on <span> (x)
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal when the user clicks anywhere outside of the modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
</script>