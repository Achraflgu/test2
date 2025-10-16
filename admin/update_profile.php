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
        
        // Move uploaded file to destination
        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            // Update the database with the new photo path
            $updateQuery = "UPDATE admins SET admin_photo = ? WHERE admin_email = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $updateQuery)) {
                mysqli_stmt_bind_param($stmt, "ss", $fileDestination, $email);
                if (mysqli_stmt_execute($stmt)) {
                    // Set photoUpdated to true
                    $photoUpdated = true;
                } else {
                    // Handle database error
                    echo "Error updating photo: " . mysqli_error($conn);
                    exit();
                }
            } else {
                // Handle statement preparation error
                echo "SQL error: " . mysqli_error($conn);
                exit();
            }
            mysqli_stmt_close($stmt);
        } else {
            // Handle file moving error
            echo "Error moving uploaded file.";
            exit();
        }
    } else {
        // Handle file upload error
        echo "File upload error: " . $fileError;
        header("Location: index.php?page=Profile&error=photo_error");
        exit();
    }
}

// If the photo is updated successfully or no photo update is performed, update other profile fields
if ($photoUpdated || !isset($_FILES['admin_photo'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $newEmail = $_POST['email'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password']; // Assuming you have fields for new password and confirm password

    // Perform validations here if needed

    // Check if the current password is correct
    $sql = "SELECT * FROM admins WHERE admin_email = ? AND admin_password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $currentPassword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        // Current password is correct, proceed with updating admin information
        $updatePassword = "";
        if (!empty($newPassword)) {
            // If new password is provided, update it
            $updatePassword = ", admin_password = ?";
        }

        // Prepare update query
        $updateQuery = "UPDATE admins SET admin_name = ?, admin_phone = ?, admin_email = ? $updatePassword WHERE admin_email = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        if (!empty($newPassword)) {
            mysqli_stmt_bind_param($stmt, "sssss", $name, $phone, $newEmail, $newPassword, $email);
        } else {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $newEmail, $email);
        }

        // Execute update query
        if (mysqli_stmt_execute($stmt)) {
            // If update successful, you might also update session variables if needed
            $_SESSION['email'] = $newEmail; // Update session with new email if it has changed
            // Redirect back to the dashboard
            header("Location: index.php?page=Profile&success=true");
            exit();
        } else {
            // Handle error if the query fails
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // Current password is incorrect, redirect back to the profile page with an error message
        header("Location: index.php?page=Profile&error=incorrect_password");
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>
