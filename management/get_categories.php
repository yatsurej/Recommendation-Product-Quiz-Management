<?php
require '../db.php';

$categoryQuery = "SELECT * FROM category";
$categoryResult = mysqli_query($conn, $categoryQuery);

$options = '<option value="">Select category</option>';
while ($row = mysqli_fetch_assoc($categoryResult)) {
    $categoryID = $row['categoryID'];
    $categoryName = $row['categoryName'];
    $options .= "<option value=\"$categoryID\">$categoryName</option>";
}

echo $options;
?>