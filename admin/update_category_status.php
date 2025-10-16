<?php
include 'db_connection.php';

if (isset($_POST['categoryId'], $_POST['status'])) {
    $categoryId = intval($_POST['categoryId']);
    $status = intval($_POST['status']);

    try {
        $sql = "UPDATE productcategories SET pcategory_status = ? WHERE pcategory_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $status, $categoryId);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'Error: Unable to execute update query.';
        }
    } catch (mysqli_sql_exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Error: Category ID and status are not set.';
}
?>
