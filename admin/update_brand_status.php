<?php
include 'db_connection.php';

if (isset($_POST['brandId'], $_POST['status'])) {
    $brandId = intval($_POST['brandId']);
    $status = intval($_POST['status']);

    try {
        $sql = "UPDATE brands SET brand_status = ? WHERE brand_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $status, $brandId);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'Error: Unable to execute update query.';
        }
    } catch (mysqli_sql_exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Error: Brand ID and status are not set.';
}
?>
