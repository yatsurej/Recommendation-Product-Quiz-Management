<?php
    $pageTitle = "Quiz";
    session_start();
    if (!isset($_SESSION['selectedCategory'])) {
        header('Location: index.php');
        exit();
    }
    include 'header.php';
    include 'db.php';
    $selectedCategory = $_SESSION['selectedCategory'];

    $query = "SELECT * FROM category WHERE categoryID = '$selectedCategory'";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        $categoryDescription    = $row['categoryDescription'];
        $categoryName           = $row['categoryName'];
?>
<style>
    .container{
        margin-top: 10em;
    }
</style>
<div class="container text-center w-75">
    <h3>READY FOR A</h3>
    <h1>QUIZ?</h1>
    <small><?php echo $categoryName; ?></small>
    <br>
    <p><?php echo $categoryDescription;?></p>
    <a href="quiz.php" class="btn btn-primary rounded-pill">Click to start</a>
</div>

<?php
 }
?>