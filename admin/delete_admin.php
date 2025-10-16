<?php
// Check if the admin ID is provided
if (isset($_POST['adminId'])) {
    // Include database connection
    include_once 'db_connection.php'; // Assuming you have this file

    // Escape admin ID for security
    $adminId = mysqli_real_escape_string($conn, $_POST['adminId']);

    // SQL query to delete the admin
    $sql = "DELETE FROM admins WHERE admin_id = '$adminId'";

    if (mysqli_query($conn, $sql)) {
        // If the deletion was successful
        $response = array(
            "status" => "success",
            "message" => "Admin deleted successfully."
        );
        echo json_encode($response);
    } else {
        // If there was an error with the deletion
        $response = array(
            "status" => "error",
            "message" => "Error deleting admin: " . mysqli_error($conn)
        );
        echo json_encode($response);
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // If the admin ID is not provided
    $response = array(
        "status" => "error",
        "message" => "Admin ID not provided."
    );
    echo json_encode($response);
}
?>
