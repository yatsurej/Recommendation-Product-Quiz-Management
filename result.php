<?php
$pageTitle = "Result";
session_start();

$_SESSION['resultViewed'] = true;

if (!isset($_SESSION['selectedCategory']) || !isset($_SESSION['productTally'])) {
    header('Location: index.php');
    exit();
}

include 'header.php';
include 'db.php';

$selectedCategory = $_SESSION['selectedCategory'];
$productTally = $_SESSION['productTally'];

$maxTallyProduct = null;
$maxTally = 0;

foreach ($productTally as $product) {
    if ($product['tally'] > $maxTally) {
        $maxTally = $product['tally'];
        $maxTallyProduct = $product;
    }
}

?>

<div class="container w-75 text-center mt-5">
    <h2><em>Congratulations!</em> The most favorable product is:</h2>
    <?php if ($maxTallyProduct): ?>
        <div class="d-flex justify-content-center align-items-center">
            <div class="card w-50">
                <div class="card-body">
                    <h3><?php echo $maxTallyProduct['name']; ?></h3>
                    <?php
                    $prodName = $maxTallyProduct['name'];  
                    $productDetailsQuery = "SELECT prodImage, prodURL FROM product WHERE prodName = '$prodName' AND categoryID = '$selectedCategory'";
                    $productDetailsResult = mysqli_query($conn, $productDetailsQuery);

                    if ($productDetailsRow = mysqli_fetch_assoc($productDetailsResult)) {
                        $prodImage = $productDetailsRow['prodImage'];
                        $prodURL = $productDetailsRow['prodURL'];
                        ?>
                        <a href="<?php echo $prodURL; ?>" target="_blank">
                            <img src="management/<?php echo $prodImage; ?>" style="width: 50%;" alt="Product Image" class="img-fluid">
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>No favorable product found.</p>
    <?php endif; ?>
</div>

<?php
    session_unset();
    session_destroy();
?>