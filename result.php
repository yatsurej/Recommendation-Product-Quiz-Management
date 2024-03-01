<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="./assets/output-min.css">
    <link rel="stylesheet" href="./assets/styles-min.css">
    <style>
        .custom-bg {
            background-image: url('./assets/images/bg-4.jpg')
        }

        p {
            color: #8E7242;
            font-size: clamp(11px, 2vw, 14px);
            font-weight: normal;
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

            <h3>Thanks for waiting</h3>
            <p>Here are the products that might interest you.</p>
            <div class="question-box">
                <?php if (!empty($maxTallyProducts)): ?>
                    <?php foreach ($maxTallyProducts as $maxTallyProduct): ?><br>
                        <div class="container w-100">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="card w-100">
                                    <div class="card-body">
                                        <?php
                                    $prodID                 = $maxTallyProduct['id'];
                                    $productDetailsQuery    = "SELECT prodImage, prodURL, prodDescription FROM product WHERE prodID = '$prodID' AND categoryID = '$selectedCategory'";
                                    $productDetailsResult   = mysqli_query($conn, $productDetailsQuery);
                                    
                                    if ($productDetailsRow  = mysqli_fetch_assoc($productDetailsResult)) {
                                        $prodImage          = $productDetailsRow['prodImage'];
                                        $prodURL            = $productDetailsRow['prodURL'];
                                        $prodDescription    = $productDetailsRow['prodDescription'];
                                        ?>
                                <img src="management/<?php echo $prodImage; ?>" class="rounded img-thumbnail" style="width: 100%;" alt="Product Image" class="img-fluid">
                                <p class="fs-5"><?php echo $maxTallyProduct['name']; ?></p>
                                <textarea type="text" style="resize: none" class="form-control mt-3" rows="4" readonly><?php echo $prodDescription; ?></textarea>
                                <a href="<?php echo $prodURL; ?>" class="btn btn-primary rounded-pill mt-3" target="_blank">VIEW PRODUCT</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <p>No favorable product found.</p>
                    <?php endif; ?>
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