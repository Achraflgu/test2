<?php
// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Fetch out-of-stock products from the database
$query = "SELECT * FROM products WHERE product_stock_quantity <= 0";
$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Out of Stock Products</title>
    <!-- Include any necessary CSS stylesheets here -->
</head>
<body>
    <h1>Out of Stock Products</h1>
    <ul>
        <?php
        // Check if there are out-of-stock products to display
        if (mysqli_num_rows($result) > 0) {
            // Loop through each out-of-stock product and display its details
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li>' . $row['product_name'] . '</li>';
            }
        } else {
            // If no out-of-stock products found
            echo '<li>No out-of-stock products found.</li>';
        }
        ?>
    </ul>
    <!-- Include any necessary JavaScript scripts here -->
</body>
</html>
