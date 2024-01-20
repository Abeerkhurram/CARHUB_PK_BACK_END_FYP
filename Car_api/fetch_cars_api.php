<?php

include "../config.php";

$fetchCarsQuery = "SELECT * FROM cars WHERE carStatus IN ('active', 'Active')";
$result = mysqli_query($conn, $fetchCarsQuery);

if ($result) {
    $carsData = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Fetch and append image URLs for each active car
    foreach ($carsData as &$car) {
        $carID = $car['CarID'];
        $fetchImagesQuery = "SELECT ImageUrl FROM carimages WHERE CarID = '$carID'";
        $imageResult = mysqli_query($conn, $fetchImagesQuery);
        $imageUrls = mysqli_fetch_all($imageResult, MYSQLI_ASSOC);

        $car['ImageUrls'] = $imageUrls;
    }

    // Send the data as JSON
    header('Content-Type: application/json');
    echo json_encode($carsData);
} else {
    // Exception handling
    $response = array('success' => false, 'message' => 'Failed to fetch active cars');
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close the database connection
mysqli_close($conn);
?>
