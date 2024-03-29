<?php
$pageTitle = "Analytics";
include 'header.php';
include 'navbar.php';
include '../db.php';

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : 0;

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

$timeFilterStart = isset($_GET['timestamp_start']) ? $_GET['timestamp_start'] : '';
$timeFilterEnd = isset($_GET['timestamp_end']) ? $_GET['timestamp_end'] : '';
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'general';

$query = "SELECT COUNT(DISTINCT IFNULL(s.guestID, 'NULL')) AS siteVisits, 
                     SUM(CASE WHEN s.status = 1 THEN 1 ELSE 0 END) AS dropOffSessions,
                     COUNT(DISTINCT CASE WHEN s.status = 2 THEN s.timestamp ELSE NULL END) AS completedSessions,
                     COUNT(DISTINCT CASE WHEN s.status IN (1, 2) THEN s.timestamp ELSE NULL END) AS totalSessions,
                     COUNT(DISTINCT CASE WHEN s.status != 0 THEN s.guestID ELSE NULL END) AS totalUsers,
                     DATE(s.timestamp) AS sessionDate,
                     s.device_type, 
                     c.categoryName,
                     p.prodName,
                     s.locationFrom
              FROM session s
              LEFT JOIN product p ON s.prodID = p.prodID
              LEFT JOIN category c ON p.categoryID = c.categoryID";
//Removed condition WHERE prodID = NULL to Count Site Visits correctly


// Append Conditions when a category is selected
if ($selectedCategory === 'general') {
    $query .= " WHERE 1=1"; // This ensures that the following conditions can be appended with "AND"
} elseif (!empty($selectedCategory)) {
    $query .= " WHERE p.categoryID = $selectedCategory";
}
// Append Conditions when a date filter is selected
if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $formattedTimestampStart = date("Y-m-d H:i:s", strtotime($timeFilterStart));
    $formattedTimestampEnd = date("Y-m-d H:i:s", strtotime($timeFilterEnd . ' + 1 day'));
    $query .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

$query .= " GROUP BY DATE(s.timestamp), c.categoryName, s.device_type, s.guestID, s.locationFrom";
$result = mysqli_query($conn, $query);

$totalSessions          = 0;
$siteVisits             = 0;
$completedSessions      = 0;
$dropOffSessions        = 0;
$categoryCounts         = [];
$deviceCounts           = [];
$sessionDates           = [];
$completedSessionsData  = [];
$dropOffSessionsData    = [];
$countryCounts          = [];

while ($row = mysqli_fetch_assoc($result)) {
    $totalSessions              += $row['totalSessions'];
    $siteVisits                 += $row['siteVisits'];
    $categoryName               = $row['categoryName'];
    $sessionDates[]             = $row['sessionDate'];
    $completedSessions          += $row['completedSessions'];
    $dropOffSessions            += $row['dropOffSessions'];
    $completedSessionsData[]    = $row['completedSessions'];
    $dropOffSessionsData[]      = $row['dropOffSessions'];
    $deviceType                 = $row['device_type'];
    $prodName                   = $row['prodName'];
    $country                    = $row['locationFrom'];

    // Add condition to remove key-value pair if category name is NULL, it can be NULL if drop off and site visit
    if ($categoryName !== NULL) {
        $categoryCounts[$categoryName] = ($categoryCounts[$categoryName] ?? 0) + $row['totalSessions'];
    }
    
    $deviceCounts[$deviceType]      = ($deviceCounts[$deviceType] ?? 0) + $row['siteVisits'];
    $countryCounts[$country] = ($countryCounts[$country] ?? 0) + $row['siteVisits'];
}
// Separate query to count prodCounts
$prodCountsQuery = "SELECT COUNT(*) AS count, p.prodName
                    FROM session s
                    LEFT JOIN product p ON s.prodID = p.prodID
                    LEFT JOIN category c ON p.categoryID = c.categoryID";

// Append Conditions when a category is selected
if ($selectedCategory === 'general') {
    $prodCountsQuery .= " WHERE 1=1"; // This ensures that the following conditions can be appended with "AND"
} elseif (!empty($selectedCategory)) {
    $prodCountsQuery .= " WHERE p.categoryID = $selectedCategory";
}

