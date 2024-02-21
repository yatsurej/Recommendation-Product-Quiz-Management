<?php
    $pageTitle = "Product Page";
    include '../header.php';
    include 'navbar.php';
    if (isset($_SESSION['username'])) {
        $username   = $_SESSION['username'];
        $userQuery  = "SELECT * FROM user WHERE username = '$username'";
        $userResult = mysqli_query($conn, $userQuery);
    
        while ($userRow = mysqli_fetch_assoc($userResult)) {
            $userID     = $userRow['userID'];

            $_SESSION['userID'] = $userID;
        }
    } else {
        header('Location: index.php');
        exit();
    }
?>

<div class="container w-75 my-3">
    <h1 class="text-center fw-bold mt-4">Product Management</h1>
    <hr style="height:1px;border-width:0;color:gray;background-color:gray">
    <?php include 'product_nav.php';?>
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
                        $prodImage       = $row['prodImage'];
                        $prodURL         = $row['prodURL'];

                    ?>
                    <tr>
                        <td><?php echo $prodName?></td>
                        <td><?php echo $categoryName?></td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- View Button -->
                                <div class="text-center me-1">
                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#viewProductModal<?php echo $prodID; ?>">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-eye"></i>
                                        </div>
                                    </button>
                                </div>
                                <!-- Edit Button -->
                                <div class="text-center">
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $prodID; ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </div>
                                </button>
                            </div>
                            </div>

                            <!-- View Product -->
                            <div class="modal fade" id="viewProductModal<?php echo $prodID; ?>" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="prodID" value="<?php echo $prodID; ?>">
                                            <!-- Card -->
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title text-center"><?php echo $prodName; ?></h4>
                                                </div>
                                                <div class="card-body">
                                                    <a href="<?php echo $prodURL?>">
                                                        <img src="<?php echo $prodImage; ?>" href="<?php echo $prodURL ?>" style="width: 50%" alt="Product Image">                                              
                                                    </a>
                                                    <textarea type="text" style="resize: none" class="form-control" rows="7" id="prodDescription" name="prodDescription" readonly><?php echo $prodDescription; ?></textarea>                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Product -->
                            <div class="modal fade" id="editProductModal<?php echo $prodID; ?>" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="functions.php" method="post">
                                                <input type="hidden" name="prodID" value="<?php echo $prodID; ?>">
                                                
                                                <!-- Card -->
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title text-center"><?php echo $prodName; ?></h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <a href="<?php echo $prodURL?>">
                                                            <img src="<?php echo $prodImage; ?>" href="<?php echo $prodURL ?>" style="width: 50%" alt="Product Image">                                              
                                                        </a>
                                                        <textarea type="text" style="resize: none" class="form-control" rows="7" id="prodDescription" name="prodDescription"><?php echo $prodDescription; ?></textarea>                                                </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success" name="updateProduct" type="submit">Save changes</button>
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


<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-center">Add Product Form</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form action="functions.php" method="post" id="productForm" enctype="multipart/form-data">
                <div class="main-form">
                    <label for="prodName">Product Name</label>
                    <input type="text" class="form-control" id="prodName" name="prodName" placeholder="Enter product's name" required>
                    <label for="prodDescription" class="form-label">Product Description</label>
                    <textarea type="text" style="resize: none" class="form-control" rows="5" id="prodDescription" name="prodDescription" placeholder="Enter product's description" required></textarea>
                    <label for="prodImage" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="prodImage" name="prodImage" required>
                    <label for="prodURL">Product URL</label>
                    <input type="text" class="form-control" id="prodURL" name="prodURL" placeholder="Enter product's URL" required>
                    <label for="prodCategory">Product Category</label>
                    <select class="form-control w-100" name="prodCategory" id="prodCategory" required>
                        <option value="">Select Category</option>
                        <?php
                            $categoryQuery = "SELECT * FROM category";
                            $categoryResult = mysqli_query($conn, $categoryQuery);
                
                            while($row = mysqli_fetch_assoc($categoryResult)){
                                $categoryID     = $row['categoryID'];
                                $categoryName   = $row['categoryName'];
                
                                echo "<option value=\"$categoryID\">$categoryName</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button name="addProduct" class="btn btn-secondary" type="submit" id="submitAdd">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>