<?php
// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Check if customerId is set and not empty
if (isset($_POST['customerId']) && !empty($_POST['customerId'])) {
    // Sanitize input to prevent SQL injection
    $customerId = mysqli_real_escape_string($conn, $_POST['customerId']);

    // Query to fetch customer data based on ID
    $query = "SELECT * FROM customers WHERE customer_id = '$customerId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Fetch customer data
        $customerData = mysqli_fetch_assoc($result);

        // Return customer data as JSON response
        echo json_encode($customerData);
    } else {
        // Handle query error
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Handle invalid input
    echo "Invalid input";
}
?>
