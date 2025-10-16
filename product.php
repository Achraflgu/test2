<?php

session_start();

include("db_connection.php");
include("header.php");
include("nav.php");


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Page Title #3
============================================= -->
<section id="page-title" class="page-title bg-parallax">
    <div class="bg-section">
        <?php
        // Include your database connection file
        include 'db_connection.php';

        // Check if the 'product_id' parameter is set in the URL
        if (isset($_GET['id'])) {
            // Assuming $productId contains the product ID passed from your PHP script
            $productId = $_GET['id']; // Example product ID

            // SQL query to fetch the category name and photo of the product based on its ID
            $sql = "SELECT pc.pcategory_name, pc.pcategory_photo
                    FROM products p
                    JOIN productcategories pc ON p.pcategory_id = pc.pcategory_id
                    WHERE p.product_id = ?";

            // Prepare the statement
            $stmt = mysqli_prepare($conn, $sql);

            // Bind the parameter
            mysqli_stmt_bind_param($stmt, "i", $productId);

            // Execute the query
            mysqli_stmt_execute($stmt);

            // Bind the result variables
            mysqli_stmt_bind_result($stmt, $categoryName, $categoryPhoto);

            // Fetch the result
            if (mysqli_stmt_fetch($stmt)) {
                // Output the category photo
                echo '<img src="admin/' . $categoryPhoto . '" alt="background" style="width: 100%; border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">';

                // Output the category name
                echo '<h1>' . $categoryName . '</h1>';
            } else {
                // Handle error if the query fails or product ID is not found
                echo 'Error fetching category';
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            // Handle case when 'product_id' parameter is not set in the URL
            echo 'Product ID parameter is missing';
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
    <!-- .bg-section end -->
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="title title-3 text-center">
                    <div class="title--content">
                        <div class="title--heading">
                            <h1>Shop Categories</h1>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <ol class="breadcrumb">
                        <li><a href="index-2.html">Home</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li class="active"><?php echo $categoryName; ?></li>
                    </ol>
                </div>
                <!-- .title end -->
            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>

<!-- #page-title end -->

<!-- product detalis #9
============================================= -->
<section id="product-detalis9" class="product-detalis product-detalis-3 product-detalis-9">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="products-gallery-carousel products-gallery-carousel-1">
                    <div class="owl-carousel products-slider" data-slider-id="1">
                        <div class="product-img">
                            <img id="product-img-1" src="" alt="product">
                        </div>
                        <div class="product-img">
                            <img id="product-img-2" src="" alt="product">
                        </div>
                        <div class="product-img">
                            <img id="product-img-3" src="" alt="product">
                        </div>
                        <div class="product-img">
                            <img id="product-img-4" src="" alt="product">
                        </div>
                    </div>
                    <div class="owl-thumbs thumbs-bg" data-slider-id="1">
                        <button class="owl-thumb-item">
                            <img id="thumb-1" src="" alt="product thumb">
                        </button>
                        <button class="owl-thumb-item">
                            <img id="thumb-2" src="" alt="product thumb">
                        </button>
                        <button class="owl-thumb-item">
                            <img id="thumb-3" src="" alt="product thumb">
                        </button>
                        <button class="owl-thumb-item">
                            <img id="thumb-4" src="" alt="product thumb">
                        </button>
                    </div>
                    <!-- .owl-thumbs end -->
                </div>
                <!-- .products-gallery-carousel end -->
            </div>
            <!-- .col-lg-6 end -->
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="product--title">
                    <h3>Hebes Living Wood Sofa</h3>
                </div>
                <!-- .product-title end -->
                <div class="product--rating">
                    <i class="fa fa-star active"></i>
                    <i class="fa fa-star active"></i>
                    <i class="fa fa-star active"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <div class="product--review">03 Customer Review</div>
                <!--- .product-review end -->
                <div class="product--price">$ 42.00</div>
                <!-- .product-price end -->
                <div class="product--desc-tabs tabs">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a href="#product--desc-tabs-1" role="tab" data-toggle="tab" class="active">INFO GUIDE</a></li>
                        <li><a href="#product--desc-tabs-2" role="tab" data-toggle="tab">SHIPPING</a></li>
                        <li><a href="#product--desc-tabs-3" role="tab" data-toggle="tab">RETURN</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade show active" id="product--desc-tabs-1">
                            <div class="product--desc">
                                <p>it's more than that. We offer integral communication services, and we're responsible for our process and results. We thank each client and their projects; thanks to them we have grown and built what we are today!</p>
                            </div>
                        </div>
                        <!-- .tab-pane end -->
                        <div role="tabpanel" class="tab-pane fade" id="product--desc-tabs-2">
                            <div class="product--desc">
                                <p>Sed id interdum urna. Nam ac elit a ante commodo tristique. tum vehicula a hendrerit ac nisi. hendrerit ac nisi Lorem ipsum dolor sit perdiet nibh vel magna lacinia ultrices. Sed id interdum urna.</p>
                            </div>
                        </div>
                        <!-- .tab-pane end -->
                        <div role="tabpanel" class="tab-pane fade" id="product--desc-tabs-3">
                            <div class="product--desc">
                                <p>Sed id interdum urna. Nam ac elit a ante commodo tristique. tum vehicula a hendrerit ac nisi. hendrerit ac nisi Lorem ipsum dolor sit perdiet nibh vel magna lacinia ultrices. Sed id interdum urna.</p>
                            </div>
                        </div>
                        <!-- .tab-pane end -->
                    </div>
                    <!-- #tab-content end -->
                </div>
                <!-- .product-desc-tabs end -->
                <div class="product--meta">
                    <div class="product--meta-select3">
                        <form class="mb-30">
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="select--box">
                                        <i class="fa fa-caret-down"></i>
                                        <select class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="select--box">
                                        <i class="fa fa-caret-down"></i>
                                        <select class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- .product-meta-select end -->
                    <ul class="product--meta-info list-unstyled">
                        <li>Availability:<span>In stock</span></li>
                        <li>SKU:<span>S3456</span></li>
                    </ul>
                    <div class="product--meta-action">
                        <div class="select-order">
                            <div class="product-quantity">
                                <input class="minus" type="button" value="-">
                                <input type="text" id="pro1-qunt" value="1" class="qty" readonly="">
                                <input class="plus" type="button" value="+">
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn--primary btn--rounded add-to-cart" data-product-id=""><i class="icon-bag"></i> ADD TO CART</a>
                        <a class="fav add-to-wishlist" data-product-id=""><i class="ti-heart"></i></a> <a href="#" class="compare" data-toggle="modal" data-target="#compare-popup"><i class="ti-control-shuffle"></i></a>
                    </div>
                    <div class="product--share">
                        <span class="share--title">Share</span>
                        <a class="share-facebook" href="#"><i class="fa fa-facebook"></i></a>
                        <a class="share-twitter" href="#"><i class="fa fa-twitter"></i></a>
                        <a class="share-google-plus" href="#"><i class="fa fa-pinterest-p"></i></a>
                        <a class="share-linkedin" href="#"><i class="fa fa-linkedin"></i></a>
                    </div>
                    <!-- .product-share end -->
                </div>
            </div>
            <!-- .col-lg-6 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- .product detalis end -->

<!-- product detalis #4
============================================= -->
<section id="product-detalis4" class="product-detalis product-detalis-2 product-detalis-4 pb-80 pt-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="product--tabs tabs">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a href="#description" aria-controls="description" role="tab" data-toggle="tab" class="active">description</a></li>
                        <li><a href="#addtional-info" aria-controls="addtional-info" role="tab" data-toggle="tab">Addtional info</a></li>
                        <li><a href="#reviews" aria-controls="reviews" role="tab" data-toggle="tab"></a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade show active" id="description">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-10 offset-lg-1">

                                    <div class="row">

                                        <div class="col-sm-12 col-md-12 col-lg-8">
                                            <div class="product--desc">
                                                <p></p>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-12 col-lg-4">
                                            <div class="product--desc-list">
                                                <h6>SIZE & FIT</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <p></p>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- .col-lg-8 end -->

                                    </div>
                                </div>
                            </div>
                            <!-- .row end -->
                        </div>
                        <!-- #description end -->
                        <div role="tabpanel" class="tab-pane fade" id="addtional-info">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-10 offset-lg-1">
                                    <div class="product--desc">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- #details end -->
                        <div role="tabpanel" class="tab-pane fade" id="reviews">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8 offset-lg-2">
                                    <ul class="product--review-comments list-unstyled">
                                        <?php
                                        // Include your database connection file
                                        include 'db_connection.php';

                                        // Assuming $productId contains the product ID passed from your PHP script
                                        $productId = $_GET['id']; // Example product ID

                                        // SQL query to fetch reviews for the product
                                        $sql = "SELECT pr.review_id, pr.review_text, pr.rating, pr.review_date, c.customer_name, c.customers_photo
                        FROM productreviews pr
                        INNER JOIN customers c ON pr.customer_id = c.customer_id
                        WHERE pr.product_id = ?";
                                        $stmt = mysqli_prepare($conn, $sql);
                                        mysqli_stmt_bind_param($stmt, "i", $productId);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);

                                        // Fetch and display each review
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<li class="review--comment">';
                                            echo '<div class="author--img" style="position: absolute; top: 0; left: 0; width: 70px; height: 70px; border-radius: 50%; overflow: hidden;">';
                                            echo '<img src="admin/' . $row['customers_photo'] . '" alt="author" style="width: 100%; height: 100%; object-fit: cover;">';
                                            echo '</div>';

                                            echo '<div class="review--comment-content">';
                                            echo '<div class="clearfix">';
                                            echo '<div class="pull-left">';
                                            echo '<h6>' . $row['customer_name'] . '</h6>';
                                            echo '<span class="review--date">' . date('F j, Y', strtotime($row['review_date'])) . '</span>';
                                            echo '</div>';
                                            echo '<div class="pull-right product--rating">';
                                            // Output star rating based on the rating value
                                            $rating = $row['rating'];
                                            for ($i = 0; $i < $rating; $i++) {
                                                echo '<i class="fa fa-star active"></i>';
                                            }
                                            echo '</div>';
                                            echo '</div>';
                                            echo '<div class="product--comment">';
                                            echo '<p>' . $row['review_text'] . '</p>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</li>';
                                        }

                                        // Close the statement and database connection

                                        ?>
                                    </ul>
                                    <!-- Add review form -->
                                    <!-- ... -->

                                    <?php
                                    // Check if the user is logged in
                                    if (isset($_SESSION['customer_email'])) {
                                        // Check if the user has already reviewed the product
                                        $productId = $_GET['id']; // Assuming product ID is passed via GET
                                        $customerEmail = $_SESSION['customer_email'];

                                        // Query the database to retrieve the customer ID based on the email
                                        $query = "SELECT customer_id FROM customers WHERE customer_email = ?";
                                        $stmt = mysqli_prepare($conn, $query);
                                        mysqli_stmt_bind_param($stmt, "s", $customerEmail);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        $row = mysqli_fetch_assoc($result);
                                        $customerId = $row['customer_id'];

                                        // Prepare the query
                                        $query = "SELECT * FROM productreviews WHERE product_id = ? AND customer_id = ?";
                                        $stmt = mysqli_prepare($conn, $query);

                                        // Bind parameters and execute the statement
                                        mysqli_stmt_bind_param($stmt, "ii", $productId, $customerId);
                                        mysqli_stmt_execute($stmt);

                                        // Get the result
                                        $result = mysqli_stmt_get_result($stmt);

                                        // Check if the query executed successfully
                                        if ($result) {
                                            // If the user has already reviewed the product
                                            if (mysqli_num_rows($result) > 0) {
                                                // Fetch the review details
                                                $reviewData = mysqli_fetch_assoc($result);
                                                $reviewId = $reviewData['review_id'];
                                                $rating = $reviewData['rating'];
                                                $reviewText = $reviewData['review_text'];

                                                // Display the edit review form
                                    ?>
                                                <script>
                                                    function setRating(rating) {
                                                        document.getElementById('rating').value = rating;
                                                    }
                                                </script>

                                                <section id="add-review-section">
                                                    <div class="form--review-rating text-center">
                                                        <h5>Edit Your Review</h5>
                                                        <div class="form--review-rating-content">
                                                            <span>Your Rating</span>
                                                            <div class="product--rating">
                                                                <!-- Output star rating selector with the user's previous rating pre-selected -->
                                                                <?php if ($rating == 1) { ?>
                                                                    <a href="#" onclick="setRating(1)"><i class="fa fa-star active"></i>&nbsp;</a>
                                                                <?php } else { ?>
                                                                    <a href="#" onclick="setRating(1)"><i class="fa fa-star"></i>&nbsp;</a>
                                                                <?php } ?>
                                                                <?php if ($rating == 2) { ?>
                                                                    <a href="#" onclick="setRating(2)"><i class="fa fa-star active"></i><i class="fa fa-star active"></i>&nbsp;</a>
                                                                <?php } else { ?>
                                                                    <a href="#" onclick="setRating(2)"><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                                <?php } ?>
                                                                <?php if ($rating == 3) { ?>
                                                                    <a href="#" onclick="setRating(3)"><i class="fa fa-star active"></i><i class="fa fa-star active"></i><i class="fa fa-star active"></i>&nbsp;</a>
                                                                <?php } else { ?>
                                                                    <a href="#" onclick="setRating(3)"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                                <?php } ?>
                                                                <?php if ($rating == 4) { ?>
                                                                    <a href="#" onclick="setRating(4)"><i class="fa fa-star active"></i><i class="fa fa-star active"></i><i class="fa fa-star active"></i><i class="fa fa-star active"></i>&nbsp;</a>
                                                                <?php } else { ?>
                                                                    <a href="#" onclick="setRating(4)"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                                <?php } ?>
                                                                <?php if ($rating == 5) { ?>
                                                                    <a href="#" onclick="setRating(5)"><i class="fa fa-star active"></i><i class="fa fa-star active"></i><i class="fa fa-star active"></i><i class="fa fa-star active"></i><i class="fa fa-star active"></i>&nbsp;</a>
                                                                <?php } else { ?>
                                                                    <a href="#" onclick="setRating(5)"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form--review">
                                                        <form method="post" action="edit_review.php">
                                                            <input type="hidden" name="review_id" value="<?php echo $reviewId; ?>">
                                                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                                            <!-- Include the rating field -->
                                                            <input type="hidden" name="rating" id="rating" value="<?php echo $rating; ?>">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                                    <textarea class="form-control" name="review_text" rows="2" placeholder="Edit Your Review"><?php echo $reviewText; ?></textarea>
                                                                </div>
                                                                <div class="col-sm-12 col-md-12 col-lg-12 text--center">
                                                                    <button type="submit" class="btn btn--primary btn--rounded">Update<i class="lnr lnr-arrow-right"></i></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </section>




                                            <?php
                                            } else {
                                                // Display the add review form
                                            ?>
                                                <script>
                                                    function setRating(rating) {
                                                        document.getElementById('rating').value = rating;
                                                    }
                                                </script>

                                                <section id="add-review-section">
                                                    <div class="form--review-rating text-center">
                                                        <h5>Add Your Review</h5>
                                                        <div class="form--review-rating-content">
                                                            <span>Your Rating</span>
                                                            <div class="product--rating">
                                                                <!-- Output star rating selector with JavaScript function call -->
                                                                <a href="#" onclick="setRating(1)"><i class="fa fa-star"></i>&nbsp;</a>
                                                                <a href="#" onclick="setRating(2)"><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                                <a href="#" onclick="setRating(3)"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                                <a href="#" onclick="setRating(4)"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                                <a href="#" onclick="setRating(5)"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form--review">
                                                        <form method="post" action="add_review.php">
                                                            <div class="row">
                                                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                                                <!-- Include hidden input field for rating -->
                                                                <input type="hidden" name="rating" id="rating" value="0">
                                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                                    <textarea class="form-control" name="review_text" rows="2" placeholder="Add Your Review"></textarea>
                                                                </div>
                                                                <div class="col-sm-12 col-md-12 col-lg-12 text--center">
                                                                    <button type="submit" class="btn btn--primary btn--rounded">Submit<i class="lnr lnr-arrow-right"></i></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </section>



                                    <?php
                                            }
                                        } else {
                                            // Handle the case where the query execution fails
                                            echo 'Error executing query: ' . mysqli_error($conn);
                                        }

                                        // Close the statement
                                        mysqli_stmt_close($stmt);
                                    }
                                    ?>



                                </div>
                                <!-- .col-lg-8 end -->
                            </div>
                            <!-- .row end -->
                        </div>
                        <!-- .container end -->
</section>
<!-- .product detalis end -->

<!--  products carousel 
============================================= -->
<section id="products-carousel" class="products-carousel related-products pt-0 pb-80">
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="heading text-center mb-50">
                    <h2 class="heading--title">Recent Products</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="carousel owl-carousel carousel-dots" data-slide="4" data-slide-rs="2" data-autoplay="true" data-nav="false" data-dots="true" data-space="15" data-loop="true" data-speed="800">
                    <?php
                    // Fetch products for the current category
                    $sql = "SELECT * FROM products ORDER BY product_id DESC LIMIT 8";
                    $result = mysqli_query($conn, $sql);
                    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    // Initialize an array to keep track of unique product names for color variations
                    $uniqueProductNames = array();

                    // Iterate over each product in the category
                    foreach ($products as $product) : ?>
                        <!-- Product item -->
                        <div class="product-item style2">
                            <div class="product--img">
                            <img src="<?= strpos($product['product_photo'], 'http') === 0 ? $product['product_photo'] : 'admin/' . $product['product_photo']; ?>" alt="<?= $product['product_name']; ?>" style="width: 300px; height: 300px; object-fit: cover;">
                                <?php if (isset($product['product_tag']) && !empty($product['product_tag'])) : ?>
                                    <?php if ($product['product_tag'] === 'Sale' && isset($product['sale_start_date']) && isset($product['sale_end_date'])) : ?>
                                        <?php
                                        // Get the current date
                                        $currentDate = date("Y-m-d");

                                        // Check if the current date is within the sale period
                                        if ($currentDate >= $product['sale_start_date'] && $currentDate <= $product['sale_end_date']) {
                                            // If the current date is within the sale period, display the "Sale" tag
                                        ?>
                                            <span class="featured-item featured-item2"><?= $product['product_tag']; ?></span>
                                        <?php } else { ?>
                                            <!-- Add additional conditions or alternative display here -->
                                        <?php } ?>
                                    <?php else : ?>
                                        <!-- Default behavior when product tag is not "Sale" -->
                                        <span class="featured-item featured-item2"><?= $product['product_tag']; ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>

                            </div>
                            <div class="product--content">
                                <div class="product--title">
                                    <h3><a href="#"><?= $product['product_name']; ?></a></h3>
                                </div>
                                <div class="product--price">
                                    <?php $currentDate = date("Y-m-d");
                                    if ($product['product_tag'] === 'Sale' && $currentDate >= $product['sale_start_date'] && $currentDate <= $product['sale_end_date']) : ?>
                                        <!-- Display sale price if product is on sale -->
                                        <div class="sale-wrapper">
                                            <span class="original-price">$<?= $product['product_price']; ?></span> <!-- Original price -->
                                            <span class="sale-price">$<?= $product['product_sale_price']; ?></span> <!-- Sale price -->
                                            <span class="sale-badge">Sale</span> <!-- Sale badge -->
                                        </div>
                                    <?php else : ?>
                                        <!-- Display regular price if product is not on sale -->
                                        <span>$<?= $product['product_price']; ?></span> <!-- Regular price -->
                                    <?php endif; ?>
                                </div>

                            </div>
                            <script>
                                $(document).ready(function() {
                                    // Add event listener to the modal close event
                                    $('#product-popup').on('hidden.bs.modal', function() {
                                        // Reload the page
                                        location.reload();
                                    });
                                });
                            </script>
                            <div class="product--hover">
                                <div class="product--action">
                                    <?php if ($product['product_stock_quantity'] > 0) : ?>
                                        <!-- If product is in stock, display the regular "ADD TO CART" button -->
                                        <a href="javascript:void(0);" class="btn btn--primary btn--rounded add-to-cart-index" data-product-id="<?= $product['product_id']; ?>"><i class="icon-bag"></i> ADD TO CART</a>
                                    <?php else : ?>
                                        <!-- If product is out of stock, display the disabled "OUT OF STOCK" button -->
                                        <span class="btn btn--primary btn--rounded" style="cursor: not-allowed; opacity: 0.7;"><i class="icon-bag"></i> OUT OF STOCK</span>
                                    <?php endif; ?>
                                    <div class="product--action-content">
                                        <div class="product--action-icons">
                                            <a data-toggle="modal" data-target="#product-popup" data-product-id="<?= $product['product_id']; ?>"><i class="ti-search"></i></a>
                                            <a class="add-to-wishlist" data-product-id="<?= $product['product_id']; ?>"><i class="ti-heart"></i></a>
                                            <a data-toggle="modal" data-target="#compare-popup"><i class="ti-control-shuffle"></i></a>
                                        </div>
                                        <div class="product--hover-info">
                                            <div class="product--title">
                                                <h3><a href="http://localhost/msport/product.php?id=<?= $product['product_id']; ?>" target="_blank"><?= $product['product_name']; ?></a></h3>
                                            </div>
                                            <div class="product--price">
                                                <?php
                                                if ($product['product_tag'] === 'Sale' && $currentDate >= $product['sale_start_date'] && $currentDate <= $product['sale_end_date']) : ?>
                                                    <!-- If the product is on sale and the current date is within the sale period -->
                                                    <span class="original-price" style="text-decoration: line-through;">$<?php echo $product['product_price']; ?></span>
                                                    <span class="sale-price">$<?php echo $product['product_sale_price']; ?></span>
                                                <?php else : ?>
                                                    <!-- If the product is not on sale or the current date is not within the sale period -->
                                                    <span>$<?php echo $product['product_price']; ?></span>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                        <div class="product--colors">
                                            <?php
                                            // Display color boxes for all products with the same name
                                            if (!in_array($product['product_name'], $uniqueProductNames)) {
                                                $keywords = explode(",", $product['product_keywords']);
                                                $color_keywords = array("red", "blue", "green", "yellow", "black", "white");

                                                // Prepare the SQL statement
                                                $same_name_sql = "SELECT * FROM products WHERE product_name = ?";

                                                // Initialize an array to store color variations for the current product
                                                $product_colors = array();

                                                // Prepare and execute the statement
                                                if ($stmt = mysqli_prepare($conn, $same_name_sql)) {
                                                    // Bind the parameter
                                                    mysqli_stmt_bind_param($stmt, "s", $product['product_name']);

                                                    // Execute the statement
                                                    mysqli_stmt_execute($stmt);

                                                    // Get the result set
                                                    $same_name_result = mysqli_stmt_get_result($stmt);

                                                    // Fetch rows
                                                    while ($same_name_row = mysqli_fetch_assoc($same_name_result)) {
                                                        $same_name_keywords = explode(",", $same_name_row['product_keywords']);
                                                        foreach ($same_name_keywords as $same_name_keyword) {
                                                            foreach ($color_keywords as $color) {
                                                                if (stripos($same_name_keyword, $color) !== false) {
                                                                    // Store color variations along with product IDs in an array
                                                                    $product_colors[$same_name_row['product_id']][] = strtolower($color);
                                                                }
                                                            }
                                                        }
                                                    }

                                                    // Close the statement
                                                    mysqli_stmt_close($stmt);
                                                }

                                                // Output color boxes
                                                foreach ($product_colors as $product_id => $colors) {
                                                    foreach ($colors as $color) {
                                                        echo '<a href="#" class="color-' . strtolower($color) . '"></a>';
                                                    }
                                                }

                                                // Add the product name to the unique product names array
                                                $uniqueProductNames[] = $product['product_name'];
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Product item -->
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- .related products end -->


<?php
include("footer.php");
?>