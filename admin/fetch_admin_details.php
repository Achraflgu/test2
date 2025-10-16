<?php
// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Check if adminId is set
if(isset($_POST['adminId'])) {
    $adminId = $_POST['adminId'];
    
    // Prepare and execute query to fetch admin details by adminId
    $stmt = $conn->prepare("SELECT admin_name, admin_photo, admin_job, admin_phone FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch admin details
    $row = $result->fetch_assoc();

    // Close statement
    $stmt->close();

    // Return admin details as JSON response
    echo json_encode($row);
} else {
    // If adminId is not set, return error response
    http_response_code(400);
    echo "Error: Admin ID is not set.";
}
?>
