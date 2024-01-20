<?php

include "../config.php";

$priceMin = $_GET['priceMin'] ?? null;
$priceMax = $_GET['priceMax'] ?? null;
$mileageMin = $_GET['mileageMin'] ?? null;
$mileageMax = $_GET['mileageMax'] ?? null;
$variant = $_GET['variant'] ?? null;
$condition = $_GET['condition'] ?? null;
$location = $_GET['location'] ?? null;
$availabilityStatus = $_GET['availabilityStatus'] ?? null;

// Build the SQL query based on the filters
$filterQuery = "SELECT * FROM cars WHERE 1";

if ($priceMin !== null && $priceMax !== null) {
    $filterQuery .= " AND Price BETWEEN $priceMin AND $priceMax";
}

if ($mileageMin !== null && $mileageMax !== null) {
    $filterQuery .= " AND Mileage BETWEEN $mileageMin AND $mileageMax";
}

if ($variant !== null) {
    $filterQuery .= " AND Variant = '$variant'";
}

if ($condition !== null) {
    $filterQuery .= " AND carCondition = '$condition'";
}

if ($location !== null) {
    $filterQuery .= " AND Location = '$location'";
}

if ($availabilityStatus !== null) {
    $filterQuery .= " AND carStatus = '$availabilityStatus'";
}

// Execute the query
$result = mysqli_query($conn, $filterQuery);

if ($result) {
    // Fetch the filtered data as an associative array
    $filteredCarsData = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Send the filtered data as JSON
    header('Content-Type: application/json');
    echo json_encode($filteredCarsData);
} else {
    // Handle the case when filtering fails
    $response = array('success' => false, 'message' => 'Failed to apply filters');
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close the database connection
mysqli_close($conn);
?>
