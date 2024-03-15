<?php
    $pageTitle = "Quiz Page";
    include 'header.php';
    include 'navbar.php';
    if (isset($_SESSION['username'])) {
        $username   = $_SESSION['username'];
        $userQuery  = "SELECT * FROM user WHERE username = '$username'";
        $userResult = mysqli_query($conn, $userQuery);
    
        while ($userRow = mysqli_fetch_assoc($userResult)) {
            $userID    = $userRow['userID'];
    
            $_SESSION['userID'] = $userID;
        }
    } else {
        header('Location: index.php');
        exit();
    }
?>

<div class="container w-75 my-3">
    <h1 class="text-center fw-bold mt-4">Quiz Management</h1>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray">
    <button class="btn btn-dark float-end mb-2 me-5" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
</div>
<div class="container w-100">
    <div class="table table-borderless ">
        <table class="table text-center table-hover">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "SELECT c.categoryID, c.categoryName, p.*
                              FROM category c 
                              NATURAL JOIN product p
                              GROUP BY p.prodID";
                    $result = mysqli_query($conn, $query);
                    
                    while($row = mysqli_fetch_assoc($result)){
                        $categoryID      = $row['categoryID'];
                        $categoryName    = $row['categoryName'];
                        $prodID          = $row['prodID'];
                        $prodName        = $row['prodName'];
                        $prodDescription = $row['prodDescription'];
                    ?>
                    <tr>
                        <td><?php echo $prodName?></td>
                        <td><?php echo $categoryName?></td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- View Button -->
                                <div class="text-center me-1">
                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#viewQuestionModal<?php $questionID;?>">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-eye"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>