<?php
// Assuming this is your PHP file (e.g., search_cars.php)
include "../config.php";

// Decode JSON data from the client
$data = json_decode(file_get_contents("php://input"), true);

// Extract the keywords
$keywords = $data['keywords'];

// Initialize an array to store the results
$searchResults = array();

// Break the keywords into an array
$keywordArray = explode(' ', $keywords);

// Iterate through each keyword
foreach ($keywordArray as $keyword) {
    // Build the SQL query to search for the keyword in relevant columns
    $searchQuery = "SELECT * FROM cars WHERE carStatus IN ('active', 'Active') AND 
                    (LOWER(Description) LIKE LOWER('%$keyword%') OR 
                     LOWER(Variant) LIKE LOWER('%$keyword%') OR 
                     LOWER(FuelType) LIKE LOWER('%$keyword%') OR 
                     LOWER(carCondition) LIKE LOWER('%$keyword%') OR 
                     LOWER(Maker) LIKE LOWER('%$keyword%') OR 
                     LOWER(Model) LIKE LOWER('%$keyword%'))";

    // Execute the query
    $result = mysqli_query($conn, $searchQuery);

    if ($result) {
        // Fetch the data as an associative array
        $searchResults = array_merge($searchResults, mysqli_fetch_all($result, MYSQLI_ASSOC));
    }
}

// Process the results to remove duplicates (if any)
$searchResults = array_values(array_unique($searchResults, SORT_REGULAR));

// Fetch and append image URLs for each car
foreach ($searchResults as &$car) {
    $carID = $car['CarID'];
    $fetchImagesQuery = "SELECT ImageUrl FROM carimages WHERE CarID = '$carID'";
    $imageResult = mysqli_query($conn, $fetchImagesQuery);
    $imageUrls = mysqli_fetch_all($imageResult, MYSQLI_ASSOC);

    // Add the image URLs to the car data
    $car['ImageUrls'] = $imageUrls;
}

// Send the data as JSON
header('Content-Type: application/json');
echo json_encode($searchResults);

// Close the database connection
mysqli_close($conn);
?>
