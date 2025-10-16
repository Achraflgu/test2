<?php
// Include database connection
include_once 'db_connection.php';
// Retrieve category and tag from AJAX request
$category = $_POST['category'];
$tag = $_POST['tag'];

// Construct SQL query based on selected filters
$sql = "SELECT * FROM products WHERE 1=1";
if (!empty($category)) {
    $sql .= " AND pcategory_id = " . $category;
}
if (!empty($tag)) {
    $sql .= " AND product_tag = '" . $tag . "'";
}

// Execute query
$result = mysqli_query($conn, $sql);

// Display filtered results
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="col-lg-3 col-md-4 col-sm-6">';
        echo '<div class="card product_item">';
        echo '<div class="body">';
        echo '<div class="cp_img">';
        echo '<img src="' . $row['product_photo'] . '" alt="Product" class="img-fluid">';
        echo '<div class="hover">';
        // Other HTML elements for edit, delete, and view buttons
        echo '</div>';
        // Display product tag if not empty
        if (!empty($row['product_tag'])) {
            echo '<div class="tag"><span class="tag-text">' . $row['product_tag'] . '</span></div>';
        }
        echo '</div>';
        echo '<div class="product_details">';
        echo '<h5><a href="ec-product-detail.html">' . $row['product_name'] . '</a></h5>';
        echo '<ul class="product_price list-unstyled">';
        echo '<li class="old_price">' . $row['product_price'] . ' DT</li>';
        // Display sale price if the product is on sale
        if ($row['product_tag'] === 'Sale') {
            echo '<li class="new_price">' . $row['product_sale_price'] . ' DT</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "No results found.";
}

// Close database connection
mysqli_close($conn);
?>
