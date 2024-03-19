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

    $mainQuestionQuery = "SELECT pq.pqContent, a.answerContent
                          FROM parent_question pq
                          LEFT JOIN question_answer qa ON qa.pqID = pq.pqID
                          LEFT JOIN answer a ON a.answerID = qa.answerID
                          LEFT JOIN session_answers sa ON sa.answerID = a.answerID
                          WHERE pq.pqID = qa.pqID
                          ORDER BY pq.pqID";
    $mainQuestionResult = mysqli_query($conn, $mainQuestionQuery);

    while($row = mysqli_fetch_assoc($mainQuestionResult)){
        $pqContent = $row['pqContent'];
        $answerContent = $row['answerContent'];
    }

    // Fetch category details from the category table
    $categoryQuery = "SELECT categoryID, categoryName FROM category";
    $categoryResult = $conn->query($categoryQuery);

    // Check if the category query was successful
    if ($categoryResult === false) {
        die("Error in category query: " . $conn->error);
    }

    // Fetch category details into an associative array
    $categories = array();
    while ($categoryRow = $categoryResult->fetch_assoc()) {
        $categories[$categoryRow['categoryID']] = $categoryRow['categoryName'];
    }

    include 'getDataForAllCategories.php';

    $conn->close();
?>


<style>
    /* Global styles with more specific selectors */
    table.questions {
        width: 100%;
        margin-bottom: 2rem;
    }

    table.questions th,
    table.questions td {
        padding: 10px;
        text-align: left;
    }

    /* Adjust the widths for each column */
    table.questions td:nth-child(1) {
        width: 70%;
    }

    table.questions td:nth-child(2),
    table.questions td:nth-child(3) {
        width: 15%;
    }

    /* Custom class for excluding global styles */
    .totalQuiz{
       
    }
    .totalQuiz th,
    .totalQuiz th {
        padding: 10px;
        text-align: left;
    }

    .totalQuiz .label {
        font-size: 10px;
    }
</style>
<div class="container mx-auto w-full md:w-4/5 lg:w-4/5 p-4">
        <div class="flex justify-between items-center p-4 bg-gray-800 text-white">
            <label for="categoryDropdown" class="text-sm font-bold">Select Category:</label>
            <nav>
                <ul class="flex">
                    <?php foreach ($categories as $categoryID => $categoryName): ?>
                        <li class="mr-4">
                            <a href="#" onclick="displaySelectedCategory(<?= $categoryID ?>)" class="text-white"><?= $categoryName ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>  
        </div>



            <div class="container mx-auto w-full md:w-full lg:w-full p-4 flex items-center space-x-4">
                <div class="flex-1">
                    <label for="startDate" class="block text-gray-700 text-sm font-bold mb-2">Start Date:</label>
                    <input type="date" id="startDate" class="w-full border rounded p-2">
                </div>

                <div class="flex-1">
                    <label for="endDate" class="block text-gray-700 text-sm font-bold mb-2">End Date:</label>
                    <input type="date" id="endDate" class="w-full border rounded p-2">
                </div>

                <div class="flex-1">
                    <label for="dateRange" class="block text-gray-700 text-sm font-bold mb-2">Select Date Range</label>
                    <select id="dateRange" class="w-full border rounded p-2">
                        <option value="allTime">All Time</option> <!-- Initial "none" value -->
                        <option value="7">Last 7 days</option>
                        <option value="14">Last 14 days</option>
                        <option value="30">Last 30 days</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="thisWeek">This week (starts Sunday)</option>
                        <option value="lastWeek">Last week (starts Sunday)</option>
                        <option value="thisMonth">This month</option>
                        <option value="lastMonth">Last month</option>
                        <option value="thisQuarter">This quarter</option>
                        <option value="lastQuarter">Last quarter</option>
                        <option value="thisYear">This year</option>
                        <option value="lastYear">Last year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                <button onclick="applyFilter()" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Apply</button>
                <button onclick="clearFilters()" class="bg-gray-300 text-gray-700 px-4 py-2 mt-4 rounded">Clear</button>
            </div>

 



    <div id="totalQuizSection" class="mx-auto text-center">
        <div class = "mx-auto text-center">
            <h4 class="text-lg font-bold mb-2">Total Quiz Completed</h4>
            <table class="totalQuiz mx-auto mb-2">
                <tr>
                    <td class="label text-center pr-4 pb-2  border-none">Event Count</td>
                    <td class="label text-center pb-2 border-none">Total Users</td>
                </tr>
                <tr>
                    <td class="border-none pr-4 text-center"><?= $eventCount ?></td>
                    <td class="border-none text-center"><?= $totalUsers ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div id="siteVisits" class="mx-auto text-center">
        <div class = "mx-auto text-center">
            <h4 class="text-lg font-bold mb-2">Site Visits</h4>
            <table class="totalQuiz mx-auto mb-2">
                <tr>
                    <td class="border-none pr-4 text-center"><?= $siteVisitCount ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="flex justify-center">
            
                <div id="totalSessionsChart"></div>
           
            <!-- Display the doughnut chart for device types -->
          
                <div id="deviceTypeChart"></div>
    </div>

        <div id="recommendedProductsChart"></div>
        
        <div id="toggleButton" class="flex hidden">
            <button id="toggleViewButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Toggle View
            </button>

            <button id="exportButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Export Data</button>
        </div>

        
        <div id="selectedCategoryQuizData"></div>

        <div class="flex justify-center">
            <div id="selectedCategoryDeviceData" class = "p-2 bg-white rounded shadow-md mb-2"></div>
            <div id="selectedCategoryMostRecommendedData" class = "p-2 bg-white rounded shadow-md mb-2"></div>
        </div>
   
        

    <div class="flex flex-row">
        <div id="selectedCategoryTables" class="flex-1 container mx-2"></div>

        <div id="selectedCategoryCharts" class="flex-1 container mx-2"></div>
    </div>
    <div class="" id="selectedCategorySources"></div>
    <div class="" id="selectedCategoryOutbound"></div>
