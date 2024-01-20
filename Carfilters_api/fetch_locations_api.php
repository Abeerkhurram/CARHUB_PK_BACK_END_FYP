<?php

include '../config.php'; // Include your database configuration

// Function to fetch cities data
function getCitiesData() {
    global $conn;

    $query = "SELECT * FROM locations";
    $result = mysqli_query($conn, $query);

    $citiesData = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $citiesData[] = $row;
        }
        return $citiesData;
    } else {
        return ['error' => mysqli_error($conn)];
    }
}

// Set the content type to JSON
header('Content-Type: application/json');

// Call the function to get cities data
$citiesData = getCitiesData();

// Output the data as JSON
echo json_encode($citiesData);

// Close the database connection
mysqli_close($conn);
?>
