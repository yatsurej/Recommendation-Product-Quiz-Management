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

    $query = "SELECT COUNT(*) AS siteVisits, 
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

    $query .= " GROUP BY c.categoryName, s.device_type, s.guestID, s.locationFrom, s.prodID"; 
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

    while($row = mysqli_fetch_assoc($result)){
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
        
        // Add condition to remove key-value pair if category name is NULL
        if ($categoryName !== NULL) {
            $categoryCounts[$categoryName] = ($categoryCounts[$categoryName] ?? 0) + $row['totalSessions'];
        }
        if ($prodName !== NULL) {
            $prodCounts[$prodName] = ($prodCounts[$prodName] ?? 0) + $row['totalSessions'];
        }
        $deviceCounts[$deviceType]      = ($deviceCounts[$deviceType] ?? 0) + $row['totalSessions'];
        $countryCounts[$country] = ($countryCounts[$country] ?? 0) + $row['totalSessions'];
    }
    arsort($prodCounts);

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

    echo "Debugging: Total Users Query: $totalUsersQuery"; // Debugging statement

    $totalUsersResult = mysqli_query($conn, $totalUsersQuery);
    $totalUsersRow = mysqli_fetch_assoc($totalUsersResult);
    $Users = $totalUsersRow['totalUsers'];

    echo " USERS: ".$Users;

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

    // Convert data to JSON for JavaScript
    $generalProductChartData = json_encode($generalProductChartData);

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

    // hideIfGeneral: True when the selected category is 'general' or no category is selected
    $hideIfGeneral = !isset($selectedCategory) || $selectedCategory === 'general';

    // hideIfCategory: True when a specific category is selected other than 'general'
    $hideIfCategory = isset($selectedCategory) && $selectedCategory !== 'general';
?>
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
    <nav>
        <ul>
         <li><a href="analytics.php">General</a></li>
            <?php foreach ($categories as $categoryID => $categoryName): ?>
                <li>
                    <a href="?category=<?php echo $categoryID; ?>"><?php echo $categoryName; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>  
</div>
<div class="container w-50">
<form id="filterForm" action="" method="GET">
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
                <input type="hidden" name="category" id="selected_category" value="<?php echo $selectedCategory; ?>">
                <button type="submit" class="btn btn-primary w-100" style="margin-top: 30px;">FILTER</button>
            </div>
        </div>
    </form>
    <div class="row my-3">
        <?php if (!$hideIfCategory): ?>
        <div class="col mb-1">
            <div class="card" style="height: 10rem;">
                <div class="card-body text-center">
                    <h5>Site Visits</h5><br>
                    <h2><?php echo $siteVisits;?></h2>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="col mb-1">
            <div class="card" style="height: 10rem;">
                <div class="card-body text-center">
                    <h5>Users</h5><br>
                    <h2><?php echo $Users;?></h2>
                </div>
            </div>
        </div>
        <div class="col mb-1">
            <div class="card" style="height: 10rem;">
                <div class="card-body text-center">
                    <h5>Sessions</h5><br>
                    <h2><?php echo $totalSessions;?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-md-6 mb-1">
            <div class="card" style="height: 20rem;">
                <div class="card-body">
                    <h5 class="card-title text-center">Device Count</h5>
                    <div id="deviceChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-1">
            <div class="card" style="height: 20rem;">
                <div class="card-body">
                    <h5 class="card-title text-center">Completed & Drop Off Sessions</h5>
                    <div id="completedDropOffChart"></div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row my-3">
        <div class="col-md-6 mb-1">
            <div class="card" style="height: 20rem;">
                <div class="card-body">
                    <h5 class="card-title text-center">Most Recommended Products</h5>
                    <?php if (!$hideIfCategory): ?><div id="productRecommendedChartGeneral"></div><?php endif; ?>
                    <?php if (!$hideIfGeneral): ?><div id="productRecommendedChartCategory"></div><?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (!$hideIfCategory): ?>
        <div class="col-md-6 mb-1">
            <div class="card" style="height: 20rem;">
                <div class="card-body">
                    <h5 class="card-title text-center">Session per Category</h5>
                    <div id="categoryChart"></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="row my-3"> 
        <div class="card">
            <div id = "questionAnswerChart"></div>
        </div>
     
    </div>
    <div class="row my-3">
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Source</h5>
                    <div class="text-center">
                        <table  class="table text-center">
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

        <?php $combinedData = array($completedSessions, $dropOffSessions);?>
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
        var generalProductChartData = <?php echo $generalProductChartData; ?>;

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
                    formatter: function(value, { series, seriesIndex, dataPointIndex }) {
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

        // For Selected Category, Most Recommended Products
        var prodCounts = <?php echo json_encode($prodCounts); ?>;
        var products = Object.keys(prodCounts); // Select only the first 5 products
        var counts = Object.values(prodCounts).map(count => Number(count)); // Corresponding counts for the first 5 products

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
                categories: products,
                labels: {
                    show: true,
                    rotate: -45,
                    rotateAlways: true,
                    trim: false,
                }
            }
        };

        var productRecommendedChart = new ApexCharts(document.querySelector("#productRecommendedChartCategory"), productRecommendedOptions);
        productRecommendedChart.render();


        <?php if (!$hideIfGeneral): ?>
        var questionAnswerData = <?php echo json_encode($questionAnswerData); ?>;
        console.log(questionAnswerData);
        for (const question in questionAnswerData) {
                if (questionAnswerData.hasOwnProperty(question)) {
                drawColumnChart(question, questionAnswerData[question]);
                }
            }

        function drawColumnChart(question, questionData) {
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
                        return { ...answerData, answer: lines }; // Use multi-line array as label
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

        var countryCounts = <?php echo json_encode($countryCounts); ?>;
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