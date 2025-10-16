<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Get the email of the logged-in admin
$email = $_SESSION['email'];

// Initialize variable for photo update status
$photoUpdated = false;

// File upload handling
if (isset($_FILES['admin_photo'])) {
    $file = $_FILES['admin_photo'];
    $fileError = $file['error'];

    // Check if there is no upload error
    if ($fileError === 0) {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileDestination = 'uploads/' . $fileName; // Change 'uploads/' to your desired destination
        move_uploaded_file($fileTmpName, $fileDestination);

        // Update the database with the new photo path
        $updateQuery = "UPDATE admins SET admin_photo = ? WHERE admin_email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $updateQuery)) {
            // Handle error if the query fails to prepare
            echo "SQL error: " . mysqli_error($conn);
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $fileDestination, $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            // Set photoUpdated to true
            $photoUpdated = true;
        }
    } else {
        // Handle file upload error
        echo "File upload error: " . $fileError;
        exit();
    }
}

// Return the updated photo path as response
if ($photoUpdated) {
    echo $fileDestination;
} else {
    echo ""; // Return empty string if photo not updated
}

// Close the database connection
mysqli_close($conn);
?>
