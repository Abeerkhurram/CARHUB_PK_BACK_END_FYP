<?php
include "../config.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $jsonInput = file_get_contents('php://input');
    
   
    $requestData = json_decode($jsonInput, true);
    if ($requestData === null) {
      
        http_response_code(400);
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
    $title = $requestData['title'];
    $carIDToUpdate = $requestData['carID']; 

    // Handle image URLs
    $uploadedImages = [];

    
    if (isset($requestData['images'])) {
        $imageUrls = $requestData['images'];
        foreach ($imageUrls as $imageUrl) {
            $uploadedImages[] = $imageUrl;
        }
    }

   
    $updateCarQuery = "UPDATE cars SET 
                        MakerID='$makerID', 
                        ModelID='$modelID', 
                        Variant='$variant', 
                        RegistrationYear='$registrationYear', 
                        Price='$price', 
                        Mileage='$mileage', 
                        FuelType='$fuelType', 
                        Transmission='$transmission', 
                        carCondition='$carCondition', 
                        Description='$description', 
                        SellerID='$sellerID', 
                        Location='$location', 
                        carStatus='$carStatus', 
                        title='$title'
                       WHERE CarID='$carIDToUpdate'";
    mysqli_query($conn, $updateCarQuery);

    // Insert image data into the 'carimages' table
    $imagePaths = implode(',', $uploadedImages);
    $insertImageQuery = "UPDATE carimages SET ImageUrl='$imagePaths' WHERE CarID='$carIDToUpdate' ";
    mysqli_query($conn, $insertImageQuery);

    
    echo 'Car information and images updated successfully!';
} else {
    
    http_response_code(405); // Method Not Allowed because it is post request get some data to proceed
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
