<?php

include "../config.php";


$data = json_decode(file_get_contents("php://input"));

// Extract user attributes
$email = $data->email ?? null;
$phoneNumber = $data->phoneNumber ?? null;
$password = $data->password ?? null;

if (($email === null && $phoneNumber === null) || $password === null) {
    $response = array('success' => false, 'message' => 'Email or PhoneNumber and Password must be provided');
} else {
    // Validate the user credentials
    $whereClause = '';

    if ($email !== null) {
        $whereClause = "Email = '$email'";
    } elseif ($phoneNumber !== null) {
        $whereClause = "PhoneNumber = '$phoneNumber'";
    }

    $verificationQuery = "SELECT * FROM users WHERE $whereClause AND userPassword = '$password'";
    $verificationResult = mysqli_query($conn, $verificationQuery);

    if ($verificationResult && mysqli_num_rows($verificationResult) > 0) {
        // Verification successful, fetch user details
        $userDetails = mysqli_fetch_assoc($verificationResult);

        // Return user details in JSON
        $response = array('success' => true, 'userDetails' => $userDetails);
    } else {
        // Verification failed
        $response = array('success' => false, 'message' => 'Invalid credentials');
    }
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
mysqli_close($conn);
?>
