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

    $query = "SELECT bq.*, c.categoryName, c.categoryID,
                GROUP_CONCAT(a.answerID) as answerIDs,
                GROUP_CONCAT(a.answerContent SEPARATOR '|') as answerContents, 
                GROUP_CONCAT(p.prodName SEPARATOR '|') as prodNames
                FROM bonus_question bq 
                LEFT JOIN category c ON bq.categoryID = c.categoryID
                LEFT JOIN question_answer qa ON bq.bqID = qa.bqID 
                LEFT JOIN answer a ON a.answerID = qa.answerID
                LEFT JOIN product_answer pa ON pa.answerID = a.answerID
                LEFT JOIN product p ON pa.prodID = p.prodID";
    if ($categoryFilter > 0) {
        $query .= " WHERE c.categoryID = $categoryFilter";
    }

    $query .= " GROUP BY bq.bqID DESC
        LIMIT $offset, $recordsPerPage";

    $result = mysqli_query($conn, $query);
?>

<div class="container">
    <?php include 'question_nav.php'; ?>
    <div class="col-md-12 p-0">
        <div class="d-flex justify-content-between align-items-center">
            <form class="form-inline d-inline">
                <select class="custom-select mr-3 data" name="category" id="category" onchange="this.form.submit()">
                    <option value="0" <?php echo ($categoryFilter == 0) ? 'selected' : ''; ?>>All Categories</option>
                    <?php
                    $categoriesQuery = "SELECT * FROM category WHERE isActive = 1";
                    $categoriesResult = mysqli_query($conn, $categoriesQuery);

                    while ($categoryRow = mysqli_fetch_assoc($categoriesResult)) {
                        $categoryId = $categoryRow['categoryID'];
                        $categoryName = $categoryRow['categoryName'];
                        echo '<option value="' . $categoryId . '" ' . ($categoryFilter == $categoryId ? 'selected' : '') . '>' . $categoryName . '</option>';
                    }
                    ?>
                </select>
            </form>
            <button class="btn btn-dark custom-button-fit" data-bs-toggle="modal" data-bs-target="#addVoucherQuestionModal">Add Voucher Question</button>
        </div>
    </div>