</div>
</div>



<!-- Include Google Charts API -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
     document.addEventListener("DOMContentLoaded", function () {
        var tablesContainer = document.getElementById("selectedCategoryTables");
        var chartsContainer = document.getElementById("selectedCategoryCharts");
        var toggleButton = document.getElementById("toggleViewButton");

        // Hide tables initially
        tablesContainer.style.display = "none";

        toggleButton.addEventListener("click", function () {
            if (tablesContainer.style.display !== "none") {
                tablesContainer.style.display = "none";
                chartsContainer.style.display = "block";
            } else {
                tablesContainer.style.display = "block";
                chartsContainer.style.display = "none";
            }
        });
    });
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});



    function drawCharts() {
        // Draw doughnut chart for total sessions
        drawTotalSessionsChart();

        // Draw doughnut chart for device types
        drawDeviceTypeChart();

        drawRecommendedProductsChart();
    }

    function drawTotalSessionsChartss() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Category');
        data.addColumn('number', 'Total Sessions');
        
        // Add data to the chart
        <?php foreach ($totalSessionsData as $data): ?>
            data.addRow(['<?= $data['Category'] ?>', <?= $data['Total Sessions'] ?>]);
        <?php endforeach; ?>

        // Set chart options
        var options = {
            chartArea: {
                width: '50%', // adjust width (percentage or absolute value)
                height: '50%' // adjust height (percentage or absolute value)
            },
            title: 'Total Sessions for Each Category',
            pieHole: 0.4, // To make it a doughnut chart
            pieSliceText: 'value', // Display actual values
        };

        // Instantiate and draw the chart.
        var chart = new google.visualization.PieChart(document.getElementById('totalSessionsChart'));
        chart.draw(data, options);
    }

    function drawTotalSessionsChart(totalSessionsData) {
    // Convert data to the format expected by Google Charts
    const chartData = [['Category', 'Total Sessions']];
    totalSessionsData.forEach(item => {
        // Log the parsed values for debugging
        console.log(`Category: ${item.Category}, Total Sessions: ${parseInt(item['Total Sessions'])}`);
        chartData.push([item.Category, parseInt(item['Total Sessions'])]);
    });

    // Load the Google Charts visualization library
    google.charts.load('current', {'packages':['corechart']});
    
    // Set a callback to run when the Google Charts library is loaded
    google.charts.setOnLoadCallback(() => {
        // Create the data table
        const data = google.visualization.arrayToDataTable(chartData);

        // Set chart options
        var options = {
            chartArea: {
                width: '50%', // adjust width (percentage or absolute value)
                height: '50%' // adjust height (percentage or absolute value)
            },
            title: 'Total Sessions for Each Category',
            pieHole: 0.4, // To make it a doughnut chart
            pieSliceText: 'value', // Display actual values
        };

        // Instantiate and draw the chart
        const chart = new google.visualization.PieChart(document.getElementById('totalSessionsChart'));
        chart.draw(data, options);
    });
}

