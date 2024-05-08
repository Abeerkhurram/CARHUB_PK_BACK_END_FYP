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

    // Get the user ID to fetch user details
    $userID = $requestData['userID'];

    $fetchUserQuery = "SELECT UserID, Name, Email, PhoneNumber, userPassword, Location, RegistrationDate FROM users WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $fetchUserQuery);

    if ($result) {
        $userData = mysqli_fetch_assoc($result);

        // Encode user details in JSON
        header('Content-Type: application/json');
        echo json_encode($userData);
    } else {
        // Exception handling
        $response = array('success' => false, 'message' => 'Failed to fetch user details');
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
