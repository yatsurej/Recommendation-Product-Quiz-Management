<?php
    $pageTitle = "P&G Laz-Run Quiz";
    include 'demo_header.php';
    include '../db.php';

    $query = "SELECT * FROM category WHERE categoryID = '4'";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)){
        $categoryDescription    = $row['categoryDescription'];
        $categoryName           = $row['categoryName'];
        $categoryTitle          = $row['categoryTitle'];
?>

<div class="body-wrapper bg3">
    <div class="wrapper justify-center gap20">
        <div class="spacer"></div>
        <div class="header-container">
            <h2>READY FOR A</h2>
            <h1 class="quiz">QUIZ?</h1>
            <h3>Category Title</h3>
        </div>
        <div class="content-container">
            <h2>Category Name</h2>
            <p>Category Description. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut auctor lorem. Aliquam ut nisl non mi gravida aliquam. Cras gravida lorem a felis tristique, nec congue felis bibendum. </p>
        </div>
        <button>CLICK TO START</button>
        <div class="spacer"></div>
    </div>
</div>

<?php
 }
?>