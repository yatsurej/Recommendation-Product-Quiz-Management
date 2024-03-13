<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <!-- <link rel="stylesheet" href="./assets/output-min.css"> -->
    <link rel="stylesheet" href="./assets/styles.css">
</head>
<body>
<?php
    $pageTitle = "Result";
    session_start();

    $_SESSION['resultViewed'] = true;

    if (!isset($_SESSION['selectedCategory']) || !isset($_SESSION['productTally'])) {
        header('Location: index.php');
        exit();
    }

    // include 'header.php';
    include 'db.php';

    $selectedCategory = $_SESSION['selectedCategory'];
    $productTally     = $_SESSION['productTally'];

    $maxTallyProducts = array();
    $maxTally = 0;

    foreach ($productTally as $product) {
        if ($product['tally'] == $maxTally) {
            $maxTallyProducts[] = $product;
        } elseif ($product['tally'] > $maxTally) {
            $maxTally           = $product['tally'];
            $maxTallyProducts   = array($product);
        }
    }
?>

<div class="body-wrapper bg5">
    <div class="wrapper justify-center gap20">
        <div class="result-title">
            <h3>Thanks for waiting</h3>
            <p>Here are the products<br>that might interest you.</p>
        </div>
        <div class="suggested-products masked-overflow">
            <?php if (!empty($maxTallyProducts)): ?>
                <?php foreach ($maxTallyProducts as $maxTallyProduct): ?>
                    <div class="product-container">
                        <div class="product-body">
                            <?php
                            $prodID                 = $maxTallyProduct['id'];
                            $productDetailsQuery    = "SELECT prodImage, prodURL, prodDescription FROM product WHERE prodID = '$prodID' AND categoryID = '$selectedCategory'";
                            $productDetailsResult   = mysqli_query($conn, $productDetailsQuery);
                            
                            if ($productDetailsRow  = mysqli_fetch_assoc($productDetailsResult)) {
                                $prodImage          = $productDetailsRow['prodImage'];
                                $prodURL            = $productDetailsRow['prodURL'];
                                $prodDescription    = $productDetailsRow['prodDescription'];
                                ?>
                        <img src="management/<?php echo $prodImage; ?>" class="suggested-image" alt="Product Image">
                        <p><?php echo $maxTallyProduct['name']; ?></p> 
                        <button onclick="window.open('<?php echo $prodURL; ?>', '_blank')" class="view-product-button">VIEW PRODUCT</button>  
                        <?php } ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="product-container">
                    <div class="product-body">
                        <p class="no-favorable-products">No favorable product found.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="nav-buttons">
            <button onclick="window.location.href = 'index.php';">Retake the quiz</button>
            <button onclick="window.location.href = 'index.php';">Back to home</button>
        </div>
    </div>
</div>

<?php
    session_unset();
    session_destroy();   
?>

</body>
</html>