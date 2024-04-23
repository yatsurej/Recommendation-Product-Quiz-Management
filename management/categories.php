<?php
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
        }
    } else {
        header('Location: index.php');
        exit();
    }
?>

<div class="container">
    <h1 class="text-center fw-bold mt-4">Product Management</h1>
    <?php include 'product_nav.php'; ?>
    <div class="d-flex justify-content-end">
        <button class="btn btn-dark h-100 custom-button" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
    </div>
</div>
<div class="container w-100" style="min-height: 62vh;">
    <div class="table-responsive">
        <table class="table table-hover">
            <!-- <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Category Title</th>
                    <th scope="col">Category Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead> -->
            <tbody>
                <?php
                    $query = "SELECT *
                              FROM category c";
                    $result = mysqli_query($conn, $query);
                    
                    while($row = mysqli_fetch_assoc($result)){
                        $categoryID             = $row['categoryID'];
                        $categoryName           = $row['categoryName'];
                        $categoryDescription    = $row['categoryDescription'];
                        $categoryTitle          = $row['categoryTitle'];
                        $categoryStatus         = $row['isActive'];

                        $statusText = ($categoryStatus == 1) ? "Active" : "Inactive";
                    ?>
                    <tr class="card w-100 d-flex flex-row justify-content-between mb-3 p-4">
                        <td style="width: 110px;"><?php echo $categoryName?></td>
                        <td class="w-25"><?php echo $categoryTitle?></td>
                        <td class="w-50"><?php echo $categoryDescription?></td>
                        <td style="width: 110px;"><?php echo $statusText?></td>
                        <td>
                        <div class="d-flex justify-content-center align-items-center">
                                <!-- Edit Button -->
                                <div class="text-center">
                                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?php echo $categoryID; ?>">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Update Category -->
                            <div class="modal fade" id="editCategoryModal<?php echo $categoryID; ?>" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content rounded-15 p-2">
                                        <div class="modal-body p-4">
                                            <form action="functions.php" method="post">
                                                <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>">
                                                <div>
                                                    <h3 class="text-center">Update Category</h3>
                                                    <div class="form-floating my-3">
                                                        <input type="text" class="form-control" name="categoryName" id="categoryName" value="<?php echo $categoryName;?>">
                                                        <label for="categoryName">Category Name</label>
                                                    </div>
                                                    <div class="form-floating my-3">
                                                        <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryTitle"><?php echo $categoryTitle;?></textarea>
                                                        <label for="categoryTitle">Category Title</label>
                                                    </div>
                                                    <div class="form-floating my-3">
                                                        <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryDescription"><?php echo $categoryDescription;?></textarea>
                                                        <label for="categoryDescription">Category Description</label>
                                                    </div>
                                                    <div class="form-floating my-3">
                                                        <select class="form-select" id="categoryStatus" name="categoryStatus">
                                                            <option value="1" <?php echo ($categoryStatus == 1) ? "selected" : ""; ?>>Active</option>
                                                            <option value="0" <?php echo ($categoryStatus == 0) ? "selected" : ""; ?>>Inactive</option>
                                                        </select>
                                                        <label for="categoryStatus">Status</label>
                                                    </div>
                                                    <div class="text-end">
                                                        <button class="btn btn-dark custom-button text-white w-100" name="updateCategory" type="submit">Save changes</button>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-15 p-2">
            <div class="modal-body p-4">
                <h3 class="text-center">Add Category Form</h3>
                <!-- Form -->
                <form action="functions.php" method="post" id="categoryForm">
                <div class="form-floating my-3">
                    <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category's name" required>
                    <label for="categoryName">Category Name</label>
                </div>
                <div class="form-floating my-3">
                    <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryTitle" placeholder="Enter category title here" ></textarea>
                    <label for="categoryTitle">Category Title</label>
                </div>
                <div class="form-floating my-3">
                    <textarea type="text" style="resize: none; height: 100px;" class="form-control" name="categoryDescription" placeholder="Enter category description here" ></textarea>
                    <label for="categoryDescription">Category Description</label>
                </div>
                <div class="d-flex justify-content-end">
                    <button name="addCategory" class="btn btn-dark custom-button text-white w-100" type="submit" id="submitAdd">Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>