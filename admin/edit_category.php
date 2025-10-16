<?php
// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate category name input
    $categoryId = $_POST['editCategoryId'];
    $categoryName = mysqli_real_escape_string($conn, $_POST['editCategoryName']);

    // Check if a photo is uploaded
    if(isset($_FILES['editCategoryPhoto']) && $_FILES['editCategoryPhoto']['error'] === UPLOAD_ERR_OK) {
        // Process the uploaded file
        $photoTmpName = $_FILES['editCategoryPhoto']['tmp_name'];
        $photoName = $_FILES['editCategoryPhoto']['name'];
        $photoPath = "uploads/" . $photoName; // Assuming there's an uploads directory

        // Move the uploaded file to the uploads directory
        if(move_uploaded_file($photoTmpName, $photoPath)) {
            // Update category with new name and photo filename
            $query = "UPDATE productcategories SET pcategory_name = '$categoryName', pcategory_photo = '$photoPath' WHERE pcategory_id = $categoryId";
            $result = mysqli_query($conn, $query);

            if($result) {
                echo "Category updated successfully.";
            } else {
                echo "Error updating category.";
            }
        } else {
            echo "Error uploading photo.";
        }
    } else {
        // Update category with new name only
        $query = "UPDATE productcategories SET pcategory_name = '$categoryName' WHERE pcategory_id = $categoryId";
        $result = mysqli_query($conn, $query);

        if($result) {
            echo "Category updated successfully.";
        } else {
            echo "Error updating category.";
        }
    }
}
?>
