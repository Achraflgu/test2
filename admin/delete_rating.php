<?php
// Include database connection
include_once 'db_connection.php';

// Check if ratingId is provided in the POST request
if (isset($_POST['ratingId'])) {
    // Sanitize the input to prevent SQL injection
    $ratingId = mysqli_real_escape_string($conn, $_POST['ratingId']);

    // Construct the SQL query to delete the rating
    $query = "DELETE FROM productreviews WHERE review_id = '$ratingId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Deletion successful
        echo json_encode(array('success' => true));
    } else {
        // Error deleting rating
        echo json_encode(array('error' => 'Failed to delete rating'));
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // RatingId is not provided
    echo json_encode(array('error' => 'RatingId not provided'));
}
?>
