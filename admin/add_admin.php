<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary fields are set
    if (isset($_POST['addadminName']) && isset($_POST['addadminEmail']) && isset($_POST['addadminPassword']) && isset($_POST['addadminJob']) && isset($_POST['addadminPhone'])) {
        // Include database connection
        include_once 'db_connection.php'; // Assuming you have this file

        // Escape user inputs for security
        $adminName = mysqli_real_escape_string($conn, $_POST['addadminName']);
        $adminEmail = mysqli_real_escape_string($conn, $_POST['addadminEmail']);
        $adminPassword = mysqli_real_escape_string($conn, $_POST['addadminPassword']); // You should hash the password before storing it in the database
        $adminJob = mysqli_real_escape_string($conn, $_POST['addadminJob']);
        $adminPhone = mysqli_real_escape_string($conn, $_POST['addadminPhone']);

        // Handle photo upload
        $adminPhoto = ''; // Initialize empty variable to store photo path
        if (isset($_FILES['addAdminPhoto'])) {
            $targetDir = "uploads/"; // Specify the directory where you want to store the photo
            $fileName = basename($_FILES["addAdminPhoto"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array($fileType, $allowTypes)) {
                // Upload file to server
                if (move_uploaded_file($_FILES["addAdminPhoto"]["tmp_name"], $targetFilePath)) {
                    $adminPhoto = $targetFilePath;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG, GIF files are allowed.";
            }
        }

        // Insert admin information into the database
        $sql = "INSERT INTO admins (admin_name, admin_email, admin_password, admin_job, admin_phone, admin_photo) VALUES ('$adminName', '$adminEmail', '$adminPassword', '$adminJob', '$adminPhone', '$adminPhoto')";

        if (mysqli_query($conn, $sql)) {
            // If the insertion was successful
            $response = array(
                "status" => "success",
                "message" => "New admin added successfully."
            );
            echo json_encode($response);
        } else {
            // If there was an error with the insertion
            $response = array(
                "status" => "error",
                "message" => "Error adding new admin: " . mysqli_error($conn)
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
