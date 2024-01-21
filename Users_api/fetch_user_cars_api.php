<?php

include "../config.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $jsonInput = file_get_contents('php://input');
    
    // Validate JSON data
    $requestData = json_decode($jsonInput, true);
    if ($requestData === null || !isset($requestData['userID'])) {
        
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid JSON data or missing userID']);
        exit;
    }

    // Get the user ID to fetch cars
    $userID = $requestData['userID'];

    $fetchCarsQuery = "SELECT * FROM cars WHERE SellerID = '$userID'";
    $result = mysqli_query($conn, $fetchCarsQuery);

    if ($result) {
        $carsData = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Fetch and append image URLs for each car
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
        $response = array('success' => false, 'message' => 'Failed to fetch cars');
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Handle other HTTP methods if needed
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}

// Close the database connection
mysqli_close($conn);
?>
