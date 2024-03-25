<?php
<<<<<<< Updated upstream
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
=======
$pageTitle = "Product Page";
include 'header.php';
include 'navbar.php';
if (isset($_SESSION['username'])) {
    $username   = $_SESSION['username'];
    $userQuery  = "SELECT * FROM user WHERE username = '$username'";
    $userResult = mysqli_query($conn, $userQuery);

    while ($userRow = mysqli_fetch_assoc($userResult)) {
        $userID    = $userRow['userID'];

        $_SESSION['userID'] = $userID;
>>>>>>> Stashed changes
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<<<<<<< Updated upstream
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
=======
<div class="admin-page">

    <div class="container w-75 my-3">
        <h1 class="text-center fw-bold mt-4">Product Management</h1>
        <hr style="height:1px;border-width:0;color:gray;background-color:gray">
        <?php include 'product_nav.php'; ?>
        <button class="btn btn-dark float-end mb-2  me-5" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>

    </div>
    <div class="container w-100">
        <div class="table table-borderless ">
            <table class="table text-center table-hover">
                <thead>
                    <tr>
                        <th scope="col">Category</th>
                        <th scope="col">Category Title</th>
                        <th scope="col">Category Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT *
                              FROM category c";
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $categoryID             = $row['categoryID'];
                        $categoryName           = $row['categoryName'];
                        $categoryDescription    = $row['categoryDescription'];
                        $categoryTitle          = $row['categoryTitle'];
                    ?>
                        <tr>
                            <td><?php echo $categoryName ?></td>
                            <td><?php echo $categoryTitle ?></td>
                            <td><?php echo $categoryDescription ?></td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <!-- Edit Button -->
                                    <div class="text-center">
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?php echo $categoryID; ?>">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Update Category -->
                                <div class="modal fade" id="editCategoryModal<?php echo $categoryID; ?>" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="text-center">Update Category</h3>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="functions.php" method="post">
                                                    <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>">
                                                    <!-- Card -->
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="form-floating my-3">
                                                                <input type="text" class="form-control" name="categoryName" id="categoryName" value="<?php echo $categoryName; ?>">
                                                                <label for="categoryName">Category Name</label>
                                                            </div>
                                                            <div class="form-floating my-3">
                                                                <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryTitle"><?php echo $categoryTitle; ?></textarea>
                                                                <label for="categoryTitle">Category Title</label>
                                                            </div>
                                                            <div class="form-floating my-3">
                                                                <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryDescription"><?php echo $categoryDescription; ?></textarea>
                                                                <label for="categoryDescription">Category Description</label>
                                                            </div>
                                                            <div class="text-end">
                                                                <button class="btn btn-success my-2 mx-2" name="updateCategory" type="submit">Save changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category's name" required>
                            <label for="categoryName">Category Name</label>
                        </div>
                        <div class="form-floating my-3">
                            <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryTitle" placeholder="Enter category title here"></textarea>
                            <label for="categoryTitle">Category Title</label>
                        </div>
                        <div class="form-floating my-3">
                            <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryDescription" placeholder="Enter category description here"></textarea>
                            <label for="categoryDescription">Category Description</label>
                        </div>
                </div>
                <div class="modal-footer">
                    <button name="addCategory" class="btn btn-secondary" type="submit" id="submitAdd">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>
>>>>>>> Stashed changes
