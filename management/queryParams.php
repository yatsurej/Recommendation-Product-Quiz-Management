<?php

$categoryID = isset($_GET['categoryID']) ? $_GET['categoryID'] : 0;
$categoryID = intval($categoryID);

$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

if (!$startDate && !$endDate) {
    $startDate = '2000-01-01';
    $endDate = date('Y-m-d');
}
$endDate = $endDate ? date('Y-m-d', strtotime($endDate . ' + 1 day')) : null;

?>