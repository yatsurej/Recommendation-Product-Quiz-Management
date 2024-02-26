<?php
    session_start();
    $pageTitle = "Quiz";
    include 'header.php';
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION['selectedCategory'] = $_POST['category'];
        header("Location: category-page.php");
        exit();
    }
?>
<style>
    .form{
        margin-top: 10em;
    }
</style>
<div class="container w-75 mt-4 text-center">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="post">
        <h3 class="mb-3 fw-bolder">Solutions you're looking for</h3>
        <?php
        $categoryQuery = "SELECT * FROM category";
        $categoryResult = mysqli_query($conn, $categoryQuery);

        while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
            $categoryTitle  = $categoryRow['categoryTitle'];
            $categoryID     = $categoryRow['categoryID'];
            $categoryName   = $categoryRow['categoryName'];
            ?>
            <div class="mb-4">
                <button type="submit" class="btn btn-primary rounded-pill w-50" name="category" value="<?php echo $categoryID; ?>">
                    <?php echo $categoryName; ?>
                </button>
            </div>
            <?php
        }
        ?>
    </form>
</div>