// Append Conditions when a date filter is selected
if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $formattedTimestampStart = date("Y-m-d H:i:s", strtotime($timeFilterStart));
    $formattedTimestampEnd = date("Y-m-d H:i:s", strtotime($timeFilterEnd . ' + 1 day'));
    $prodCountsQuery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

$prodCountsQuery .= " GROUP BY p.prodName";

// Execute the query
$prodCountsResult = mysqli_query($conn, $prodCountsQuery);

// Initialize prodCounts array
$prodCounts = [];

// Fetch prodCounts results
while ($prodRow = mysqli_fetch_assoc($prodCountsResult)) {
    $prodCounts[$prodRow['prodName']] = $prodRow['count'];
}
// Source query
$sourceQuery = "SELECT DISTINCT s.source 
     FROM session s
     LEFT JOIN product p ON s.prodID = p.prodID
     LEFT JOIN category c ON p.categoryID = c.categoryID
     WHERE s.prodID IS NOT NULL ";

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $sourceQuery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

if ($selectedCategory !== 'general') {
    $sourceQuery .= " AND p.categoryID = $selectedCategory";
}

$sourceQuery .= " ORDER BY s.source";
$sourceResult = mysqli_query($conn, $sourceQuery);

$sources = [];
while ($subrow = mysqli_fetch_assoc($sourceResult)) {
    $sources[] = $subrow['source'];
}

$outBoundQuery = "  SELECT p.prodURL, p.prodName,
                        COUNT(s.outbound) AS count
                        FROM session s
                        INNER JOIN product p ON s.prodID = p.prodID
                        WHERE s.outbound = 1
    ";

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $outBoundQuery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

if ($selectedCategory !== 'general') {
    $outBoundQuery .= " AND p.categoryID = $selectedCategory";
}

$outBoundQuery .= " GROUP BY p.prodURL";
$outBoundResult = mysqli_query($conn, $outBoundQuery);

$outBoundData = array();
while ($row = mysqli_fetch_assoc($outBoundResult)) {
    $outBoundData[] = array(
        'prodName' => $row['prodName'],
        'prodURL' => $row['prodURL'],
        'count' => $row['count']
    );
}
// Separated Total Users Query because and error occurs when combined in the main query
$totalUsersQuery = "SELECT COUNT(DISTINCT guestID) AS totalUsers 
    FROM session s
    LEFT JOIN product p ON s.prodID = p.prodID
    LEFT JOIN category c ON p.categoryID = c.categoryID
    WHERE s.status <> 0";

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $formattedTimestampStart = date("Y-m-d H:i:s", strtotime($timeFilterStart));
    $formattedTimestampEnd = date("Y-m-d H:i:s", strtotime($timeFilterEnd . ' + 1 day'));
    $totalUsersQuery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

if ($selectedCategory !== 'general') {
    $totalUsersQuery .= " AND p.categoryID = $selectedCategory";
}


$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalUsersRow = mysqli_fetch_assoc($totalUsersResult);
$users = $totalUsersRow['totalUsers'];

// Query the Top three Products per Category For GENERAL
$productsQuery = "SELECT 
    c.categoryName,
    p.prodName,
    COUNT(*) AS productCount
    FROM session s
    LEFT JOIN product p ON s.prodID = p.prodID
    LEFT JOIN category c ON p.categoryID = c.categoryID
    WHERE s.prodID IS NOT NULL ";

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $productsQuery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

$productsQuery .= " GROUP BY c.categoryName, p.prodName
                        ORDER BY c.categoryName, productCount DESC";

$result = mysqli_query($conn, $productsQuery);

$topProductsPerCategory = []; // Associative array to store top 3 products per category for GENERAL

while ($row = mysqli_fetch_assoc($result)) {
    $categoryName = $row['categoryName'];
    $prodName = $row['prodName'];
    $productCount = $row['productCount'];

    // Initialize category array
    if (!isset($topProductsPerCategory[$categoryName])) {
        $topProductsPerCategory[$categoryName] = [
            "Top 1 Products" => [],
            "Top 2 Products" => [],
            "Top 3 Products" => []
        ];
    }

    // Store the top products per category
    foreach ($topProductsPerCategory[$categoryName] as $key => $value) {
        if (count($value) < 1) {
            $topProductsPerCategory[$categoryName][$key][] = [
                'prodName' => $prodName,
                'productCount' => $productCount
            ];
            break;
        }
    }
}
// Use to create the chart
$generalProductChartData = [];

