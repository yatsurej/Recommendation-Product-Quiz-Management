<?php
    $pageTitle = "Product Page";
    include '../header.php';
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

<div class="container-body" style="padding-top: 128px;">
    <h1 class="text-center fw-bold mt-4">Product Management</h1>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray">
    <?php include 'product_nav.php';?>
    <button class="btn btn-dark float-end mb-2  me-5" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>

</div>
<div class="container-body">
    <div class="table table-borderless ">
        <table class="table text-center table-hover">
            <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Number of User Engagement</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "SELECT *
                              FROM category c";
                    $result = mysqli_query($conn, $query);
                    
                    while($row = mysqli_fetch_assoc($result)){
                        $categoryID      = $row['categoryID'];
                        $categoryName    = $row['categoryName'];
                        $userEngagement  = $row['userClick'];
                    ?>
                    <tr>
                        <td><?php echo $categoryName?></td>
                        <td><?php echo $userEngagement?></td>
                    </tr>
                    <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-center">Add Category Form</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form action="functions.php" method="post" id="categoryForm">
                <div class="main-form">
                    <label for="categoryName">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category's name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button name="addCategory" class="btn btn-secondary" type="submit" id="submitAdd">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>


<style>
    .container-body {
        padding-left: 256px;
    }

    @media only screen and (max-width: 640px) {
        .container-body {
            padding-left: 0px;
        }
    }
</style>