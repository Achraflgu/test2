<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary fields are set
    if (isset($_POST['customerId']) && isset($_POST['customerName']) && isset($_POST['customerAddress']) && isset($_POST['customerEmail'])) {
        // Include database connection
        include_once 'db_connection.php'; // Assuming you have this file

        // Escape user inputs for security
        $customerId = mysqli_real_escape_string($conn, $_POST['customerId']);
        $customerName = mysqli_real_escape_string($conn, $_POST['customerName']);
        $customerAddress = mysqli_real_escape_string($conn, $_POST['customerAddress']);
        // Email cannot be updated, so no need to escape it
        $customerEmail = $_POST['customerEmail'];

        // Handle photo upload
        // Handle photo upload
$customerPhoto = isset($_POST['customerPhoto']) ? $_POST['customerPhoto'] : 'uploads/default.jpg';


        // Update customer information in the database
        $sql = "UPDATE customers SET customer_name='$customerName', customer_address='$customerAddress', customers_photo='$customerPhoto' WHERE customer_id='$customerId'";

        if (mysqli_query($conn, $sql)) {
            // If the update was successful
            $response = array(
                "status" => "success",
                "message" => "Customer information updated successfully."
            );
            echo json_encode($response);
        } else {
            // If there was an error with the update
            $response = array(
                "status" => "error",
                "message" => "Error updating customer information: " . mysqli_error($conn)
            );
            echo json_encode($response);
        }

        // Close database connection
        mysqli_close($conn);
    } else {
        // If any necessary field is missing
        $response = array(
            "status" => "error",
            "message" => "Missing parameters."
        );
        echo json_encode($response);
    }
} else {
    // If the request method is not POST
    $response = array(
        "status" => "error",
        "message" => "Invalid request method."
    );
    echo json_encode($response);
}
?>