foreach ($topProductsPerCategory as $category => $tops) {
    foreach ($tops as $top => $products) {
        foreach ($products as $product) {
            $generalProductChartData[$category][$top][] = [
                'name' => $product['prodName'],
                'count' => $product['productCount']
            ];
        }
    }
}

//Execute the query to fetch question answer data ONLY IF a category is selected

if (isset($selectedCategory) && $selectedCategory !== 'general') {
    // Query to get data for the selected category with optional date filtering
    $queryQuestionAnswer = "SELECT pq.pqID, pq.pqContent AS question, a.answerContent AS answer,
                                COUNT(DISTINCT s.guestID) AS totalUsers,
                                COUNT(sa.saID) AS clickCount
                                FROM parent_question pq
                                LEFT JOIN question_answer qa ON pq.pqID = qa.pqID
                                LEFT JOIN answer a ON qa.answerID = a.answerID
                                LEFT JOIN session_answers sa ON a.answerID = sa.answerID
                                LEFT JOIN session s ON sa.sessionID = s.sessionID
                                LEFT JOIN product p ON s.prodID = p.prodID
                                WHERE p.categoryID = $selectedCategory";

    if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
        $queryQuestionAnswer .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
    }
    $queryQuestionAnswer .= " GROUP BY pq.pqID, pq.pqContent, a.answerID, a.answerContent";

    $resultQuestionAnswer = mysqli_query($conn, $queryQuestionAnswer);

    $questionAnswerData = [];

    while ($row = mysqli_fetch_assoc($resultQuestionAnswer)) {
        $question = $row['question'];
        $answer = $row['answer'];
        $clickCount = (int)$row['clickCount'];
        $totalUsers = (int)$row['totalUsers'];

        // Add data to the array
        $questionAnswerData[$question][] = [
            'answer' => $answer,
            'clickCount' => $clickCount,
            'totalUsers' => $totalUsers,
        ];
    }
}

// Use to hide divs if in general category
$hideIfGeneral = !isset($selectedCategory) || $selectedCategory === 'general';

// Use to hide divs if in a selected category
$hideIfCategory = isset($selectedCategory) && $selectedCategory !== 'general';
?>

