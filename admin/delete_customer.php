<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the customer ID is provided
    if (isset($_POST['customerId'])) {
        // Include database connection
        include_once 'db_connection.php'; // Assuming you have this file

        // Escape the customer ID
        $customerId = mysqli_real_escape_string($conn, $_POST['customerId']);

        // Delete the customer record from the database
        $sql = "DELETE FROM customers WHERE customer_id = '$customerId'";
        if (mysqli_query($conn, $sql)) {
            $response = array(
                "status" => "success",
                "message" => "Customer deleted successfully."
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Error deleting customer: " . mysqli_error($conn)
            );
        }

        // Close database connection
        mysqli_close($conn);

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // If customer ID is not provided
        $response = array(
            "status" => "error",
            "message" => "Customer ID is missing."
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