function drawDeviceTypeChart(deviceTypesData) {
    // Convert data to the format expected by Google Charts
    const chartData = [['Device Type', 'Count']];
    for (const deviceType in deviceTypesData) {
        chartData.push([deviceType, parseInt(deviceTypesData[deviceType])]);
    }

    // Load the Google Charts visualization library
    google.charts.load('current', {'packages':['corechart']});
    
    // Set a callback to run when the Google Charts library is loaded
    google.charts.setOnLoadCallback(() => {
        // Create the data table
        const data = google.visualization.arrayToDataTable(chartData);

        // Set chart options
        var options = {
            chartArea: {
                width: '50%', // adjust width (percentage or absolute value)
                height: '50%' // adjust height (percentage or absolute value)
            },
            title: 'Total Sessions for Each Category',
            pieHole: 0.4, // To make it a doughnut chart
            pieSliceText: 'value', // Display actual values
        };

        // Instantiate and draw the chart
        const chart = new google.visualization.PieChart(document.getElementById('deviceTypeChart'));
        chart.draw(data, options);
    });
}

        function drawRecommendedProductsChart(recommendedProductsData) {
    // Load the Google Charts visualization library
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Charts library is loaded
    google.charts.setOnLoadCallback(() => {
        // Create the data table for recommended products
        const data = new google.visualization.DataTable();
        data.addColumn('string', 'Product');
        data.addColumn('number', 'Recommendation Count');

        // Add data to the recommended products chart
        recommendedProductsData.forEach(product => {
            data.addRow([product.Product, parseInt(product.RecommendationCount)]);
        });

        // Set chart options for recommended products
        const options = {
            chartArea: {
                width: '50%',
                height: '50%'
            },
            title: 'Recommended Products',
            vAxis: {
                title: '',
                minValue: 0,
                textStyle: {
            fontSize: 10 // Set the font size for the horizontal axis labels
        }
            },
            hAxis: {
                title: '',
                textStyle: {
            fontSize: 10 // Set the font size for the horizontal axis labels
        }
            },
            legend: 'none' // Set legend to none to remove it
        };

        // Instantiate and draw the recommended products chart
        const chart = new google.visualization.ColumnChart(document.getElementById('recommendedProductsChart'));
        chart.draw(data, options);
    });
}


            
            var chartData = null;
            // Add event listener for the export button outside the function
                document.getElementById('exportButton').addEventListener('click', function () {
                    exportChartDataToCSV(chartData);
                    });
                    // Cache frequently accessed DOM elements
        const elements = {
            selectedCategoryCharts: document.getElementById('selectedCategoryCharts'),
            startDate: document.getElementById('startDate'),
            endDate: document.getElementById('endDate'),
            dateRange: document.getElementById('dateRange')
        };

        function fetchDataForAllCategories(startDate, endDate) {
                
                console.log(startDate);
                console.log(endDate);
                const allCategoriesURL = `getDataForAllCategories.php?startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}`;

                // Make a request to fetch data for all categories
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            console.log(this.responseText);
                            const responseData = JSON.parse(this.responseText);
                            drawTotalSessionsChart(responseData.TotalSessions);
                            drawDeviceTypeChart(responseData.DeviceTypes);
                            drawRecommendedProductsChart(responseData.RecommendedProducts);
                        } else {
                            console.error(`Error in fetching data for all categories: ${this.status}`);
                            // Add error handling logic here...
                        }
                    }
                };
                xhttp.open("GET", allCategoriesURL, true);
                xhttp.send();
            }
            window.onload = function() {
                // Run displaySelectedCategory with null categoryID on page load
                displaySelectedCategory(null);
            };
        let selectedCategoryID = null;
        function displaySelectedCategory(categoryID) {
                document.getElementById('selectedCategoryCharts').innerHTML = '';

                 // Validate and sanitize categoryID, startDate, and endDate
                // Add your validation logic here...
                document.getElementById('startDate').value = '';
                document.getElementById('endDate').value = '';
                document.getElementById('dateRange').value = 'allTime';
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                // Check if categoryID is null
                if (categoryID === null) {
                    // Fetch data for all categories
                    console.log("no category selected");
                    selectedCategoryID = null;
                    fetchDataForAllCategories(startDate, endDate);
                    return; // Exit the function
                }
                // Define constants for element IDs
                const elementIDs = ['totalSessionsChart', 'deviceTypeChart', 'totalQuizSection', 'recommendedProductsChart', 'siteVisits'];

                // Hide or show elements based on category selection
                elementIDs.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.style.display = (categoryID !== "") ? 'none' : 'block';
                    }
                });

                const toggleViewButton = document.getElementById('toggleButton');
                const exportButton = document.getElementById('exportButton');

                if (toggleViewButton) {
                        toggleViewButton.style.display = (categoryID !== "") ? 'block' : 'none';
                    }

               

                // Define URLs for data retrieval
                const urls = {
                    'tables': "getTablesForCategory.php",
                    'charts': "getChartsForCategory.php",
                    'quizCompleted': "getQuizDataForCategory.php",
                    'deviceTypeData': "getDeviceDataForCategory.php",
                    'mostRecommendedProducts': "getMostRecommendedForCategory.php",
                    'sources': "getSourcesForCategory.php",
                    'outbound': "getOutboundForCategory.php",
                    'conditionalQuestions': "getConditionalQuestionsData.php",
                    'bonusQuestions': "getBonusQuestionsData.php"
                };

                // Make asynchronous requests for each data type
                Object.keys(urls).forEach(key => {
                    const url = `${urls[key]}?categoryID=${categoryID}&startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}`;
                    const xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4) {
                            if (this.status == 200) {
                                switch (key) {
                                    case 'tables':
                                        document.getElementById('selectedCategoryTables').innerHTML = this.responseText;
                                        break;
                                    case 'charts':
                                        const chartData = JSON.parse(this.responseText);
                                        for (const question in chartData) {
                                            if (chartData.hasOwnProperty(question)) {
                                                drawColumnChart(question, chartData[question]);
                                            }
                                        }
                                        break;
                                    case 'quizCompleted':
                                        document.getElementById('selectedCategoryQuizData').innerHTML = this.responseText;
                                        break;
                                    case 'deviceTypeData':
                                        const deviceTypeData = JSON.parse(this.responseText);
                                        drawSelectedCategoryDeviceTypeChart(deviceTypeData);
                                        break;
                                    case 'mostRecommendedProducts':
                                        const mostRecommendedProductData = JSON.parse(this.responseText);
                                        drawMostRecommendedProducts(mostRecommendedProductData);
                                        break;
                                    case 'sources':
                                        const sources = JSON.parse(this.responseText);
                                        createSourcesTable(sources);
                                        break;
                                    case 'outbound':
                                        const outbound = JSON.parse(this.responseText);
                                        createOutboundTable(outbound);
                                        break;
                                    case 'conditionalQuestions':
                                        const conditionalQuestionData = JSON.parse(this.responseText);
                                        for (const question in conditionalQuestionData) {
                                            if (conditionalQuestionData.hasOwnProperty(question)) {
                                                drawConditionalChart(question, conditionalQuestionData[question]);
                                            }
                                        }
                                        break;
                                    case 'bonusQuestions':
                                        const bonusQuestionData = JSON.parse(this.responseText);
                                        for (const question in bonusQuestionData) {
                                            if (bonusQuestionData.hasOwnProperty(question)) {
                                                drawBonusChart(question, bonusQuestionData[question]);
                                            }
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            } else {
                                console.error(`Error in fetching ${key}: ${this.status}`);
                                // Add error handling logic here...
                            }
                        }
                        selectedCategoryID = categoryID;
                    };
                    xhttp.open("GET", url, true);
                    xhttp.send();
                });

            }
            function applyFilter() {
            // Define constants for element IDs
            document.getElementById('selectedCategoryCharts').innerHTML = '';
            const elementIDs = ['startDate', 'endDate', 'dateRange'];

            // Get references to DOM elements
            const elements = {};
            elementIDs.forEach(id => {
                elements[id] = document.getElementById(id);
            });

            // Get start and end dates based on the selected date range
            let startDate, endDate;
            if (elements.startDate.value && elements.endDate.value) {
                // If custom date range is selected, use the entered dates
                startDate = new Date(elements.startDate.value);
                endDate = new Date(elements.endDate.value);
                elements.dateRange.value = 'custom';
            } else {
                // If a predefined date range is selected, calculate start and end dates
                const selectedDateRange = elements.dateRange.value;
                switch (selectedDateRange) {
                    default:
                        startDate = new Date(elements.startDate.value);
                        endDate = new Date(elements.endDate.value);
                        break;
                    case 'allTime':
                        startDate = new Date('2000-01-01');
                        endDate = new Date();
                        break;
                    case '7':
                        startDate = new Date();
                        startDate.setDate(startDate.getDate() - 7);
                        endDate = new Date();
                        break;
                    case '14':
                        startDate = new Date();
                        startDate.setDate(startDate.getDate() - 14);
                        endDate = new Date();
                        break;
                    case '30':
                        startDate = new Date();
                        startDate.setDate(startDate.getDate() - 30);
                        endDate = new Date();
                        break;
                    case 'today':
                        startDate = new Date();
                        endDate = new Date();
                        break;
                    case 'yesterday':
                        startDate = new Date();
                        startDate.setDate(startDate.getDate() - 1);
                        endDate = new Date(startDate);
                        break;
                    case 'thisWeek':
                        startDate = new Date();
                        startDate.setDate(startDate.getDate() - startDate.getDay());
                        endDate = new Date();
                        break;
                    case 'lastWeek':
                        startDate = new Date();
                        startDate.setDate(startDate.getDate() - startDate.getDay() - 7);
                        endDate = new Date();
                        endDate.setDate(endDate.getDate() - endDate.getDay() - 1);
                        break;
                    case 'thisMonth':
                        startDate = new Date();
                        startDate.setDate(1);
                        endDate = new Date();
                        break;
                    case 'lastMonth':
                        startDate = new Date();
                        startDate.setMonth(startDate.getMonth() - 1, 1);
                        endDate = new Date(startDate);
                        endDate.setMonth(endDate.getMonth() + 1, 0);
                        break;
                    case 'thisQuarter':
                        startDate = new Date();
                        startDate.setMonth(Math.floor(startDate.getMonth() / 3) * 3, 1);
                        endDate = new Date();
                        break;
                    case 'lastQuarter':
                        startDate = new Date();
                        startDate.setMonth(Math.floor(startDate.getMonth() / 3) * 3 - 3, 1);
                        endDate = new Date(startDate);
                        endDate.setMonth(endDate.getMonth() + 3, 0);
                        break;
                    case 'thisYear':
                        startDate = new Date();
                        startDate.setMonth(0, 1);
                        endDate = new Date();
                        break;
                    case 'lastYear':
                        startDate = new Date();
                        startDate.setFullYear(startDate.getFullYear() - 1, 0, 1);
                        endDate = new Date(startDate);
                        endDate.setFullYear(endDate.getFullYear(), 11, 31);
                        break;
                }
                // Update start and end date inputs if not 'allTime'
                if (selectedDateRange !== 'allTime') {
                    elements.startDate.valueAsDate = startDate;
                    elements.endDate.valueAsDate = endDate;
                }
            }

            console.log('Calculated Start Date:', startDate.toISOString().split('T')[0]);
            console.log('Calculated End Date:', endDate.toISOString().split('T')[0]);
            // Check if no category is selected

                if (selectedCategoryID === null) {
                
                    fetchDataForAllCategories(startDate, endDate);
                    return; // Exit the function
                }

            const urls = {
                'tables': "getTablesForCategory.php",
                'charts': "getChartsForCategory.php",
                'quizData': "getQuizDataForCategory.php",
                'deviceTypeData': "getDeviceDataForCategory.php",
                'mostRecommendedProducts': "getMostRecommendedForCategory.php",
                'sources': "getSourcesForCategory.php",
                'outbound': "getOutboundForCategory.php",
                'conditionalQuestions': "getConditionalQuestionsData.php",
                'bonusQuestions': "getBonusQuestionsData.php"
            };

            // Make asynchronous requests for each data type
            Object.keys(urls).forEach(key => {
                const url = `${urls[key]}?categoryID=${selectedCategoryID}&startDate=${encodeURIComponent(startDate.toISOString().split('T')[0])}&endDate=${encodeURIComponent(endDate.toISOString().split('T')[0])}`;
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            switch (key) {
                                case 'tables':
                                    document.getElementById('selectedCategoryTables').innerHTML = this.responseText;
                                    break;
                                case 'charts':
                                    const chartData = JSON.parse(this.responseText);
                                    for (const question in chartData) {
                                        if (chartData.hasOwnProperty(question)) {
                                            drawColumnChart(question, chartData[question]);
                                        }
                                    }
                                    break;
                                case 'quizData':
                                    document.getElementById('selectedCategoryQuizData').innerHTML = this.responseText;
                                    break;
                                case 'deviceTypeData':
                                    const deviceTypeData = JSON.parse(this.responseText);
                                    drawSelectedCategoryDeviceTypeChart(deviceTypeData);
                                    break;
                                case 'mostRecommendedProducts':
                                    const mostRecommendedProductData = JSON.parse(this.responseText);
                                    drawMostRecommendedProducts(mostRecommendedProductData);
                                    break;
                                case 'sources':
                                    const sources = JSON.parse(this.responseText);
                                    createSourcesTable(sources);
                                    break;
                                case 'outbound':
                                    const outbound = JSON.parse(this.responseText);
                                    createOutboundTable(outbound);
                                    break;
                                case 'conditionalQuestions':
                                    const conditionalQuestionData = JSON.parse(this.responseText);
                                    for (const question in conditionalQuestionData) {
                                        if (conditionalQuestionData.hasOwnProperty(question)) {
                                            drawConditionalChart(question, conditionalQuestionData[question]);
                                        }
                                    }
                                    break;
                                case 'bonusQuestions':
                                    const bonusQuestionData = JSON.parse(this.responseText);
                                    for (const question in bonusQuestionData) {
                                        if (bonusQuestionData.hasOwnProperty(question)) {
                                            drawBonusChart(question, bonusQuestionData[question]);
                                        }
                                    }
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            console.error(`Error in fetching ${key}: ${this.status}`);
                            // Add error handling logic here...
                        }
                    }
                };
                xhttp.open("GET", url, true);
                xhttp.send();
            });
        }

    function clearFilters() {
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('dateRange').value = 'allTime';

        applyFilter();
    }
    function formatDate(date) {
    return date.toISOString().split('T')[0];
}

        // Function to draw the Google Column Chart for a specific question
            function drawColumnChart(question, questionData) {
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Answer');
                    data.addColumn('number', 'Click Count');
                    data.addColumn('number', 'Total Sessions');

                    // Add data rows
                    questionData.forEach(function (answerData) {
                        data.addRow([answerData.answer, answerData.clickCount, answerData.totalSessions]);
                    });

                    var options = {
                        title: question,
                        legend: { position: 'none' }, // Remove the legend
                        colors: ['#4285F4', '#3F51B5'], // Use shades of blue for the columns
                        height: 350, // Adjust the height as needed
                            titleTextStyle: {
                                fontSize: 16, // Adjust the title font size
                            },
                            vAxis: {
                                format: '0', // Display vertical axis values as integers
                            },
                    };
                    // Create a new div element for each chart
                    var chartContainer = document.createElement('div');
                    var chartContainerId = 'chartContainer_' + question; // Unique ID for each chart container
                    chartContainer.id = chartContainerId;
                    
                    // Add Tailwind CSS classes for styling
                    chartContainer.classList.add('p-4', 'bg-white', 'rounded', 'shadow-md', 'mb-4');

                    document.getElementById('selectedCategoryCharts').appendChild(chartContainer);

                    var chart = new google.visualization.ColumnChart(chartContainer);
                    chart.draw(data, options);
                }

            }
             // Function to draw the Google Column Chart for a specific question
             function drawConditionalChart(question, questionData) {
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Answer');
                    data.addColumn('number', 'Click Count');
                    data.addColumn('number', 'Total Sessions');

                    // Add data rows
                    questionData.forEach(function (answerData) {
                        data.addRow([answerData.answer, answerData.clickCount, answerData.totalSessions]);
                    });

                    var options = {
                        title: question,
                        legend: { position: 'none' }, // Remove the legend
                        colors: ['#4285F4', '#3F51B5'], // Use shades of blue for the columns
                        height: 350, // Adjust the height as needed
                            titleTextStyle: {
                                fontSize: 16, // Adjust the title font size
                            },
                            vAxis: {
                                format: '0', // Display vertical axis values as integers
                            },
                    };
                    // Create a new div element for each chart
                    var chartContainer = document.createElement('div');
                    var chartContainerId = 'chartContainer_' + question; // Unique ID for each chart container
                    chartContainer.id = chartContainerId;
                    
                    // Add Tailwind CSS classes for styling
                    chartContainer.classList.add('p-4', 'bg-white', 'rounded', 'shadow-md', 'mb-4');

                    document.getElementById('selectedCategoryCharts').appendChild(chartContainer);

                    var chart = new google.visualization.ColumnChart(chartContainer);
                    chart.draw(data, options);
                }

            }

            // Function to draw the Google Column Chart for a specific question
            function drawBonusChart(question, questionData) {
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Answer');
                    data.addColumn('number', 'Click Count');
                    data.addColumn('number', 'Total Sessions');

                    // Add data rows
                    questionData.forEach(function (answerData) {
                        data.addRow([answerData.answer, answerData.clickCount, answerData.totalSessions]);
                    });

                    var options = {
                        title: question,
                        legend: { position: 'none' }, // Remove the legend
                        colors: ['#4285F4', '#3F51B5'], // Use shades of blue for the columns
                        height: 350, // Adjust the height as needed
                            titleTextStyle: {
                                fontSize: 16, // Adjust the title font size
                            },
                            vAxis: {
                                format: '0', // Display vertical axis values as integers
                            },
                    };
                    // Create a new div element for each chart
                    var chartContainer = document.createElement('div');
                    var chartContainerId = 'chartContainer_' + question; // Unique ID for each chart container
                    chartContainer.id = chartContainerId;
                    
                    // Add Tailwind CSS classes for styling
                    chartContainer.classList.add('p-4', 'bg-white', 'rounded', 'shadow-md', 'mb-4');

                    document.getElementById('selectedCategoryCharts').appendChild(chartContainer);

                    var chart = new google.visualization.ColumnChart(chartContainer);
                    chart.draw(data, options);
                }

            }
            function drawSelectedCategoryDeviceTypeChart(deviceTypeData) {
                // Ensure that Google Charts is loaded
                google.charts.load('current', {'packages':['corechart']});

                // Set a callback function to draw the chart when Google Charts is loaded
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    // Create the data table from the fetched deviceTypeData
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Device Type');
                    data.addColumn('number', 'Count');

                    for (var i = 0; i < deviceTypeData.length; i++) {
                        data.addRow([deviceTypeData[i].deviceType, deviceTypeData[i].deviceCount]);
                    }

                    // Set chart options
                    var options = {
                        title: 'Devices',
                        titleTextStyle: { fontSize: 16 },
                        pieHole: 0.4, // To make it a doughnut chart,
                        pieSliceText: 'value', // Display actual values
                        colors: ['#4285F4', '#3F51B5'],
                        chartArea: { width: '50%', height: '50%' }
                        
                    };

                    // Instantiate and draw the chart, passing in the data and options
                    var chart = new google.visualization.PieChart(document.getElementById('selectedCategoryDeviceData'));
                    chart.draw(data, options);
                }
            }
            function drawMostRecommendedProducts(mostRecommendedProductData) {
                google.charts.load('current', {'packages':['corechart']});

                // Set a callback function to draw the chart when Google Charts is loaded
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    // Create the data table
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Product Name');
                        data.addColumn('number', 'Recommendation Count');

                        // Add rows to the data table
                        for (var i = 0; i < mostRecommendedProductData.length; i++) {
                            var product = mostRecommendedProductData[i];
                            data.addRow([product.prodName, product.recommendationCount]);
                        }

                        // Set chart options
                        var options = {
                            title: 'Most Recommended Products',
                            legend: { position: 'none' },
                            colors: ['#4285F4', '#3F51B5'], // Optional: Set custom colors
                            chartArea: { width: '70%', height: '70%' } // Optional: Adjust chart area size
                        };

                        // Instantiate and draw the chart
                        var chart = new google.visualization.ColumnChart(document.getElementById('selectedCategoryMostRecommendedData'));
                        chart.draw(data, options);
                }
            }
            function createSourcesTable(sources) {
                    // Get the container element
                    var container = document.getElementById("selectedCategorySources");
                    
                    // Clear existing table if any
                    container.innerHTML = "";

                    // Create the new table
                    var table = document.createElement("table");
                    table.classList.add("min-w-full", "bg-white", "border", "border-gray-300", "shadow-md", "mb-4");
                    var thead = table.createTHead();
                    thead.classList.add("bg-gray-50");
                    var tbody = document.createElement("tbody");

                    // Create table headers
                    var headerRow = thead.insertRow();
                    Object.keys(sources[0]).forEach(function (key) {
                        if (key !== "sourceCount") { // Exclude "Source Count" column
                            var th = document.createElement("th");
                            th.textContent = key.charAt(0).toUpperCase() + key.slice(1); // Capitalize first letter
                            th.classList.add("px-6", "py-3", "text-left", "text-xs", "font-medium", "text-gray-500", "uppercase", "tracking-wider");
                            headerRow.appendChild(th);
                        }
                    });

                    if (sources.length === 0) {
                        var noDataRow = tbody.insertRow();
                        var cell = noDataRow.insertCell();
                        cell.textContent = "No data available";
                        cell.colSpan = Object.keys(sources[0]).length - 1; // Adjust colspan based on the number of columns
                        cell.classList.add("px-6", "py-4", "whitespace-no-wrap", "text-sm", "leading-5", "text-gray-900");
                    }
                    else {
                    // Create table rows
                    sources.forEach(function (item) {
                        var row = tbody.insertRow();
                        row.classList.add("bg-white");
                        Object.entries(item).forEach(function ([key, value]) {
                            if (key !== "sourceCount") { // Exclude "Source Count" column
                                var cell = row.insertCell();
                                cell.textContent = value;
                                cell.classList.add("px-6", "py-4", "whitespace-no-wrap", "text-sm", "leading-5", "text-gray-900");
                            }
                        });
                    });
                    }
                    table.appendChild(tbody);
                    container.appendChild(table); // Append the table to the container
                }
            function createOutboundTable(outbound) {
                    var container = document.getElementById("selectedCategoryOutbound");
                    container.innerHTML = ""; // Clear existing content

                    var table = document.createElement("table");
                    table.classList.add("min-w-full", "bg-white", "border", "border-gray-300", "shadow-md");

                    // Create table header
                    var thead = document.createElement("thead");
                    thead.classList.add("bg-gray-50");
                    var headerRow = thead.insertRow();
                    var headers = Object.keys(outbound[0]);
                    headers.forEach(function(headerText) {
                        var th = document.createElement("th");
                        th.textContent = headerText;
                        th.classList.add("px-6", "py-3", "text-left", "text-xs", "font-medium", "text-gray-500", "uppercase", "tracking-wider");
                        headerRow.appendChild(th);
                    });
                    table.appendChild(thead);

                    // Create table body
                    var tbody = document.createElement("tbody");
                    outbound.forEach(function(rowData) {
                        var row = tbody.insertRow();
                        headers.forEach(function(header) {
                            var cell = row.insertCell();
                            var text = rowData[header];
                            if (header === "prodURL" && text.length > 50) { // Limiting prodURL to 30 characters
                                text = text.substr(0, 50) + "..."; // Truncate and add ellipsis
                            }
                            cell.textContent = text;
                            cell.classList.add("px-6", "py-4", "whitespace-no-wrap", "text-sm", "leading-5", "text-gray-900");
                        });
                    });
                    table.appendChild(tbody);

                    // Append table to the container element
                    container.appendChild(table);
                }

            function exportChartDataToCSV(chartData) {
                    const csvRows = [];

                    // Header row
                    csvRows.push('Question,Answer,ClickCount,TotalSessions');

                    // Data rows
                    for (const question in chartData) {
                        if (chartData.hasOwnProperty(question)) {
                            const questionData = chartData[question];
                            questionData.forEach(entry => {
                                const rowData = [
                                    escapeCSVValue(question),
                                    escapeCSVValue(entry.answer),
                                    entry.clickCount,
                                    entry.totalSessions
                                ];
                                csvRows.push(rowData.join(','));
                            });
                        }
                    }

                    // Create a CSV string
                    const csvContent = csvRows.join('\n');

                    // Create a data URI for the CSV content
                    const encodedUri = encodeURI("data:text/csv;charset=utf-8," + csvContent);

                    // Create a link element and trigger the download
                    const link = document.createElement('a');
                    link.setAttribute('href', encodedUri);
                    link.setAttribute('download', 'chartData.csv');
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }

                function escapeCSVValue(value) {
                    // Enclose the value in double quotes and escape any existing double quotes
                    const escapedValue = String(value).replace(/"/g, '""');

                    // Replace additional special characters as needed
                    const replacements = {
                        '\u2013': '--', // En dash
                        '\u2014': '---' // Em dash
                        // Add more replacements for other special characters as needed
                    };

                    // Apply additional replacements
                    return `"${applyReplacements(escapedValue, replacements)}"`;
                }

                function applyReplacements(value, replacements) {
                    // Replace specified characters in the value
                    for (const [search, replace] of Object.entries(replacements)) {
                        value = value.replace(new RegExp(search, 'g'), replace);
                    }
                    return value;
                }
</script>