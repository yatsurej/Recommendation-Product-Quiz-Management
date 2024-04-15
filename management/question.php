<?php
    $pageTitle = "Question Page";
    include 'header.php';
    include 'navbar.php';
    include '../db.php';
    
    if (isset($_SESSION['username'])) {
        $username    = $_SESSION['username'];
        $userQuery   = "SELECT * FROM user WHERE username = '$username'";
        $userResult  = mysqli_query($conn, $userQuery);
    
        while ($userRow = mysqli_fetch_assoc($userResult)) {
            $userID    = $userRow['userID'];
            $_SESSION['userID'] = $userID;
        }
    } else {
        header('Location: index.php');
        exit();
    }

    $recordsPerPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $recordsPerPage;
    $categoryFilter = isset($_GET['category']) ? $_GET['category'] : 0;

    $query = "SELECT pq.*, c.categoryName, 
                GROUP_CONCAT(a.answerContent SEPARATOR '|') as answerContents, 
                GROUP_CONCAT(p.prodName) as prodNames
                FROM parent_question pq 
                LEFT JOIN category c ON pq.categoryID = c.categoryID
                LEFT JOIN question_answer qa ON pq.pqID = qa.pqID 
                LEFT JOIN answer a ON a.answerID = qa.answerID
                LEFT JOIN product_answer pa ON pa.answerID = a.answerID
                LEFT JOIN product p ON pa.prodID = p.prodID";
    if ($categoryFilter > 0) {
        $query .= " WHERE c.categoryID = $categoryFilter";
    }

    $query .= " GROUP BY pq.pqID DESC
        LIMIT $offset, $recordsPerPage";

    $result = mysqli_query($conn, $query);
?>

<div class="container w-75">
    <?php include 'question_nav.php';?>
    <form class="form-inline d-inline">
        <select class="custom-select mr-3" name="category" id="category" onchange="this.form.submit()">
            <option value="0" <?php echo ($categoryFilter == 0) ? 'selected' : ''; ?>>All Categories</option>
            <?php
            $categoriesQuery = "SELECT * FROM category";
            $categoriesResult = mysqli_query($conn, $categoriesQuery);

            while ($categoryRow = mysqli_fetch_assoc($categoriesResult)) {
                $categoryId = $categoryRow['categoryID'];
                $categoryName = $categoryRow['categoryName'];
                echo '<option value="' . $categoryId . '" ' . ($categoryFilter == $categoryId ? 'selected' : '') . '>' . $categoryName . '</option>';
            }
            ?>
        </select>
    </form>
    <button class="btn btn-dark float-end mb-2" data-bs-toggle="modal" data-bs-target="#addQuestionModal">Add Main Question</button>
