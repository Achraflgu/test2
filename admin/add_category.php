<?php
// Include database connection
include_once 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate category name input
    $categoryName = mysqli_real_escape_string($conn, $_POST['categoryName']);

    // Check if a photo is uploaded
    if(isset($_FILES['categoryPhoto']) && $_FILES['categoryPhoto']['error'] === UPLOAD_ERR_OK) {
        // Process the uploaded file
        $photoTmpName = $_FILES['categoryPhoto']['tmp_name'];
        $photoName = $_FILES['categoryPhoto']['name'];
        $photoPath = "uploads/" . $photoName; // Assuming there's an uploads directory

        // Move the uploaded file to the uploads directory
        if(move_uploaded_file($photoTmpName, $photoPath)) {
            // Insert new category into the database
            $query = "INSERT INTO productcategories (pcategory_name, pcategory_photo) VALUES ('$categoryName', '$photoPath')";
            $result = mysqli_query($conn, $query);

            if($result) {
                echo "Category added successfully.";
            } else {
                echo "Error adding category.";
            }
        } else {
            echo "Error uploading photo.";
        }
    } else {
        echo "Error: Photo not uploaded.";
    }
}
?>
