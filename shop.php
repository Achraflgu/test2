<?php

session_start();

include("db_connection.php");
include("header.php");
include("nav.php");


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Number of products per page
        var productsPerPage = 10;

        // Total number of products
        var totalProducts = $('.category-item').length;

        // Calculate the total number of pages
        var totalPages = Math.ceil(totalProducts / productsPerPage);

        // Initially show the first page
        showPage(1);

        // Function to show products for a specific page
        function showPage(pageNumber) {
            // Hide all products
            $('.category-item').hide();

            // Calculate the range of products to display for the given page
            var startIndex = (pageNumber - 1) * productsPerPage;
            var endIndex = Math.min(startIndex + productsPerPage, totalProducts);

            // Show the products within the calculated range
            for (var i = startIndex; i < endIndex; i++) {
                $('.category-item').eq(i).show();
            }
        }

        // Function to generate pagination links
        function generatePagination() {
            var paginationHTML = '<li><a href="#" aria-label="previous"><i class="fa fa-chevron-left"></i></a></li>';

            for (var i = 1; i <= totalPages; i++) {
                paginationHTML += '<li><a href="#">' + i + '</a></li>';
            }

            paginationHTML += '<li><a href="#" aria-label="Next"><i class="fa fa-chevron-right"></i></a></li>';

            $('.pagination').html(paginationHTML);

            // Initially set the first page as active
            $('.pagination li').eq(1).addClass('active');

            // Handle pagination click events
            $('.pagination li a').on('click', function(e) {
                e.preventDefault();

                var page = $(this).text();

                // If the clicked page is the previous page button
                if ($(this).attr('aria-label') === 'previous') {
                    var currentPage = parseInt($('.pagination li.active a').text());
                    page = currentPage - 1;
                    if (page < 1) page = 1;
                }

                // If the clicked page is the next page button
                if ($(this).attr('aria-label') === 'Next') {
                    var currentPage = parseInt($('.pagination li.active a').text());
                    page = currentPage + 1;
                    if (page > totalPages) page = totalPages;
                }

                // Show the products for the selected page
                showPage(page);

                // Update active page indicator
                $('.pagination li').removeClass('active');
                $('.pagination li').eq(page).addClass('active');
            });
        }

        // Generate pagination links
        generatePagination();
    });
