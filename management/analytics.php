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
    $query = "SELECT COUNT(DISTINCT s.guestID) AS totalUsers, COUNT(*) AS totalEvents, c.categoryName
                FROM session s
                LEFT JOIN product p ON s.prodID = p.prodID
                LEFT JOIN category c ON p.categoryID = c.categoryID
                WHERE s.prodID IS NOT NULL ";

    if (!empty($timeFilterStart) && !empty($timeFilterEnd)) {
        $formattedTimestampStart = date("Y-m-d H:i:s", strtotime($timeFilterStart));
        $formattedTimestampEnd = date("Y-m-d H:i:s", strtotime($timeFilterEnd));
        $query .= " AND s.timestamp BETWEEN '$formattedTimestampStart' AND '$formattedTimestampEnd'";
    }

    $query .= " GROUP BY c.categoryName"; // Group by category

    $result = mysqli_query($conn, $query);

    $totalUsers = 0; 
    $totalEvents = 0;
    $categoryCounts = []; // Initialize array to store category counts

    while($row = mysqli_fetch_assoc($result)){
        $totalUsers = $row['totalUsers'];
        $totalEvents = $row['totalEvents'];
        $categoryName = $row['categoryName'];
        
        // Store category counts in array
        $categoryCounts[$categoryName] = $row['totalEvents'];
    }
?>
<div class="container w-50">
    <form>
        <label for="timestamp_start">From:</label>
        <input type="date" class="form-control" name="timestamp_start" id="timestamp_start" value="<?php echo $timeFilterStart; ?>">
        <label for="timestamp_end">To:</label>
        <input type="date" class="form-control" name="timestamp_end" id="timestamp_end" value="<?php echo $timeFilterEnd; ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    <?php
    echo "Total Users: " . $totalUsers . "<br>";
    echo "Total Events: " . $totalEvents . "<br>";
    // Display category counts
    foreach ($categoryCounts as $category => $count){
        echo "Category: $category - Count: $count<br>";
    }
    ?>
</div>