</div>
<div class="container" style="min-height: 56vh;">
    <div class="table-responsive">
        <table class="table table-hover">
            <tbody>
                <?php
                    while ($row = mysqli_fetch_assoc($result)){
                        $bqID           = $row['bqID'];
                        $bqContent      = $row['bqContent'];
                        $bqNumOptions   = $row['bqNumOptions'];
                        $bqMaxAnswer    = $row['bqMaxAnswer'];
                        $categoryName   = $row['categoryName'];
                        $prodNames      = $row['prodNames'];
                        $answerContents = $row['answerContents'];
                        $answerIDs      = $row['answerIDs'];
                        $categoryID     = $row['categoryID'];
                        $bqStatus       = $row['isActive'];
                ?>
                <tr class="card w-100 d-flex flex-row justify-content-between mb-3 p-4">
                    <td><?php echo $categoryName; ?></td>
                    <td class="w-75"><?php echo $bqContent; ?></td>
                    <td style="width: 140px;">
                        <form action="functions.php" method="post">
                            <input type="hidden" name="bqID" value="<?php echo $bqID; ?>">
                            <input type="hidden" name="updateBqStatus" value="true"> 
                            <input type="hidden" name="bqStatus" value="<?php echo ($bqStatus == 1) ? 1 : 0; ?>">
                            <label class="switch">
                                <input type="checkbox" name="toggle" <?php echo ($bqStatus == 1) ? "checked" : ""; ?> onchange="updateStatus(this)">
                                <span class="slider"></span>
                            </label>
                        </form>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- View Button -->
                            <div class="text-center me-1">
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewQuestionModal<?php echo $bqID; ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-eye"></i>
                                    </div>
                                </button>
                            </div>
                            <!-- Edit Button -->
                            <div class="text-center">
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#editQuestionModal<?php echo $bqID; ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <!-- View Question Details -->
                        <div class="modal fade" id="viewQuestionModal<?php echo $bqID; ?>" tabindex="-1" aria-labelledby="viewQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content rounded-15 p-2">
                                    <div class="modal-body p-4">
                                        <input type="hidden" name="bqID" value="<?php echo $bqID; ?>">
                                        <div class="d-flex justify-content-between mb-4">
                                            <div>
                                                <p class="mb-0">Category</p>
                                                <h4><strong><?php echo $categoryName ?></strong></h4>
                                            </div>
                                        </div>
                                        <div class="mb-4 text-center">
                                            <p class="mb-0">Question: </p>
                                            <h4 class="fw-bold"> <?php echo $bqContent; ?></h4>
                                        </div>
                                        <div class="form-group my-2">
                                            <div class="row">
                                                <div class="col">
                                                    <label for="answers">Answers:</label>
                                                    <span>Associated Product/s:</span>
                                                    <ul class="list-group" name="answers">
                                                        <?php
                                                        $answersWithProducts = [];
                                                        $answersArray = explode('|', $answerContents);
                                                        $associatedProducts = explode('|', $prodNames);

                                                        foreach ($answersArray as $index => $answer) {
                                                            $product = isset($associatedProducts[$index]) ? $associatedProducts[$index] : 'No associated product';
                                                            $answersWithProducts[$answer][] = $product;
                                                        }

                                                        foreach ($answersWithProducts as $answer => $products) {
                                                            echo '<li class="list-group-item list-group-item-secondary mb-2 card bg-transparent">';
                                                            echo '<p class="mb-0">Option:</p> <p>', htmlspecialchars($answer), '</p >';

                                                            echo '<span class="text-muted small">';
                                                            echo '<p class="mb-0">Associated Products:</p>';

                                                            // Start a new unordered list for associated products
                                                            echo '<ul>';

                                                            // Iterate over each product and create list items
                                                            foreach ($products as $product) {
                                                                echo '<li>', htmlspecialchars($product), '</li>';
                                                            }

                                                            // End the unordered list
                                                            echo '</ul>';

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
                        
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editQuestionModal<?php echo $bqID; ?>" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content rounded-15 p-2">
                                    <div class="modal-body p-4">
                                        <form action="functions.php" method="post">
                                            <input type="hidden" name="bqID" value="<?php echo $bqID; ?>">
                                            <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>">
                                            <div class="form-group my-4">
                                                <label for="parentQuestion">Question: </label>
                                                <textarea type="text" style="resize: none" class="form-control" rows="3" id="parentQuestion" name="parentQuestion"><?php echo $bqContent; ?></textarea>
                                            </div>
                                            <div class="form-group my-2">
                                                <div class="row">
                                                    <div class="col">
                                                        <label for="answers">Answers:</label>
                                                        <?php
                                                        $answersWithProducts = [];
                                                        $answersArray = explode('|', $answerContents);
                                                        $answerIDsArray = explode(',', $answerIDs); 
                                                        $associatedProducts = explode('|', $prodNames);

                                                        $uniqueAnswers = [];

                                                        foreach ($answersArray as $index => $answer) {
                                                            $answerID = isset($answerIDsArray[$index]) ? $answerIDsArray[$index] : 'No associated answer ID'; // Get the answer ID

                                                            if (!in_array($answer, $uniqueAnswers)) {
                                                                $uniqueAnswers[] = $answer;
                                                        ?>
                                                                <div class="row d-flex align-items-center justify-content-between mb-3">
                                                                    <div class="col-10 p-0">
                                                                        <input type="hidden" name="answerID" value="<?php echo $answerID; ?>">
                                                                        <input type="text" class="form-control" value="<?php echo $answer; ?>" name="answers[]" id="answers">
                                                                    </div>
                                                                    <div class="col-2 p-0 text-center">
                                                                        <button type="button" class="btn btn-dark rounded-circle p-2" style="line-height: 0;" data-bs-toggle="modal" onclick="dismissAndOpenModal('<?php echo $answerID; ?>')" data-bs-dismiss="modal"><i class="fa-solid fa-gear"></i></button>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <button name="updateVoucherQuestion" class="btn btn-dark custom-button text-white w-100" type="submit">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            foreach ($answersArray as $index => $answer) {
                                $answerID = isset($answerIDsArray[$index]) ? $answerIDsArray[$index] : 'No associated answer ID'; // Get the answer ID
                                ?>
                                <div class="modal fade" id="editAnswerModal<?php echo $answerID; ?>" tabindex="-1" aria-labelledby="editAnswerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded-15 p-2">
                                            <div class="modal-body p-4">
                                                <form action="functions.php" method="post">
                                                    <h5 class="modal-title" id="editAnswerModalLabel">Edit Answer</h5>
                                                    <input type="hidden" name="answerID" value="<?php echo $answerID; ?>">
                                                    <div class="form-group my-2">
                                                        <label for="answer">Answer:</label>
                                                        <input type="text" class="form-control" value="<?php echo $answer; ?>" name="answer" readonly>
                                                    </div>
                                                    <div class="form-group my-2 mb-4">
                                                        <label for="products">Associated Products</label>
                                                        <select name="products[]" class="chosen-products" multiple>
                                                            <?php
                                                            // Fetch associated products for this specific answer
                                                            $productsQuery = "SELECT * FROM product WHERE categoryID = $categoryID";
                                                            $productsResult = mysqli_query($conn, $productsQuery);

                                                            while ($productRow = mysqli_fetch_assoc($productsResult)) {
                                                                $productID   = $productRow['prodID'];
                                                                $productName = $productRow['prodName'];
                                                                $selected = in_array($productName, explode(',', $associatedProducts[$index])) ? 'selected' : ''; // Check if this product is associated with the current answer

                                                                echo '<option value="' . htmlspecialchars($productID) . '" ' . $selected . '>' . htmlspecialchars($productName) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                <button name="updateVoucherAnswerProducts" class="btn btn-dark custom-button text-white w-100" type="submit">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
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
        $totalRecordsQuery = "SELECT COUNT(*) AS totalRecords FROM bonus_question";
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
<div class="modal fade" id="addVoucherQuestionModal" tabindex="-1" aria-labelledby="addVoucherQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-15 p-2">
            <div class="modal-body p-4">
                <!-- Form -->
                <h3 class="text-center">Add Voucher Question</h3>
                <form action="functions.php" method="post" id="questionForm">
                    <div class="main-form">
                        <div class="form-group mb-4">
                            <div class="form-floating my-3">
                                <select name="category" id="mainForm_category" class="form-control w-100" onchange="toggleInputs('mainForm')">
                                    <option value="">Select category</option>
                                    <?php
                                    $categoryQuery = "SELECT * FROM category WHERE isActive = 1";
                                    $categoryResult = mysqli_query($conn, $categoryQuery);

                                    while ($row = mysqli_fetch_assoc($categoryResult)) {
                                        $categoryID   = $row['categoryID'];
                                        $categoryName = $row['categoryName'];

                                        echo "<option value=\"$categoryID\">$categoryName</option>";
                                    }
                                    ?>
                                </select>
                                <label for="mainForm_category">Choose Category</label>
                            </div>
                        </div>
                        <div class="form-group my-4">
                            <div class="form-floating">
                                <textarea type="text" style="resize: none; height: 100px;" class="form-control" id="mainForm_voucherQuestion" name="voucherQuestion" placeholder="Enter question here" required disabled></textarea>
                                <label for="mainForm_voucherQuestion">Question:</label>
                            </div>
                        </div>
                        <div class="form-group my-4">
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
                <button name="addVoucherQuestion" class="btn btn-dark custom-button text-white w-100" type="submit" id="submitAdd" disabled>Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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
                                <select id="${uniqueId}" id="answerProduct" name="answer_type[${i}][]" class="form-control ${formPrefix}_productAnswers" multiple>
                                    ${data} 
                                </select>
                            </div>
                        </div>`;
                }
                answerInputsContainer.innerHTML = inputs;

                $('.' + formPrefix + '_productAnswers').each(function () {
                    new MultiSelectTag(this.id);
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
        var questionInput        = document.getElementById(formPrefix + '_voucherQuestion'); 
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
    function dismissAndOpenModal(answerID) {
        $('#editQuestionModal<?php echo $bqID; ?>').modal('hide');

        $('#editAnswerModal' + answerID).modal('show');
    }
    $(".chosen-products").chosen({ width: '100%' });
    function updateStatus(checkbox) {
        if (checkbox.checked) {
            checkbox.form.querySelector('[name="bqStatus"]').value = 1; // Set bqStatus to 1 if checkbox is checked
        } else {
            checkbox.form.querySelector('[name="bqStatus"]').value = 0; // Set bqStatus to 0 if checkbox is unchecked
        }
        checkbox.form.submit(); // Submit the form
    }
</script>

<?php
include 'footer.php';
?>