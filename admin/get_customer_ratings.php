<?php
// Include database connection
include_once 'db_connection.php';

// Check if productId is provided in the POST request
if (isset($_POST['productId'])) {
    // Sanitize the input to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $_POST['productId']);

    // Fetch customer ratings for the product from the database
    $query = "SELECT pr.*, c.customer_name
              FROM productreviews pr
              INNER JOIN customers c ON pr.customer_id = c.customer_id
              WHERE pr.product_id = '$productId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if there are any ratings for the product
        if (mysqli_num_rows($result) > 0) {
            $ratings = [];
            // Fetch each rating and store it in an array
            while ($row = mysqli_fetch_assoc($result)) {
                $ratings[] = $row;
            }
            // Return the ratings as JSON response
            echo json_encode($ratings);
        } else {
            // No ratings found for the product
            echo json_encode(array('error' => 'No ratings found for the product'));
        }
    } else {
        // Error fetching ratings
        echo json_encode(array('error' => 'Failed to fetch ratings'));
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // ProductId is not provided
    echo json_encode(array('error' => 'ProductId not provided'));
}
?>