</div>
<div class="container w-75">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="text-center">
                <tr>
                    <th>Category Name</th>
                    <th>Question Content</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = mysqli_fetch_assoc($result)){
                        $pqID           = $row['pqID'];
                        $pqContent      = $row['pqContent'];
                        $pqNumOptions   = $row['pqNumOptions'];
                        $pqMaxAnswer    = $row['pqMaxAnswer'];
                        $categoryName   = $row['categoryName'];
                        $prodNames      = $row['prodNames'];
                        $answerContents = $row['answerContents'];
                        $pqOrder        = $row['pqOrder'];
                        $categoryID     = $row['categoryID'];
                ?>
                <tr>
                    <td><?php echo $categoryName;?></td>
                    <td><?php echo $pqContent;?></td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- View Button -->
                            <div class="text-center me-1">
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#viewQuestionModal<?php echo $pqID; ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-eye"></i>
                                    </div>
                                </button>
                            </div>
                            <!-- Edit Button -->
                            <div class="text-center">
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#editQuestionModal<?php echo $pqID; ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <!-- View Question Details -->
                        <div class="modal fade" id="viewQuestionModal<?php echo $pqID; ?>" tabindex="-1" aria-labelledby="viewQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="pqID" value="<?php echo $pqID; ?>">
                                        <!-- Card -->
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group my-2">
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="category" class="form-label">Category:</label>
                                                            <input type="text" class="form-control" value="<?php echo $categoryName;?>" id="category" readonly>
                                                        </div>
                                                        <div class="col">
                                                            <label for="category" class="form-label">Question Number:</label>
                                                            <input type="text" class="form-control" value="<?php echo $pqOrder;?>" id="category" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group my-2">
                                                    <label for="numberOptions">Question: </label>
                                                    <textarea type="text" style="resize: none" class="form-control" rows="3" id="prodDescription" name="prodDescription" readonly><?php echo $pqContent; ?></textarea>                                                
                                                </div>
                                                <div class="form-group my-2">
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="answers">Answers:</label>
                                                            <span class="float-end">Associated Product/s:</span>
                                                            <ul class="list-group" name="answers">
                                                                <?php
                                                                $answersWithProducts = [];
                                                                $answersArray = explode('|', $answerContents);
                                                                $associatedProducts = explode(',', $prodNames);

                                                                foreach ($answersArray as $index => $answer) {
                                                                    $product = isset($associatedProducts[$index]) ? $associatedProducts[$index] : 'No associated product';
                                                                    $answersWithProducts[$answer][] = $product;
                                                                }

                                                                foreach ($answersWithProducts as $answer => $products) {
                                                                    echo '<li class="list-group-item list-group-item-secondary">';
                                                                    echo $answer;

                                                                    echo '<span class="float-end text-muted small">';
                                                                    echo implode('<br> ', $products);
                                                                    echo '</span>';

                                                                    echo '</li>';
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editQuestionModal<?php echo $pqID; ?>" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="functions.php" method="post">
                                            <input type="hidden" name="pqID" value="<?php echo $pqID; ?>">
                                            <input type="hidden" name="categoryID" id="editForm_category" value="<?php echo $categoryID; ?>">
                                            <!-- Card -->
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group my-2">
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control" value="<?php echo $pqOrder;?>" name="pqOrder" id="pqOrder">
                                                            <label for="pqOrder">Question Number:</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group my-2">
                                                        <label for="parentQuestion">Question: </label>
                                                        <textarea type="text" style="resize: none" class="form-control" rows="3" id="parentQuestion" name="parentQuestion"><?php echo $pqContent; ?></textarea>                                                
                                                    </div>
                                                    <div class="form-group my-2">
                                                        <div class="row">
                                                            <div class="col">
                                                                <label for="answers">Answers:</label>
                                                                <?php
                                                                    $answersWithProducts = [];
                                                                    $answersArray = explode('|', $answerContents);
                                                                    $associatedProducts = explode(',', $prodNames);
    
                                                                    foreach ($answersArray as $index => $answer) {
                                                                        $product = isset($associatedProducts[$index]) ? $associatedProducts[$index] : 'No associated product';
                                                                        $answersWithProducts[$answer][] = $product;
                                                                    }
    
                                                                    foreach ($answersWithProducts as $answer => $products) {?>
                                                                        <input type="text" class="form-control" value="<?php echo $answer;?>" name="answers[]" id="answers">
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col">
                                                                <label for="answerProduct">Associated Products:</label>
                                                                <?php foreach ($answersWithProducts as $answer => $products) { ?>
                                                                    <select class="form-control answerProducts" name="answerProducts[]" multiple>
                                                                        <?php
                                                                            $productsQuery = "SELECT * FROM product WHERE categoryID = $categoryID";
                                                                            $productsResult = mysqli_query($conn, $productsQuery);

                                                                            while ($productRow = mysqli_fetch_assoc($productsResult)) {
                                                                                $productID   = $productRow['prodID'];
                                                                                $productName = $productRow['prodName'];

                                                                                $selected = in_array($productName, $products) ? 'selected' : '';

                                                                                echo '<option value="' . htmlspecialchars($productID) . '" ' . $selected . '>' . htmlspecialchars($productName) . '</option>';
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <br>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button name="updateMainQuestion" class="btn btn-success" type="submit">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="container w-75">
    <ul class="pagination justify-content-center">
        <?php
        $totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM parent_question";
        if ($categoryFilter > 0) {
            $totalRecordsQuery .= " WHERE categoryID = $categoryFilter";
        }
        
        $totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
        $totalRecordsRow = mysqli_fetch_assoc($totalRecordsResult);
        $totalRecords = $totalRecordsRow['totalRecords'];
        $totalPages = ceil($totalRecords / $recordsPerPage);

        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '">';
                echo '<a class="page-link" href="?page=' . $i . '&category=' . $categoryFilter . '">' . $i . '</a>';
                echo '</li>';
            }
        }
        ?>
    </ul>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-center">Add Main Question Form</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form action="functions.php" method="post" id="questionForm">
                <div class="main-form">
                    <div class="form-group my-2">
                        <div class="form-floating">
                            <select name="category" id="mainForm_category" class="form-control w-100" onchange="toggleInputs('mainForm')">
                                <option value="">Select category</option>
                                <?php
                                    $categoryQuery = "SELECT * FROM category";
                                    $categoryResult = mysqli_query($conn, $categoryQuery);
                                        
                                    while($row = mysqli_fetch_assoc($categoryResult)){
                                        $categoryID   = $row['categoryID'];
                                        $categoryName = $row['categoryName'];
                                            
                                        echo "<option value=\"$categoryID\">$categoryName</option>";
                                    }
                                ?>
                            </select>
                            <label for="mainForm_category">Choose Category</label>
                        </div>
                    </div>
                    <div class="form-group my-2">
                        <div class="form-floating">
                            <textarea type="text" style="resize: none; height: 100px;" class="form-control" id="mainForm_parentQuestion" name="parentQuestion" placeholder="Enter question here" required disabled></textarea> 
                            <label for="mainForm_parentQuestion">Question:</label>
                        </div>
                    </div>
                    <div class="form-group my-2">
                        <div class="row">
                            <div class="col">
                                <div class="form-floating">
                                    <select name="numOptions" id="mainForm_numOptions" class="form-control w-100" onchange="addAnswerInputs('mainForm')" required disabled>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <label for="mainForm_numOptions">Number of Options</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <select name="numAnswer" id="mainForm_numAnswer" class="form-control w-100" required disabled>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <label for="mainForm_numAnswer">Maximum Number of Answers</label>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Answer inputs -->
                <br>
                <div id="mainForm_answerInputsContainer">
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <button name="addMainQuestion" class="btn btn-success" type="submit" id="submitAdd" disabled>Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                                    
<script>
    function addAnswerInputs(formPrefix) {
        const numAnswers = document.getElementById(formPrefix + '_numOptions').value;
        const answerInputsContainer = document.getElementById(formPrefix + '_answerInputsContainer');
        const categoryID = document.getElementById(formPrefix + '_category').value;

        $.ajax({
            url: 'get_products.php',
            type: 'GET',
            data: { categoryID: categoryID }, 
            success: function (data) {
                let inputs = '';
                for (let i = 0; i < numAnswers; i++) {
                    const uniqueId = formPrefix + '_productAnswers' + i;
                    inputs += `
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" name="answer[]" id="answerOption" placeholder="Enter answer option ${i + 1} here." required class="form-control">
                                    <label for="answerOption">Answer Option ${i + 1}</label>
                                </div>
                            </div>
                            <div class="col">
                                <label for="answerProduct" class="text-muted">Product/s</label>
                                <select id="${uniqueId}" class="form-control chosen-select" name="answer_type[${i}][]" multiple>
                                    ${data} 
                                </select>
                            </div>
                        </div>`;
                }
                answerInputsContainer.innerHTML = inputs;

                $('.chosen-select').each(function () {
                    $(this).chosen(); 
                });

                toggleInputs(formPrefix);
            },
            error: function () {
                console.error('Error fetching answer types');
            }
        });
    }
    
    function toggleInputs(formPrefix) {
        var categorySelect       = document.getElementById(formPrefix + '_category');
        var questionInput        = document.getElementById(formPrefix + '_parentQuestion'); 
        var answersSelect        = document.getElementById(formPrefix + '_numOptions');
        var numAnswerSelect      = document.getElementById(formPrefix + '_numAnswer');
        var submitButton         = document.getElementById('submitAdd');

        if (categorySelect.value == 0) {
            questionInput.disabled          = true;
            answersSelect.disabled          = true;
            numAnswerSelect.disabled        = true;
            submitButton.disabled           = true;
        } else {
            questionInput.disabled          = false;
            answersSelect.disabled          = false;
            numAnswerSelect.disabled        = false;
            submitButton.disabled           = false;
        }
    }
</script>