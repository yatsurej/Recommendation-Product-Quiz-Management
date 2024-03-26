<?php
$pageTitle = "Analytics";
include 'header.php';
include 'navbar.php';
include '../db.php';

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

$query = "SELECT COUNT(DISTINCT s.guestID) AS totalUsers, 
                     COUNT(*) AS siteVisits, 
                     SUM(CASE WHEN s.status = 1 THEN 1 ELSE 0 END) AS dropOffSessions,
                     SUM(CASE WHEN s.status = 2 THEN 1 ELSE 0 END) AS completedSessions,
                     SUM(CASE WHEN s.status IN (1, 2) THEN 1 ELSE 0 END) AS totalSessions,
                     DATE(s.timestamp) AS sessionDate,
                     s.device_type, 
                     c.categoryName,
                     p.prodName,
                     s.locationFrom
              FROM session s
              LEFT JOIN product p ON s.prodID = p.prodID
              LEFT JOIN category c ON p.categoryID = c.categoryID";

if ($selectedCategory === 'general') {
    $query .= " WHERE 1=1"; // This ensures that the following conditions can be appended with "AND"
} elseif (!empty($selectedCategory)) {
    $query .= " WHERE p.categoryID = $selectedCategory";
}

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $formattedTimestampStart = date("Y-m-d H:i:s", strtotime($timeFilterStart));
    $formattedTimestampEnd = date("Y-m-d H:i:s", strtotime($timeFilterEnd . ' + 1 day'));
    $query .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

$query .= " GROUP BY c.categoryName, s.device_type, s.locationFrom, s.prodID ";
$result = mysqli_query($conn, $query);

$subquery = "SELECT DISTINCT s.source 
            FROM session s
            LEFT JOIN product p ON s.prodID = p.prodID
            LEFT JOIN category c ON p.categoryID = c.categoryID
            WHERE s.prodID IS NOT NULL ";

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $subquery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

if ($selectedCategory !== 'general') {
    $subquery .= " AND p.categoryID = $selectedCategory";
}

$subquery .= " ORDER BY s.source";
$subresult = mysqli_query($conn, $subquery);

$sources = [];
while ($subrow = mysqli_fetch_assoc($subresult)) {
    $sources[] = $subrow['source'];
}

$totalUsers             = 0;
$totalSessions          = 0;
$siteVisits             = 0;
$completedSessions      = 0;
$dropOffSessions        = 0;
$categoryCounts         = [];
$deviceCounts           = [];
$prodCounts             = [];
$sessionDates           = [];
$completedSessionsData  = [];
$dropOffSessionsData    = [];
$countryCounts          = [];

while ($row = mysqli_fetch_assoc($result)) {
    $totalUsers                 += $row['totalUsers'];
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

    $countryCounts[$country] = ($countryCounts[$country] ?? 0) + $row['totalSessions'];


    // Add condition to remove key-value pair if category name is NULL
    if ($categoryName !== NULL) {
        $categoryCounts[$categoryName] = ($categoryCounts[$categoryName] ?? 0) + $row['totalSessions'];
    }
    $prodCounts[$prodName]          = ($prodCounts[$prodName]  ?? 0) + $row['totalSessions'];

    $deviceCounts[$deviceType] = ($deviceCounts[$deviceType] ?? 0) + $row['totalSessions'];
}


var_dump($siteVisits);
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

$topProductsPerCategory = []; // Associative array to store top 3 products per category

