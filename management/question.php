<?php
    $pageTitle = "Question Page";
    include '../header.php';
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
                GROUP_CONCAT(a.answerContent) as answerContents, 
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

<div class="container container-body" style="padding-top: 128px;">
    <h1 class="text-center fw-bold mt-4">Questionnaire Management</h1>
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
    <button class="btn btn-dark float-end mb-2" data-bs-toggle="modal" data-bs-target="#addQuestionModal">Add Question</button>
</div>
<div class="container container-body">
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
                        $pqMinAnswer    = $row['pqMinAnswer'];
                        $categoryName   = $row['categoryName'];
                        $prodNames      = $row['prodNames'];
                        $answerContents = $row['answerContents'];
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
                                            <div class="card-header">
                                                <h4 class="card-title text-center">Question ID: <?php echo $pqID; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <label for="minAnswer">Category: <?php echo $categoryName;?></label>
                                                </div>
                                                <label for="numberOptions">Question: </label>
                                                <textarea type="text" style="resize: none" class="form-control" rows="3" id="prodDescription" name="prodDescription" readonly><?php echo $pqContent; ?></textarea>                                                
                                                <div class="row">
                                                    <div class="col">
                                                        <label for="numberOptions">Number of Options:</label>
                                                        <input type="text" name="numberOptions" class="form-control" value="<?php echo $pqNumOptions;?>" readonly>
                                                    </div>
                                                    <div class="col">
                                                        <label for="minAnswer">Minimum Number of Answers:</label>
                                                        <input type="text" class="form-control" value="<?php echo $pqMinAnswer;?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <label for="answers">Answers:</label>
                                                <span class="float-end">Associated Product/s:</span>
                                                <ul class="list-group" name="answers">
                                                    <?php
                                                    $answersArray = explode(',', $answerContents);
                                                    $associatedProducts = explode(',', $prodNames);

                                                    foreach ($answersArray as $index => $answer) {
                                                        echo '<li class="list-group-item list-group-item-secondary">';
                                                        echo $answer;

                                                        if (isset($associatedProducts[$index])) {
                                                            $productsForAnswer = explode(',', $associatedProducts[$index]);
                                                            echo '<span class="float-end text-muted small">';
                                                            echo implode(', ', $productsForAnswer);
                                                            echo '</span>';
                                                        } else {
                                                            echo '<span class="float-end text-muted small">No associated product</span>';
                                                        }

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
                        <!-- Edit Question Details -->
                        <div class="modal fade" id="editQuestionModal<?php echo $pqID; ?>" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="pqID" value="<?php echo $pqID; ?>">
                                        <!-- Card -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title text-center">Question ID: <?php echo $pqID; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <label for="minAnswer">Category: <?php echo $categoryName;?></label>
                                                </div>
                                                <label for="numberOptions">Question: </label>
                                                <textarea type="text" style="resize: none" class="form-control" rows="3" id="prodDescription" name="prodDescription"><?php echo $pqContent; ?></textarea>                                                
                                                <div class="row">
                                                <div class="col">
                                                    <label for="numberOptions">Number of Options:</label>
                                                    <select name="numOptions" class="form-control" required>
                                                        <?php
                                                        for ($i = 0; $i <= 5; $i++) {
                                                            echo "<option value=\"$i\"";
                                                            echo ($i == $pqNumOptions) ? ' selected' : '';
                                                            echo ">$i</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="minAnswer">Minimum Number of Answers:</label>
                                                    <select name="minAnswer" class="form-control" required>
                                                        <?php
                                                        for ($i = 0; $i <= 5; $i++) {
                                                            echo "<option value=\"$i\"";
                                                            echo ($i == $pqMinAnswer) ? ' selected' : '';
                                                            echo ">$i</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                                <label for="answers">Answers:</label>
                                                <span class="float-end">Associated Product/s:</span>
                                                <ul class="list-group" name="answers">
                                                    <?php
                                                    $answersArray = explode(',', $answerContents);
                                                    $associatedProducts = explode(',', $prodNames);

                                                    foreach ($answersArray as $index => $answer) {
                                                        echo '<li class="list-group-item list-group-item-secondary">';
                                                        echo $answer;

                                                        if (isset($associatedProducts[$index])) {
                                                            $productsForAnswer = explode(',', $associatedProducts[$index]);
                                                            echo '<span class="float-end text-muted small">';
                                                            echo implode(', ', $productsForAnswer);
                                                            echo '</span>';
                                                        } else {
                                                            echo '<span class="float-end text-muted small">No associated product</span>';
                                                        }

                                                        echo '</li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success" name="updateQuestion" type="submit">Save changes</button>
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
<div class="container-body">
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
                <h3 class="text-center">Add Question Form</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a href="javascript:void(0)" class="float-end mb-3 add-more-form btn btn-primary">Add more</a>  <!-- Multiple Form Add Button -->
                <!-- Form -->
                <form action="functions.php" method="post" id="questionForm">
                <div class="main-form">
                    <label for="mainForm_category">Choose Category</label>
                    <select name="category" id="mainForm_category" class="form-control" onchange="toggleInputs('mainForm')">
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
                    <label for="mainForm_parentQuestion">Question:</label>
                    <textarea type="text" style="resize: none" class="form-control" rows="3" id="mainForm_parentQuestion" name="parentQuestion" placeholder="Enter question here" required disabled></textarea> 
                    <div class="row">
                        <div class="col">
                            <label for="mainForm_numOptions">Number of Options</label>
                            <select name="numOptions" id="mainForm_numOptions" class="form-control " onchange="addAnswerInputs('mainForm')" required disabled>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="mainForm_numAnswer">Minimum Number of Answers</label>
                            <select name="numAnswer" id="mainForm_numAnswer" class="form-control" required disabled>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <!-- Answer inputs -->
                    <br>
                    <div id="mainForm_answerInputsContainer">
                    </div>
                </div>
                <!-- New Forms for Multiple Forms -->
                <div class="new-forms my-5"></div>
            </div>
            <div class="modal-footer">
                <button name="addQuestion" class="btn btn-success" type="submit" id="submitAdd" disabled>Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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
                                <input type="text" name="answer[]" placeholder="Enter answer option ${i + 1} here." required class="form-control">
                            </div>
                            <div class="col">
                                <select id="${uniqueId}" name="answer_type[${i}][]" class="form-control ${formPrefix}_productAnswers" multiple>
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
    $(document).ready(function () {
        $(document).on('click', '.remove-btn', function () {
            $(this).closest('.main-form').remove();
        });

        $(document).on('click', '.add-more-form', function () {
            $.ajax({
                url: 'get_categories.php',
                type: 'GET',
                success: function (data) {
                    const formPrefix = 'form' + Date.now(); // Unique prefix for each form
                    $('.new-forms').append(`<div class="main-form my-5">
                        <button class="float-end remove-btn btn btn-danger mb-1" type="button">Remove</button>
                        <label for="${formPrefix}_category">Choose Category</label>
                        <select name="category" id="${formPrefix}_category" class="form-control" onchange="toggleInputs('${formPrefix}')">
                            ${data}
                        </select>
                        <label for="${formPrefix}_parentQuestion">Question:</label>
                        <textarea type="text" style="resize: none" class="form-control" rows="3" id="${formPrefix}_parentQuestion" name="parentQuestion" placeholder="Enter question here" required disabled></textarea>
                        <div class="row">
                            <div class="col">
                                <label for="${formPrefix}_numOptions">Number of Options</label>
                                <select name="numOptions" id="${formPrefix}_numOptions" class="form-control " onchange="addAnswerInputs('${formPrefix}')" required disabled>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="${formPrefix}_numAnswer">Minimum Number of Answers</label>
                                <select name="numAnswer" id="${formPrefix}_numAnswer" class="form-control " required disabled>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div id="${formPrefix}_answerInputsContainer">
                        </div>
                    </div>`);
                },
                error: function () {
                    console.error('Error fetching category options');
                }
            });
        });
    });
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