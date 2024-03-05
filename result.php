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
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: space-around;
            align-items: center;
            height: -webkit-fill-available;
            max-width: 320px;
        }

        p {
            color: #8E7242;
            font-weight: normal;
        }

        .wrapper h3 {
            line-height: 0.8;
        }

        .suggested-products {
            overflow: scroll;
            flex-direction: column;
            -ms-overflow-style: none;
            scrollbar-width: none;
            padding-bottom: var(--mask-height);
        }

        .masked-overflow {
            --scrollbar-width: 8px;
            --mask-height: 32px;
            overflow-y: auto;
            height: 100%;
            padding-bottom: var(--mask-height);
            --mask-image-content: linear-gradient(
            to bottom,
            transparent,
            black var(--mask-height),
            black calc(100% - var(--mask-height)),
            transparent
            );
            --mask-size-content: calc(100% - var(--scrollbar-width)) 100%;
            --mask-image-scrollbar: linear-gradient(black, black);
            --mask-size-scrollbar: var(--scrollbar-width) 100%;
            mask-image: var(--mask-image-content), var(--mask-image-scrollbar);
            mask-size: var(--mask-size-content), var(--mask-size-scrollbar);
            mask-position: 0 100%, 100% 100%; /* Adjusted to apply only at the bottom */
            mask-repeat: no-repeat, no-repeat;
        }


        .suggested-products::-webkit-scrollbar {
        display: none;
        }

        .product-body p {
            font-size: 12px;
            color: #000;
            font-weight: bold;
        }

        .product-container {
            background: #fff;
            border-radius: 15px;
            border-color: #8E7242;
            border-width: 2px;
            display: flex;
            flex-direction: column;
            padding: 20px 20px;
            margin-bottom: 20px;
            height: 350px;
            width: -webkit-fill-available;
        }
        
        .product-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 200px;
            height: 450px;
            justify-content: space-between;
        }

        /* .product-body textarea {
            width: 100%;
            font-size: 11px;
            text-align: center;
            height: 35px;
            max-height: 40px;

        } */

        .product-body button {
            font-size: 11px;
            width: 100%;
        }
        
        .nav-buttons {
            width: -webkit-fill-available;
            padding-top: 20px
        }
        
        .nav-buttons button{
            width: 100%;
            font-size: 1rem;
            letter-spacing: 4px;
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
                            <!-- <textarea type="text" rows="4" readonly><?php echo $prodDescription; ?></textarea> -->
                            <button onclick="window.open('<?php echo $prodURL; ?>', '_blank')" class="choices text-white border-2 rounded-3xl px-auto py-auto text-center">VIEW PRODUCT</button>  
                            <?php } ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="spacer"></div>
                                <?php else: ?>
                <p>No favorable product found.</p>
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