</script>
<script>
    $(document).ready(function() {
        // Parse URL parameters
        var urlParams = new URLSearchParams(window.location.search);
        var subcategory = urlParams.get('subcategory');
        var category = urlParams.get('category');

        // Filter based on subcategory and category if they exist in the URL
        if (subcategory !== null) {
            filterBySubcategory(subcategory, category);
        } else if (category !== null) {
            filterByCategory(category);
        }
    });

    function updateURLParameter(url, param, value) {
        var re = new RegExp("([?&])" + param + "=.*?(&|$)", "i");
        var separator = url.indexOf('?') !== -1 ? "&" : "?";
        if (url.match(re)) {
            return url.replace(re, '$1' + param + "=" + value + '$2');
        } else {
            return url + separator + param + "=" + value;
        }
    }

    function filterByBrand(brandId) {
        // Update URL with brand parameter
        var currentURL = window.location.href;
        var newURL = updateURLParameter(currentURL, 'brand', brandId);
        window.history.pushState({
            path: newURL
        }, '', newURL);

        $('.category-item').each(function() {
            var brand = $(this).data('brand');
            if (brand == brandId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $(document).ready(function() {
        var $sliderRange = $("#slider-range"),
            $sliderAmount = $("#amount");

        // Initialize the slider
        $sliderRange.slider({
            range: true,
            min: 0,
            max: 500,
            values: [50, 300],
            slide: function(event, ui) {
                // Update the displayed price range as the slider is moved
                $sliderAmount.val("$" + ui.values[0] + " - $" + ui.values[1]);
                // Call the filterByPrice function to filter products based on the selected price range
                filterByPrice(ui.values[0], ui.values[1]);
            }
        });

        // Set the initial value of the displayed price range
        $sliderAmount.val("$" + $sliderRange.slider("values", 0) + " - $" + $sliderRange.slider("values", 1));
    });

    // Function to filter products based on price
    // Function to filter products based on price
    function filterByPrice(minPrice, maxPrice) {
        // Check if any other filters are active
        var categoryFilterActive = false;
        var brandFilterActive = false;
        var subcategoryFilterActive = false;

        // Check if a category filter is active
        if (new URLSearchParams(window.location.search).get('category') !== null) {
            categoryFilterActive = true;
        }

        // Check if a brand filter is active
        if (new URLSearchParams(window.location.search).get('brand') !== null) {
            brandFilterActive = true;
        }

        // Check if a subcategory filter is active
        if (new URLSearchParams(window.location.search).get('subcategory') !== null) {
            subcategoryFilterActive = true;
        }

        // Filter products based on price and active filters
        $('.category-item').each(function() {
            var price = parseFloat($(this).data('price'));
            var category = $(this).data('category');
            var brand = $(this).data('brand');
            var keywords = $(this).data('keywords');

            // Check if the product's price is within the selected range
            var priceFilterPassed = price >= minPrice && price <= maxPrice;

            // Check if the product's category matches the selected category filter (if active)
            var categoryFilterPassed = !categoryFilterActive || (category == new URLSearchParams(window.location.search).get('category'));

            // Check if the product's brand matches the selected brand filter (if active)
            var brandFilterPassed = !brandFilterActive || (brand == new URLSearchParams(window.location.search).get('brand'));

            // Check if the product's keywords contain the selected subcategory (if active)
            var subcategoryFilterPassed = !subcategoryFilterActive || (keywords && keywords.indexOf(new URLSearchParams(window.location.search).get('subcategory')) !== -1);

            // Show or hide the product based on the combined filter conditions
            if (priceFilterPassed && categoryFilterPassed && brandFilterPassed && subcategoryFilterPassed) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }


    function filterByCategory(categoryId) {
        // Update URL with category parameter
        var currentURL = window.location.href;
        var newURL = updateURLParameter(currentURL, 'category', categoryId);
        window.history.pushState({
            path: newURL
        }, '', newURL);

        // Hide all products
        $('.category-item').hide();
        // Show products belonging to the selected category
        $('.category-item[data-category="' + categoryId + '"]').show();
    }

    function clearAllFilters() {
        // Show all category items
        $('.category-item').show();
        // Clear any selected category or subcategory
        $('.category-item').removeClass('selected');
        // Clear any selected brand
        $('.brand-item').removeClass('selected');

        // Clear URL parameters
        var url = window.location.href;
        var cleanUrl = url.split("?")[0]; // Get the base URL without parameters
        window.history.replaceState({}, document.title, cleanUrl); // Replace the URL without parameters
    }

    function filterBySubcategory(subcategoryName, categoryId = null) {
        // Update URL with subcategory parameter
        var currentURL = window.location.href;
        var newURL = updateURLParameter(currentURL, 'subcategory', subcategoryName);
        if (categoryId !== null) {
            newURL = updateURLParameter(newURL, 'category', categoryId);
        }
        window.history.pushState({
            path: newURL
        }, '', newURL);

        $('.category-item').each(function() {
            var keywords = $(this).data('keywords');
            var category = $(this).data('category');
            if (keywords && keywords.indexOf(subcategoryName) !== -1) {
                if (categoryId === null || category == categoryId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                $(this).hide();
            }
        });
    }
</script>


<!-- Page Title #1
============================================= -->
<section id="page-title" class="page-title bg-parallax">
    <div class="bg-section">
        <img src="assets/images/page-title/1.jpg" alt="background">
    </div>
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
                    <ol class="breadcrumb breadcrumb-bottom">
                        <li><a href="index-2.html">Home</a></li>
                        <li class="active">Shop Categories</li>
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

<!-- category #16
============================================= -->
<section id="category16" class="category category-3 category-6 category-13 category-16 pt-50 pb-80">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-3">
                <div class="sidebar cat-sidebar">

                    <!-- Categories
    ============================= -->
                    <div class="widget widget-categories2">
                        <div class="widget--title">
                            <h3>Categories</h3>
                        </div>
                        <div class="widget--content">
                            <ul class="main--list list-unstyled mb-0">
                                <?php
                                // Fetch categories from the database
                                $category_sql = "SELECT * FROM productcategories";
                                $category_result = mysqli_query($conn, $category_sql);

                                if (mysqli_num_rows($category_result) > 0) {
                                    while ($category_row = mysqli_fetch_assoc($category_result)) {
                                        $pcategory_id = $category_row['pcategory_id'];
                                        $pcategory_name = $category_row['pcategory_name'];

                                        // Count the number of products under this category
                                        $product_count_sql = "SELECT COUNT(*) AS product_count 
                                          FROM products 
                                          WHERE pcategory_id = $pcategory_id";
                                        $product_count_result = mysqli_query($conn, $product_count_sql);
                                        $product_count_row = mysqli_fetch_assoc($product_count_result);
                                        $product_count = $product_count_row['product_count'];
                                ?>
                                        <li>
                                            <a href="javascript:void(0);" onclick="filterByCategory(<?php echo $pcategory_id; ?>)">
                                                <?php echo $pcategory_name; ?>
                                                <span>(<?php echo $product_count; ?>)</span>
                                            </a>
                                            <ul class="inner--list list-unstyled mb-0">
                                                <?php
                                                // Fetch subcategories for this category from product_keywords
                                                $subcategory_sql = "SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(REPLACE(product_keywords, '[', ''), ']', ''), ',', n.digit+1), ',', -1) AS subcategory
                                                FROM products
                                                INNER JOIN (
                                                    SELECT 0 AS digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                                                ) AS n
                                                WHERE pcategory_id = $pcategory_id
                                                    AND product_keywords LIKE '%f-%'";
                                                $subcategory_result = mysqli_query($conn, $subcategory_sql);
                                                while ($subcategory_row = mysqli_fetch_assoc($subcategory_result)) {
                                                    // Extract the subcategory name and remove any quotes
                                                    $subcategory_name = trim($subcategory_row['subcategory'], '"');
                                                    // If subcategory starts with 'f-', remove it
                                                    if (strpos($subcategory_name, 'f-') === 0) {
                                                        $subcategory_name = substr($subcategory_name, 2);
                                                        // Count the number of products for this subcategory
                                                        $subcategory_product_count_sql = "SELECT COUNT(*) AS subcategory_product_count 
                                                                      FROM products 
                                                                      WHERE pcategory_id = $pcategory_id 
                                                                      AND product_keywords LIKE '%$subcategory_name%'";
                                                        $subcategory_product_count_result = mysqli_query($conn, $subcategory_product_count_sql);
                                                        $subcategory_product_count_row = mysqli_fetch_assoc($subcategory_product_count_result);
                                                        $subcategory_product_count = $subcategory_product_count_row['subcategory_product_count'];
                                                        echo '<li><a href="javascript:void(0);" onclick="filterBySubcategory(\'' . $subcategory_name . '\', ' . $pcategory_id . ')">' . $subcategory_name . '<span>(' . $subcategory_product_count . ')</span></a></li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                <?php
                                    }
                                } else {
                                    echo '<li>No categories found</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <!-- .widget-categories end -->

                    <!-- Widget Filter
    ============================================= -->
                    <div class="widget widget-filter">
                        <div class="widget--title">
                            <h3>Filter By</h3>
                        </div>
                        <div class="widget--content">
                            <div class="category--filter">
                                <h4 class="subtitle mt-0">price</h4>
                                <div id="slider-range"></div>
                                <p>
                                    <input type="text" id="amount" readonly>
                                </p>
                            </div>
                            <div class="brands--fiter">
                                <h4 class="subtitle">Brands</h4>
                                <ul class="list-unstyled mb-0">
                                    <?php
                                    // Fetch brands from the database
                                    $brand_sql = "SELECT * FROM brands";
                                    $brand_result = mysqli_query($conn, $brand_sql);

                                    if (mysqli_num_rows($brand_result) > 0) {
                                        while ($brand_row = mysqli_fetch_assoc($brand_result)) {
                                            $brand_id = $brand_row['brand_id'];
                                            $brand_name = $brand_row['brand_name'];

                                            // Count the number of products for this brand
                                            $product_count_sql = "SELECT COUNT(*) AS product_count 
                    FROM products 
                    WHERE brand_id = $brand_id";
                                            $product_count_result = mysqli_query($conn, $product_count_sql);
                                            $product_count_row = mysqli_fetch_assoc($product_count_result);
                                            $product_count = $product_count_row['product_count'];
                                    ?>
                                            <li><a href="javascript:void(0);" onclick="filterByBrand(<?php echo $brand_id; ?>)"><?php echo $brand_name; ?><span>(<?php echo $product_count; ?>)</span></a></li>
                                    <?php
                                        }
                                    } else {
                                        echo '<li>No brands found</li>';
                                    }
                                    ?>
                                </ul>
                            </div>


                        </div>
                    </div><!-- .widget-filter end -->

                </div>
            </div>
            <!-- .col-lg-3 end -->
            <div class="col-sm-12 col-md-12 col-lg-9">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 category-options">
                        <div class="category-num pull-left pull-none-xs">
                            <?php
                            // SQL query to count the number of products
                            $count_query = "SELECT COUNT(*) AS total_products FROM products";
                            $count_result = mysqli_query($conn, $count_query);

                            if ($count_result) {
                                // Fetch the count from the result
                                $row = mysqli_fetch_assoc($count_result);
                                $total_products = $row['total_products'];
                            } else {
                                // Error handling if the query fails
                                $total_products = 0;
                            }

                            // Display the count
                            echo "<h2><span>{$total_products}</span> PRODUCTS FOUND</h2>";
                            ?>
                        </div>
                        <!-- .category-num end -->
                        <div class="category-select pull-right text-right text-left-sm pull-none-xs">
                            <ul class="list-unstyled mb-0">
                                <li class="option sort--options">
                                    <span class="option--title">Sort by:</span>
                                    <div class="select-form">
                                        <i class="fa fa-caret-down"></i>
                                        <select>
                                            <option selected="" value="Default">name</option>
                                            <option value="color">color</option>
                                            <option value="price">price</option>
                                            <option value="branding">branding</option>
                                        </select>
                                    </div>
                                </li>
                                <li class="option">
                                    <span class="option--title">SHOW</span>
                                    <ul class="list-unstyled show--num">
                                        <li>2</li>
                                        <li>4</li>
                                        <li>6</li>
                                    </ul>
                                </li>
                                <li class="option view--type">
                                    <a onclick="clearAllFilters()" id="clear-filters" class=""><i class="fa fa-times-circle"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- .category-options end -->
                </div>
                <!-- .row end -->
                <div class="row mb-60">
                    <?php
                    // Fetch products from the database
                    $sql = "SELECT * FROM products";
                    $result = mysqli_query($conn, $sql);

                    // Initialize an array to keep track of unique product names
                    $uniqueProductNames = array();

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Check if the product name is already encountered
                            if (!in_array($row['product_name'], $uniqueProductNames)) {
                                // Add the product name to the unique product names array
                                $uniqueProductNames[] = $row['product_name'];

                                // Escape the product name to handle single quotes
                                $product_name = mysqli_real_escape_string($conn, $row['product_name']);

                                // Fetch all products with the same name and check their keywords for color names
                                $same_name_sql = "SELECT * FROM products WHERE product_name='$product_name'";
                                $same_name_result = mysqli_query($conn, $same_name_sql);
                                $color_keywords = array("red", "blue", "green", "yellow", "black", "white");

                                // Start the category filter functionality by adding data attributes to each product
                                echo '<div class="col-sm-6 col-md-6 col-lg-3 category-item"';
                                echo ' data-category="' . $row['pcategory_id'] . '"';
                                echo ' data-brand="' . $row['brand_id'] . '"';
                                echo ' data-keywords=\'' . htmlspecialchars(json_encode($row['product_keywords'])) . '\'';
                                echo ' data-price="' . $row['product_price'] . '"';

                                while ($same_name_row = mysqli_fetch_assoc($same_name_result)) {
                                    $keywords = explode(",", $same_name_row['product_keywords']);
                                    foreach ($keywords as $keyword) {
                                        foreach ($color_keywords as $color) {
                                            if (stripos($keyword, $color) !== false) {
                                                echo ' data-subcategory="' . strtolower($color) . '"';
                                            }
                                        }
                                    }
                                }
                                echo '>';
                                $currentDate = date("Y-m-d");
                                $isSale = isset($row['product_tag']) && !empty($row['product_tag']) && stripos($row['product_tag'], 'Sale') !== false &&
                                    isset($row['sale_start_date']) && isset($row['sale_end_date']) &&
                                    $currentDate >= $row['sale_start_date'] && $currentDate <= $row['sale_end_date'];

                    ?>
                                <div class="category--img">
                                    <img src="<?php echo strpos($row['product_photo'], 'http') === 0 ? $row['product_photo'] : 'admin/' . $row['product_photo']; ?>" alt="category" style="width: 300px; height: 300px; object-fit: cover;">
                                    <?php if (isset($row['product_tag']) && !empty($row['product_tag'])) : ?>
                                        <?php if ($row['product_tag'] === 'Sale' && isset($row['sale_start_date']) && isset($row['sale_end_date'])) : ?>
                                            <?php
                                            // Get the current date

                                            // Check if the current date is within the sale period
                                            if ($currentDate >= $row['sale_start_date'] && $currentDate <= $row['sale_end_date']) {
                                                // If the current date is within the sale period, display the "Sale" tag
                                            ?>
                                                <span class="featured-item featured-item2"><?= $row['product_tag']; ?></span>
                                            <?php } else { ?>
                                                <!-- Add additional conditions or alternative display here -->
                                            <?php } ?>
                                        <?php else : ?>
                                            <!-- Default behavior when product tag is not "Sale" -->
                                            <span class="featured-item featured-item2"><?= $row['product_tag']; ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </div>
                                <div class="category--content">
                                    <div class="category--title">
                                        <h3><a href="#"><?php echo $row['product_name']; ?></a></h3>
                                    </div>
                                    <div class="category--price">
                                        <?php if ($isSale) : ?>
                                            <div class="sale-wrapper">
                                                <span class="original-price">$<?php echo $row['product_price']; ?></span>
                                                <span class="sale-price">$<?php echo $row['product_sale_price']; ?></span>
                                                <span class="sale-badge">Sale</span>
                                            </div>
                                        <?php else : ?>
                                            <span class="regular-price">$<?php echo $row['product_price']; ?></span>
                                        <?php endif; ?>
                                    </div>

                                </div>
                                <div class="category--hover">
                                    <div class="category--action">
                                        <?php
                                        // Check if there are multiple products with the same name
                                        if (mysqli_num_rows($same_name_result) > 1) {
                                            // If there are multiple products with the same name, show the modal link
                                        ?>
                                            <?php if ($row['product_stock_quantity'] > 0) : ?>
                                                <a data-toggle="modal" class="btn btn--primary btn--rounded" data-target="#product-popup" data-product-id="<?= $row['product_id']; ?>"><i class="icon-bag"></i> ADD TO CART</a>
                                            <?php else : ?>
                                                <span class="btn btn--primary btn--rounded" style="cursor: not-allowed; opacity: 0.7;"><i class="icon-bag"></i> OUT OF STOCK</span>
                                            <?php endif; ?>
                                        <?php } else { ?>
                                            <!-- If there's only one product with this name, show the regular link -->
                                            <?php if ($row['product_stock_quantity'] > 0) : ?>
                                                <a href="javascript:void(0);" class="btn btn--primary btn--rounded add-to-cart-index" data-product-id="<?= $row['product_id']; ?>"><i class="icon-bag"></i> ADD TO CART</a>
                                            <?php else : ?>
                                                <span class="btn btn--primary btn--rounded" style="cursor: not-allowed; opacity: 0.7;"><i class="icon-bag"></i> OUT OF STOCK</span>
                                            <?php endif; ?>
                                        <?php } ?>

                                        <div class="category--action-content">
                                            <div class="category--action-icons">
                                                <a data-toggle="modal" data-target="#product-popup" data-product-id="<?= $row['product_id']; ?>"><i class="ti-search"></i></a>
                                                <a class="add-to-wishlist" data-product-id="<?= $row['product_id']; ?>"><i class="ti-heart"></i></a>
                                                <a href="#" class="compare" data-toggle="modal" data-target="#compare-popup"><i class="ti-control-shuffle"></i></a>
                                            </div>
                                            <div class="category--hover-info">
                                                <div class="category--title">
                                                    <h3><a href="http://localhost/msport/product.php?id=<?= $row['product_id']; ?>"><?php echo $row['product_name']; ?></a></h3>
                                                </div>
                                                <div class="category--price">
                                                    <?php if ($isSale) : ?>
                                                        <span class="original-price" style="text-decoration: line-through;">$<?php echo $row['product_price']; ?></span>
                                                        <span class="sale-price">$<?php echo $row['product_sale_price']; ?></span>
                                                    <?php else : ?>
                                                        <span>$<?php echo $row['product_price']; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="category--colors">
                                                <?php
                                                // Display color boxes
                                                $same_name_result = mysqli_query($conn, $same_name_sql);
                                                while ($same_name_row = mysqli_fetch_assoc($same_name_result)) {
                                                    $keywords = explode(",", $same_name_row['product_keywords']);
                                                    foreach ($keywords as $keyword) {
                                                        foreach ($color_keywords as $color) {
                                                            if (stripos($keyword, $color) !== false) {
                                                                echo '<div class="color-box circular" style="background-color: ' . strtolower($color) . ';" data-product-id="' . $same_name_row['product_id'] . '" data-toggle="modal" data-target="#product-popup"></div>';
                                                                echo '<div class="product-photo-popup" style="display: none;"></div>';
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
    <?php
                            }
                        }
                    } else {
                        echo "No products found";
                    }
    ?>

            </div>



            <!-- .row end -->

        </div>
        <!-- .col-lg-9 end -->
    </div>
    <!-- .row end -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 clearfix text--center">
            <ul class="pagination">
                <!-- Pagination links will be dynamically generated here -->
            </ul>
        </div>
    </div>

    <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #category end -->
<?php
include("footer.php");
?>