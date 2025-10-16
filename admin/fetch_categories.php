<?php
// Assuming you have a database connection established
// Include your database connection file here if not already included
include 'db_connection.php';
// Function to fetch categories from the database
$query = "SELECT * FROM productcategories";
$result = mysqli_query($connection, $query);

// Check if query was successful
if ($result) {
    $categories = array();
    // Fetch categories from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['pcategory_name'];
    }
    // Output categories as JSON
    echo json_encode($categories);
} else {
    // Handle query error
    echo "Error: " . mysqli_error($connection);
}

// Close database connection
mysqli_close($connection);
?>
