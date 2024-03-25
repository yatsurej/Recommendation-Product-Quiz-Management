<?php
<<<<<<< Updated upstream
    include '../header.php';
    include 'navbar.php';
    $pageTitle = "Analytics";
?>

<div class="container w-50 text-center">
    <h1 class="mt-5">Analytics. Hello World!</h1>
</div>
=======
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
$query = "SELECT COUNT(DISTINCT s.guestID) AS totalUsers, 
                     COUNT(*) AS totalSessions, 
                     SUM(CASE WHEN s.isFinished = 0 THEN 1 ELSE 0 END) AS dropOffSessions,
                     SUM(CASE WHEN s.isFinished = 1 THEN 1 ELSE 0 END) AS completedSessions,
                     DATE(s.timestamp) AS sessionDate,
                     s.device_type, 
                     c.categoryName,
                     p.prodName,
                     s.locationFrom
              FROM session s
              LEFT JOIN product p ON s.prodID = p.prodID
              LEFT JOIN category c ON p.categoryID = c.categoryID
              WHERE s.prodID IS NOT NULL ";

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $formattedTimestampStart = date("Y-m-d H:i:s", strtotime($timeFilterStart));
    $formattedTimestampEnd = date("Y-m-d H:i:s", strtotime($timeFilterEnd . ' + 1 day'));
    $query .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

$query .= " GROUP BY c.categoryName";
$result = mysqli_query($conn, $query);

$subquery = "SELECT DISTINCT s.source 
                FROM session s
                WHERE s.prodID IS NOT NULL ";

if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
    $subquery .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
}

$subquery .= " ORDER BY s.source";
$subresult = mysqli_query($conn, $subquery);

$sources = [];
while ($subrow = mysqli_fetch_assoc($subresult)) {
    $sources[] = $subrow['source'];
}

$totalUsers             = 0;
$totalSessions          = 0;
$completedSessions      = 0;
$dropOffSessions        = 0;
$categoryCounts         = [];
$deviceCounts           = [];
$productCounts          = [];
$sessionDates           = [];
$completedSessionsData  = [];
$dropOffSessionsData    = [];
$countryCounts          = [];

while ($row = mysqli_fetch_assoc($result)) {
    $totalUsers                 += $row['totalUsers'];
    $totalSessions              += $row['totalSessions'];
    $categoryName               = $row['categoryName'];
    $sessionDates[]             = $row['sessionDate'];
    $completedSessions          += $row['completedSessions'];
    $dropOffSessions            += $row['dropOffSessions'];
    $completedSessionsData[]    = $row['completedSessions'];
    $dropOffSessionsData[]      = $row['dropOffSessions'];
    $deviceType                 = $row['device_type'];
    $prodName                   = $row['prodName'];
    $country                    = $row['locationFrom'];

    $countryCounts[$country]        = $row['totalSessions'];
    $categoryCounts[$categoryName]  = $row['totalSessions'];
    $prodCounts[$prodName]          = $row['totalSessions'];

    if (isset($deviceCounts[$deviceType])) {
        $deviceCounts[$deviceType] = $row['totalSessions'];
    } else {
        $deviceCounts[$deviceType] = $row['totalSessions'];
    }
}
?>
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
                    <button type="submit" class="btn btn-primary w-100" style="margin-top: 30px;">FILTER</button>
                </div>
            </div>
        </form>
        <div class="row my-3">
            <div class="col mb-1">
                <div class="card" style="height: 10rem;">
                    <div class="card-body text-center">
                        <h5>Total Users</h5><br>
                        <h2><?php echo $totalUsers; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col mb-1">
                <div class="card" style="height: 10rem;">
                    <div class="card-body text-center">
                        <h5>Total Sessions</h5><br>
                        <h2><?php echo $totalSessions; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col mb-1">
                <div class="card" style="height: 10rem;">
                    <div class="card-body text-center">
                        <h5>Category Session Count</h5><br>
                        <?php foreach ($categoryCounts as $category => $c_count) { ?>
                            <h2><?php echo $category . " : " . $c_count; ?></h2>
                        <?php
                        }
                        ?>
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
        </div>
        <div class="row my-3">
            <div class="col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Most Recommended Products</h5>
                        <div id="productRecommendedChart"></div>
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
                                                $countQuery = "SELECT COUNT(*) AS count FROM session WHERE source = '$source'";
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
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
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
        var totalSessions = Object.values(deviceCounts).reduce((acc, val) => acc + val, 0);
        var deviceChartData = Object.values(deviceCounts).map(count => (count / totalSessions) * 100);
        var deviceLabels = Object.keys(deviceCounts);

        var deviceChartOptions = {
            chart: {
                type: 'pie',
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
        var totalSessions = Object.values(categoryCounts).reduce((acc, val) => acc + val, 0);
        var categoryChartData = Object.values(categoryCounts).map(count => (count / totalSessions) * 100);
        var categoryLabels = Object.keys(categoryCounts);

        var categoryChartOptions = {
            chart: {
                type: 'pie',
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

        var prodCounts = <?php echo json_encode($prodCounts); ?>;
        var products = Object.keys(prodCounts);
        var counts = Object.values(prodCounts);

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

        var productRecommendedChart = new ApexCharts(document.querySelector("#productRecommendedChart"), productRecommendedOptions);
        productRecommendedChart.render();

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
        };

        var completedDropOffChart = new ApexCharts(document.querySelector("#completedDropOffChart"), completedDropOffOptions);
        completedDropOffChart.render();

        var countryCounts = <?php echo json_encode($countryCounts); ?>;
        var totalSessions = Object.values(countryCounts).reduce((acc, val) => acc + val, 0);
        var countryChartData = Object.values(countryCounts).map(count => (count / totalSessions) * 100);
        var countryLabels = Object.keys(countryCounts);

        var countryChartOptions = {
            chart: {
                type: 'pie',
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
>>>>>>> Stashed changes
