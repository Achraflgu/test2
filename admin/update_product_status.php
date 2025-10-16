<?php
include 'db_connection.php';

if (isset($_POST['productId'], $_POST['status'])) {
    $productId = intval($_POST['productId']);
    $status = intval($_POST['status']);

    try {
        $sql = "UPDATE products SET product_status = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $status, $productId);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'Error: Unable to execute update query.';
        }
    } catch (mysqli_sql_exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Error: productId and status are not set.';
}
?>