<div class="container">
    <div class="row rg-20">
        <div class="row">
            <div class="col-md-12">
                <form id="filterForm" action="" method="GET">
                    <div class="row align-items-center">
                        <div class="col">
                            <div>
                                <?php
                                // Fetch category details from the category table
                                $categoryQuery = "SELECT categoryID, categoryName FROM category";
                                $categoryResult = mysqli_query($conn, $categoryQuery);
                                $categories = array();

                                while ($categoryRow = $categoryResult->fetch_assoc()) {
                                    $categories[$categoryRow['categoryID']] = $categoryRow['categoryName'];
                                }
                                ?>
                                <label for="categoryDropdown">Select Category:</label>
                                <form class="form-inline d-inline">
                                    <select class="custom-select data mr-3" name="category" id="category" onchange="window.location.href=this.value;">
                                        <option value="analytics.php">General</option>
                                        <?php foreach ($categories as $categoryID => $categoryName) : ?>
                                            <option value="?category=<?php echo $categoryID; ?>" <?php echo ($categoryFilter == $categoryID) ? 'selected' : ''; ?>><?php echo $categoryName; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="col">
                            <label for="timestamp_start">Start Date:</label>
                            <input type="date" class="form-control" name="timestamp_start" id="timestamp_start" value="<?php echo $timeFilterStart ? $timeFilterStart : date('Y-m-01'); ?>">
                        </div>
                        <div class="col">
                            <label for="timestamp_end">End Date:</label>
                            <input type="date" class="form-control" name="timestamp_end" id="timestamp_end" value="<?php echo $timeFilterEnd ? $timeFilterEnd : date('Y-m-t'); ?>">
                        </div>
                        <div class="col">
                            <label><br /></label>
                            <input type="hidden" name="category" id="selected_category" value="<?php echo $selectedCategory; ?>">
                            <button type="submit" class="btn btn-dark w-100" style="height: 46px">FILTER</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row ">
            <?php if (!$hideIfCategory) : ?>
                <div class="col ">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>Site Visits</h5>
                            <h2><?php echo $siteVisits; ?></h2>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Users</h5>
                        <h2><?php echo $users; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Sessions</h5>
                        <h2><?php echo $totalSessions; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Device Count</h5>
                        <div id="deviceChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Completed & Drop Off Sessions</h5>
                        <div id="completedDropOffChart"></div>

                    </div>
                </div>
            </div>
        </div>
        <?php if (!$hideIfGeneral) : ?>
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Most Recommended Products</h5>
                        <div id="productRecommendedChartCategory"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!$hideIfCategory) : ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Most Recommended Products</h5>
                            <div id="productRecommendedChartGeneral"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Session per Category</h5>
                            <div id="categoryChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="card">
                <div id="questionAnswerChart"></div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Source</h5>
                        <div class="text-center">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sources as $source) { ?>
                                        <tr>
                                            <td><?php echo $source; ?></td>
                                            <td>
                                                <?php
                                                $countQuery = "SELECT COUNT(*) AS count 
                                                        FROM session s
                                                        LEFT JOIN product p ON s.prodID = p.prodID
                                                        LEFT JOIN category c ON p.categoryID = c.categoryID
                                                        WHERE s.source = '$source'
                                                        AND s.prodID IS NOT NULL ";

                                                if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
                                                    $countQuery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
                                                }

                                                if ($selectedCategory !== 'general') {
                                                    $countQuery .= " AND p.categoryID = $selectedCategory";
                                                }

                                                $countResult = mysqli_query($conn, $countQuery);
                                                $countRow = mysqli_fetch_assoc($countResult);
                                                echo $countRow['count'];
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Country</h5>
                        <div id="countryChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <table class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>Product Recommended</th>
                                        <th>Click Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($outBoundData as $row) : ?>
                                        <tr>
                                            <td> <a href="<?php echo $row['prodURL']; ?>" target="_blank"> <?php echo $row['prodName']; ?></a></td>
                                            <td><?php echo $row['count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update the form action URL when category is selected
        document.querySelectorAll('#categoryDropdown a').forEach(function(categoryLink) {
            categoryLink.addEventListener('click', function(event) {
                event.preventDefault();
                var categoryID = this.getAttribute('data-category-id');
                document.getElementById('selected_category').value = categoryID;
                document.getElementById('filterForm').submit();
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('timestamp_start');
        const endDateInput = document.getElementById('timestamp_end');

        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value < this.value) {
                endDateInput.value = '';
            }
        });

        // Prepare data for pie chart
        var deviceCounts = <?php echo json_encode($deviceCounts); ?>;
        // var totalSessions = Object.values(deviceCounts).reduce((acc, val) => acc + val, 0);
        var deviceChartData = Object.values(deviceCounts).map(count => Number(count));
        var deviceLabels = Object.keys(deviceCounts);

        var deviceChartOptions = {
            chart: {
                type: 'donut',
                height: 240,

            },
            series: deviceChartData,
            labels: deviceLabels,
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                        },
                        size: '60%'
                    },
                    dataLabels: {
                        show: true,
                    },
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var deviceChart = new ApexCharts(document.querySelector("#deviceChart"), deviceChartOptions);
        deviceChart.render();

        var categoryCounts = <?php echo json_encode($categoryCounts); ?>;
        var categoryChartData = Object.values(categoryCounts).map(count => Number(count));
        var categoryLabels = Object.keys(categoryCounts);

        var categoryChartOptions = {
            chart: {
                type: 'donut',
                height: 240,
            },
            series: categoryChartData,
            labels: categoryLabels,
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                        },
                        size: '60%'
                    },
                    dataLabels: {
                        show: true,
                    },
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var categoryChart = new ApexCharts(document.querySelector("#categoryChart"), categoryChartOptions);
        categoryChart.render();

        <?php $combinedData = array($completedSessions, $dropOffSessions); ?>
        var combinedData = <?php echo json_encode($combinedData); ?>;
        var completedDropOffOptions = {
            chart: {
                height: 250,
                type: 'bar', // Change chart type to bar
            },
            plotOptions: {
                bar: {
                    horizontal: true // Make bars horizontal
                }
            },
            series: [{
                data: combinedData // Use the combined data directly as series data
            }],
            xaxis: {
                categories: ['Completed', 'Drop Off'], // Specify categories for y-axis
            },
        };

        var completedDropOffChart = new ApexCharts(document.querySelector("#completedDropOffChart"), completedDropOffOptions);
        completedDropOffChart.render();


        // top products per category chart - GENERAL
        var generalProductChartData = <?php echo json_encode($generalProductChartData); ?>;

        // Prepare data for chart
        let categories = [];
        let seriesTop1 = [];
        let seriesTop2 = [];
        let seriesTop3 = [];

        for (let category in generalProductChartData) {
            categories.push(category);
            let top1Count = generalProductChartData[category]["Top 1 Products"] ? parseInt(generalProductChartData[category]["Top 1 Products"][0].count) : 0;
            let top2Count = generalProductChartData[category]["Top 2 Products"] ? parseInt(generalProductChartData[category]["Top 2 Products"][0].count) : 0;
            let top3Count = generalProductChartData[category]["Top 3 Products"] ? parseInt(generalProductChartData[category]["Top 3 Products"][0].count) : 0;

            seriesTop1.push(top1Count);
            seriesTop2.push(top2Count);
            seriesTop3.push(top3Count);
        }

        var generalProductOptions = {
            chart: {
                type: 'bar',
                height: 250
            },
            plotOptions: {
                bar: {
                    horizontal: false
                }
            },
            series: [{
                name: 'Top 1',
                data: seriesTop1
            }, {
                name: 'Top 2',
                data: seriesTop2
            }, {
                name: 'Top 3',
                data: seriesTop3
            }],
            xaxis: {
                categories: categories,
                title: {
                    text: 'Category'
                }
            },
            yaxis: {
                title: {
                    text: 'Count'
                }
            },
            legend: {
                position: 'top'
            },
            tooltip: {
                y: {
                    formatter: function(value, {
                        series,
                        seriesIndex,
                        dataPointIndex
                    }) {
                        let product = null;
                        switch (seriesIndex) {
                            case 0:
                                product = generalProductChartData[categories[dataPointIndex]]["Top 1 Products"];
                                break;
                            case 1:
                                product = generalProductChartData[categories[dataPointIndex]]["Top 2 Products"];
                                break;
                            case 2:
                                product = generalProductChartData[categories[dataPointIndex]]["Top 3 Products"];
                                break;
                        }
                        if (product && product.length > 0) {
                            return product[0].name + ': ' + value;
                        } else {
                            return 'No product available: ' + value;
                        }
                    }
                }
            }
        };

        var generalProductChart = new ApexCharts(document.querySelector("#productRecommendedChartGeneral"), generalProductOptions);
        generalProductChart.render();


        <?php arsort($prodCounts); ?>   // Sort Products descending order by prodCount

        // If a category is selected - Most Recommended Products
        var categoryProductChartData = <?php echo json_encode($prodCounts); ?>;
        var products = Object.keys(categoryProductChartData); 
        var counts = Object.values(categoryProductChartData).map(count => Number(count));

            // Define the maximum label length
        const MAX_LABEL_LENGTH = 20; // Adjust this value as needed

        // Modify products data to wrap long labels into multiple lines
        const modifiedProducts = products.map(product => {
        if (product.length > MAX_LABEL_LENGTH) {
            // Logic for splitting product name and creating multi-line labels
            const words = product.split(' ');
            const lines = [];
            let currentLine = '';
            for (const word of words) {
            if (currentLine.length + word.length > MAX_LABEL_LENGTH) {
                lines.push(currentLine.trim());
                currentLine = '';
            }
            currentLine += word + ' ';
            }
            lines.push(currentLine.trim());
            return lines;
        }
        return product; // Keep as-is if not too long
        });

        // Define chart options
        var productRecommendedOptions = {
        chart: {
            type: 'bar',
            height: 240,
        },
        series: [{
            name: 'Sessions',
            data: counts
        }],
        xaxis: {
            categories: modifiedProducts,
            labels: {
            show: true,
            style: {
                fontFamily: "Inter, sans-serif",
                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
            },
            }
        }
        };
        var categoryProductRecommendedChart = new ApexCharts(document.querySelector("#productRecommendedChartCategory"), productRecommendedOptions);
        categoryProductRecommendedChart.render();



        <?php if (!$hideIfGeneral) : ?> // Don't execute if no category is selected
            var questionAnswerData = <?php echo json_encode($questionAnswerData); ?>;
            for (const question in questionAnswerData) { // draw chart for each question
                if (questionAnswerData.hasOwnProperty(question)) {
                    drawQuestionAnswerChart(question, questionAnswerData[question]);
                }
            }

            function drawQuestionAnswerChart(question, questionData) {
                var chartContainerId = 'chartContainer_' + question; // Unique ID for each chart container

                // Create a new div element for each chart
                var chartContainer = document.createElement('div');
                chartContainer.id = chartContainerId;
                chartContainer.classList.add('p-4', 'bg-white', 'rounded', 'shadow-md', 'mb-4');
                document.getElementById('questionAnswerChart').appendChild(chartContainer);

                // Define the maximum label length
                const MAX_LABEL_LENGTH = 20; // Adjust this value as needed

                // Modify questionData to wrap long labels into multiple lines
                const modifiedQuestionData = questionData.map(answerData => {
                    const label = answerData.answer;
                    if (label.length > MAX_LABEL_LENGTH) {
                        const words = label.split(' '); // Split by space
                        const lines = [];
                        let currentLine = '';
                        for (const word of words) {
                            if (currentLine.length + word.length > MAX_LABEL_LENGTH) {
                                lines.push(currentLine.trim());
                                currentLine = '';
                            }
                            currentLine += word + ' ';
                        }
                        lines.push(currentLine.trim()); // Add the last line
                        return {
                            ...answerData,
                            answer: lines
                        }; // Use multi-line array as label
                    }
                    return answerData; // Keep as-is if not too long
                });

                // Define options object
                var options = {
                    title: {
                        text: question,
                        align: 'center',
                    },
                    colors: ["#1A56DB", "#FDBA8C"],

                    series: [{
                        name: 'Click Count',
                        data: questionData.map(answerData => answerData.clickCount)
                    }, {
                        name: 'Total Users',
                        data: questionData.map(answerData => answerData.totalUsers)
                    }],
                    chart: {
                        type: "bar",
                        height: "320px",
                        fontFamily: "Inter, sans-serif",
                        toolbar: {
                            show: false,
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "65%",
                            borderRadiusApplication: "end",
                            borderRadius: 10,
                        },
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        style: {
                            fontFamily: "Inter, sans-serif",
                        },
                    },
                    states: {
                        hover: {
                            filter: {
                                type: "darken",
                                value: 1,
                            },
                        },
                    },
                    stroke: {
                        show: true,
                        width: 0,
                        colors: ["transparent"],
                    },
                    grid: {
                        show: false,
                        strokeDashArray: 4,
                        padding: {
                            left: 2,
                            right: 2,
                            top: -14
                        },
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    legend: {
                        show: true,
                    },
                    xaxis: {
                        floating: false,
                        categories: modifiedQuestionData.map(answerData => answerData.answer),
                        labels: {
                            rotate: 0,
                            show: true,
                            style: {
                                fontFamily: "Inter, sans-serif",
                                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                            },
                        },
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false,
                        },
                    },
                    yaxis: {
                        show: false,
                    },
                    fill: {
                        opacity: 1,
                    },

                };

                var chart = new ApexCharts(chartContainer, options);
                chart.render();
            }
        <?php endif; ?>

        // Countries Chart
        var countryCounts = <?php echo json_encode($countryCounts); ?>;
        var countryChartData = Object.values(countryCounts).map(count => Number(count));
        var countryLabels = Object.keys(countryCounts);

        var countryChartOptions = {
            chart: {
                type: 'donut',
                height: 240,
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                        },
                        size: '60%'
                    },
                    dataLabels: {
                        show: true,
                    },
                },
            },
            series: countryChartData,
            labels: countryLabels,
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var countryChart = new ApexCharts(document.querySelector("#countryChart"), countryChartOptions);
        countryChart.render();
    });
</script>