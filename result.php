<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="./assets/output-min.css">
    <link rel="stylesheet" href="./assets/styles.css">
    <style>

        .spacer{
            height: 20px
        }

        .custom-bg {
            background-image: url('./assets/images/bg-4.jpg')
        }

        .wrapper {
            height: -webkit-fill-available;
            max-width: 320px;
        }

    </style>
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

<div class="custom-bg">
    <div class="content">
        <div class="wrapper">
            <div class="spacer"></div>
            <h3>Thanks for<br>waiting</h3>
            <p>Here are the products<br>that might interest you.</p>
            <div class="suggested-products masked-overflow">
                <div class="spacer"></div>
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
                            <img src="management/<?php echo $prodImage; ?>" class="rounded img-thumbnail" alt="Product Image" class="img-fluid">
                            <p><?php echo $maxTallyProduct['name']; ?></p> 
                            <button onclick="window.open('<?php echo $prodURL; ?>', '_blank')" class="choices text-white border-2 rounded-3xl px-auto py-auto text-center">VIEW PRODUCT</button>  
                            <?php } ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="spacer"></div>
                <?php else: ?>
                    <div class="product-container">
                        <div class="product-body">
                            <p class="no-favorable-products">No favorable product found.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="nav-buttons">
                <button class="choices text-white border-2 rounded-3xl px-auto py-auto text-center me-2 mb-2" onclick="window.location.href = 'index.php';">Retake the quiz</button>
                <button class="choices text-white border-2 rounded-3xl px-auto py-auto text-center me-2 mb-2" onclick="window.location.href = 'index.php';">Back to home</button>
            </div>
        </div>
    </div>
</div>

<?php
    // session_unset();
    // session_destroy();   
?>

</body>
</html>