while ($row = mysqli_fetch_assoc($result)) {
    $categoryName = $row['categoryName'];
    $prodName = $row['prodName'];
    $productCount = $row['productCount'];

    // Initialize category array if not already initialized
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

$productChartData = [];

foreach ($topProductsPerCategory as $category => $tops) {
    foreach ($tops as $top => $products) {
        foreach ($products as $product) {
            $productChartData[$category][$top][] = [
                'name' => $product['prodName'],
                'count' => $product['productCount']
            ];
        }
    }
}

// Convert data to JSON for JavaScript
$productChartData = json_encode($productChartData);

?>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<div class="container w-50">
    <form>
=======
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

</div>
<div class="dropdown show">
  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropdown link
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item" href="#">Action</a>
    <a class="dropdown-item" href="#">Another action</a>
    <a class="dropdown-item" href="#">Something else here</a>
  </div>
</div>
<div class="container">
    <form id="filterForm" action="" method="GET">
>>>>>>> Stashed changes
        <div class="row align-items-center my-3">
            <div class="col">
                <label>Select Category:</label>
                <div class="dropdown-content" id="myDropdown">
                    <a href="analytics.php">General</a>
                    <?php foreach ($categories as $categoryID => $categoryName) : ?>
                        <a href="?category=<?php echo $categoryID; ?>"><?php echo $categoryName; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown link
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
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
                <input type="hidden" name="category" id="selected_category" value="<?php echo $selectedCategory; ?>">
                <button type="submit" class="btn btn-dark w-100">FILTER</button>
            </div>
        </div>
    </form>
    <div class="row my-3">
        <div class="col mb-1">
            <div class="card" style="height: 10rem;">
                <div class="card-body text-center">
                    <h5>Site Visits</h5><br>
                    <h2><?php echo $siteVisits; ?></h2>
                </div>
            </div>
        </div>
        <div class="col mb-1">
            <div class="card" style="height: 10rem;">
                <div class="card-body text-center">
                    <h5>Users</h5><br>
                    <h2><?php echo $totalUsers; ?></h2>
                </div>
            </div>
        </div>
        <div class="col mb-1">
            <div class="card" style="height: 10rem;">
                <div class="card-body text-center">
<<<<<<< Updated upstream
                    <h5>Category Session Count</h5><br>
                    <?php foreach ($categoryCounts as $category => $c_count){ ?>
                            <h2><?php echo $category . " : " . $c_count;?></h2>
=======
<div class="admin-page">
    <div class="container">
        <form>
            <div class="row align-items-center my-3">
                <div class="col">
                    <label for="timestamp_start">Start Date:</label>
                    <input type="date" class="form-control" name="timestamp_start" id="timestamp_start" value="<?php echo $timeFilterStart ? $timeFilterStart : date('Y-m-01'); ?>">
                </div>
                <div class="col">
                    <label for="timestamp_end">End Date:</label>
                    <input type="date" class="form-control" name="timestamp_end" id="timestamp_end" value="<?php echo $timeFilterEnd ? $timeFilterEnd : date('Y-m-t'); ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-dark w-100">FILTER</button>
                </div>
            </div>
        </form>
        <div class="row my-3">
            <div class="col mb-1">
                <div class="card" style="height: 10rem;">
                    <div class="card-body text-center">
                        <h5>Total Users</h5>
                        <h2><?php echo $totalUsers; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col mb-1">
                <div class="card" style="height: 10rem;">
                    <div class="card-body text-center">
                        <h5>Total Sessions</h5>
                        <h2><?php echo $totalSessions; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col mb-1">
                <div class="card" style="height: 10rem;">
                    <div class="card-body text-center">
                        <h5>Category Session Count</h5>
                        <?php foreach ($categoryCounts as $category => $c_count) { ?>
                            <h3><?php echo $category . " : " . $c_count; ?></h3>
>>>>>>> Stashed changes
                        <?php
                        }
                    ?>
=======
                    <h5>Sessions</h5><br>
                    <h2><?php echo $totalSessions; ?></h2>
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Device Count</h5>
                    <div id="deviceChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <div class="col">
                        <div id="completedDropOffChart"></div>
                    </div>
                    <div class="col">
                        <h1>Completed:</h1>
                        <h1><?php echo $completedSessions; ?></h1>
                        <h1>Drop off:</h1>
                        <h1><?php echo $dropOffSessions; ?></h1>
                    </div>
                </div>
            </div>
        </div>
<<<<<<< Updated upstream
    </div>
    <div class="row my-3">
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Most Recommended Products</h5>
<<<<<<< Updated upstream
                    <div id="productRecommendedChart"></div>
=======
        <div class="row my-3">
            <div class="col-md-6 mb-1">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center">Device Count</h5>
                        <div id="deviceChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="col">
                            <div id="completedDropOffChart"></div>
                        </div>
                        <div class="col">
                            <p>Completed: <span class="h5"> <?php echo $completedSessions; ?></span></p>
                            <p>Drop off: <span class="h5 "> <?php echo $dropOffSessions; ?></span></p>
                        </div>
                    </div>
>>>>>>> Stashed changes
=======
                    <div id="productRecommendedChartGeneral"></div>
                    <div id="productRecommendedChartCategory"></div>
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Session per Category</h5>
                    <div id="categoryChart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-md-6 mb-1">
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
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Country</h5>
                    <div id="countryChart"></div>
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


        var completedDropOffOptions = {
            chart: {
                height: 350,
                type: 'line',
            },
            series: [{
                name: 'Completed',
                data: <?php echo json_encode($completedSessionsData); ?>
            }, {
                name: 'Drop Off',
                data: <?php echo json_encode($dropOffSessionsData); ?>
            }],
            xaxis: {
                categories: <?php echo json_encode($sessionDates); ?>,
            },
            stroke: {
                curve: 'smooth'
            },
        };

        var completedDropOffChart = new ApexCharts(document.querySelector("#completedDropOffChart"), completedDropOffOptions);
        completedDropOffChart.render();

        // top products per category chart
        var productChartData = <?php echo $productChartData; ?>;

        // Prepare data for chart
        let categories = [];
        let seriesTop1 = [];
        let seriesTop2 = [];
        let seriesTop3 = [];

        for (let category in productChartData) {
            categories.push(category);
            let top1Count = productChartData[category]["Top 1 Products"] ? parseInt(productChartData[category]["Top 1 Products"][0].count) : 0;
            let top2Count = productChartData[category]["Top 2 Products"] ? parseInt(productChartData[category]["Top 2 Products"][0].count) : 0;
            let top3Count = productChartData[category]["Top 3 Products"] ? parseInt(productChartData[category]["Top 3 Products"][0].count) : 0;

            seriesTop1.push(top1Count);
            seriesTop2.push(top2Count);
            seriesTop3.push(top3Count);
        }

        var options = {
            chart: {
                type: 'bar'
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
                                product = productChartData[categories[dataPointIndex]]["Top 1 Products"];
                                break;
                            case 1:
                                product = productChartData[categories[dataPointIndex]]["Top 2 Products"];
                                break;
                            case 2:
                                product = productChartData[categories[dataPointIndex]]["Top 3 Products"];
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

        var chart = new ApexCharts(document.querySelector("#productRecommendedChartGeneral"), options);
        chart.render();

        var countryCounts = <?php echo json_encode($countryCounts); ?>;
        // var totalSessions = Object.values(countryCounts).reduce((acc, val) => acc + val, 0);
        var countryChartData = Object.values(countryCounts).map(count => Number(count));
        var countryLabels = Object.keys(countryCounts);

        var countryChartOptions = {
            chart: {
                type: 'donut',
                height: 240,
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