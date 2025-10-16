<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary fields are set
    if (isset($_POST['adminId']) && isset($_POST['adminName']) && isset($_POST['adminJob']) && isset($_POST['adminPhone'])) {
        // Include database connection
        include_once 'db_connection.php'; // Assuming you have this file

        // Escape user inputs for security
        $adminId = mysqli_real_escape_string($conn, $_POST['adminId']);
        $adminName = mysqli_real_escape_string($conn, $_POST['adminName']);
        $adminJob = mysqli_real_escape_string($conn, $_POST['adminJob']);
        $adminPhone = mysqli_real_escape_string($conn, $_POST['adminPhone']);

        // Handle photo upload
        $targetDirectory = "uploads/";
        $newAdminPhotoPath = ''; // Initialize variable to store new photo path

        if (!empty($_FILES['newAdminPhoto']['name'])) {
            $fileName = basename($_FILES['newAdminPhoto']['name']);
            $targetFilePath = $targetDirectory . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['newAdminPhoto']['tmp_name'], $targetFilePath)) {
                    $newAdminPhotoPath = $targetFilePath;
                } else {
                    $response = array(
                        "status" => "error",
                        "message" => "Error uploading photo."
                    );
                    echo json_encode($response);
                    exit; // Stop script execution
                }
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed."
                );
                echo json_encode($response);
                exit; // Stop script execution
            }
        }

        // Update admin information in the database
        $sql = "UPDATE admins SET admin_name='$adminName', admin_job='$adminJob', admin_phone='$adminPhone'";
        if (!empty($newAdminPhotoPath)) {
            $sql .= ", admin_photo='$newAdminPhotoPath'";
        }
        $sql .= " WHERE admin_id='$adminId'";

        if (mysqli_query($conn, $sql)) {
            // If the update was successful
            $response = array(
                "status" => "success",
                "message" => "Admin information updated successfully.",
                "newAdminPhotoPath" => $newAdminPhotoPath // Provide new photo path to update the image source in the frontend
            );
            echo json_encode($response);
        } else {
            // If there was an error with the update
            $response = array(
                "status" => "error",
                "message" => "Error updating admin information: " . mysqli_error($conn)
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
