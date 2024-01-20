<?php
include "../config.php";

// Assuming this is your PHP file (e.g., create_car.php)

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from the request body
    $jsonInput = file_get_contents('php://input');
    
    // Validate JSON data
    $requestData = json_decode($jsonInput, true);
    if ($requestData === null) {
        // Invalid JSON data
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }

    // Collect car data
    $makerID = $requestData['makerID'];
    $modelID = $requestData['modelID'];
    $variant = $requestData['variant'];
    $registrationYear = $requestData['registrationYear'];
    $price = $requestData['price'];
    $mileage = $requestData['mileage'];
    $fuelType = $requestData['fuelType'];
    $transmission = $requestData['transmission'];
    $carCondition = $requestData['carCondition'];
    $description = $requestData['description'];
    $sellerID = $requestData['sellerID'];
    $location = $requestData['location'];
    $carStatus = $requestData['carStatus'];
    $title=$requestData['title'];

    // Handle image URLs
    $uploadedImages = [];

    // Check if 'images' key is set in the request
    if (isset($requestData['images'])) {
        $imageUrls = $requestData['images'];
        foreach ($imageUrls as $imageUrl) {
            $uploadedImages[] = $imageUrl;
        }
    }

    // Insert car data into the 'cars' table
    $insertCarQuery = "INSERT INTO cars (MakerID, ModelID, Variant, RegistrationYear, Price, Mileage, FuelType, Transmission, carCondition, Description, SellerID, Location, carStatus,title) 
                       VALUES ('$makerID', '$modelID', '$variant', '$registrationYear', '$price', '$mileage', '$fuelType', '$transmission', '$carCondition', '$description', '$sellerID', '$location', '$carStatus','$title')";
    mysqli_query($conn, $insertCarQuery);

    // Get the last inserted CarID
    $carID = mysqli_insert_id($conn);

    // Insert image data into the 'carimages' table
    $imagePaths = implode(',', $uploadedImages);
    $insertImageQuery = "INSERT INTO carimages (CarID, ImageUrl) VALUES ('$carID', '$imagePaths')";
    mysqli_query($conn, $insertImageQuery);

    // Display a success message
    echo 'Car information and images uploaded successfully!';
} else {
    // Handle other HTTP methods if needed
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
