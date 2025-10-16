<div class="preloader">
    <div class="loader-eclipse">
        <div class="loader-content"></div>
    </div>
</div> <!-- Document Wrapper
	============================================= -->
<div id="wrapperParallax" class="wrapper clearfix">
    <!-- Show in desktop Onky -->
    <header id="navbar-spy1" class="header header-1 header-light d-none d-xl-block">
        <nav id="primary-menu1" class="navbar navbar-expand-xl navbar-light">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img class="logo" src="assets/images/logo/logo-dark.png" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mr-auto">
                        <!-- home Menu -->
                        <li class="has-dropdown mega-dropdown active">
                            <a href="index.php" data-toggle="dropdown" class="dropdown-toggle menu-item">Home</a>

                            <!-- .mega-dropdown-menu end -->
                        </li><!-- li end -->

                        <!-- shop Menu -->
                        <li class="has-dropdown mega-dropdown">
                            <a href="shop.php" class="dropdown-toggle menu-item">Shop</a>
                            <ul class="dropdown-menu mega-dropdown-menu collections-menu">
                                <div class="container">
                                    <div class="row">
                                        <?php
                                        // Fetch categories from the database
                                        $category_sql = "SELECT * FROM productcategories WHERE pcategory_status = 1";
                                        $category_result = mysqli_query($conn, $category_sql);

                                        if (mysqli_num_rows($category_result) > 0) {
                                            while ($category_row = mysqli_fetch_assoc($category_result)) {
                                                $pcategory_id = $category_row['pcategory_id'];
                                                $pcategory_name = $category_row['pcategory_name'];
                                                $pcategory_photo = $category_row['pcategory_photo'];
                                        ?>
                                                <div class="col-md-12 col-lg-5ths">
                                                    <a href="shop.php?category=<?php echo $pcategory_id; ?>">
                                                        <li>
                                                            <div class="collection--menu-content">
                                                                <h5><?php echo $pcategory_name; ?></h5>
                                                                <ul>
                                                                    <?php
                                                                    // Fetch subcategories for this category from product_keywords
                                                                    $subcategory_sql = "SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(REPLACE(product_keywords, '[', ''), ']', ''), ',', n.digit+1), ',', -1) AS subcategory
                                            FROM products
                                            INNER JOIN (
                                            SELECT 0 AS digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) AS n
                                            WHERE pcategory_id = $pcategory_id AND product_keywords LIKE '%f-%'";
                                                                    $subcategory_result = mysqli_query($conn, $subcategory_sql);
                                                                    while ($subcategory_row = mysqli_fetch_assoc($subcategory_result)) {
                                                                        // Extract the subcategory name and remove any quotes
                                                                        $subcategory_name = trim($subcategory_row['subcategory'], '"');
                                                                        // If subcategory starts with 'f-', remove it
                                                                        if (strpos($subcategory_name, 'f-') === 0) {
                                                                            $subcategory_name = substr($subcategory_name, 2);
                                                                    ?>
                                                                            <li>
                                                                                <a href="shop.php?subcategory=<?php echo $subcategory_name; ?>&category=<?php echo $pcategory_id; ?>">
                                                                                    <?php echo $subcategory_name; ?>
                                                                                </a>
                                                                            </li>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                            <div class="menu--img">
                                                                <img src="admin/<?php echo $pcategory_photo; ?>" alt="<?php echo $pcategory_name; ?>" class="img-fluid">
                                                            </div>
                                                        </li>
                                                    </a>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo '<li>No categories found</li>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </ul>
                            <!-- .mega-dropdown-menu end -->
                        </li><!-- li end -->

                        <!-- Pages Menu -->
                        <li>
                            <a href="http://localhost/msport/blog_list.php" class="link-hover">Blog</a>
                        </li>
                        <!-- li end -->

                        <!-- collection Menu -->

                        <!-- li end -->

                        <!-- Blog Menu-->
                        <li>
                            <a href="http://localhost/msport/contact.php" class="menu-item">Contact</a>
                        </li>
                        <!-- li end -->

                        <!-- features Menu -->
                        <li>
                            <a href="http://localhost/msport/about.php" class="link-hover">About Us</a>
                        </li>
                        <!-- li end -->
                    </ul>
                    <div class="module-container">
                        <!-- Module Search -->
                        <div class="module module-search pull-left">
                            <div class="module-icon search-icon">
                                <i class="lnr lnr-magnifier"></i>
                                <span class="title">Search</span>
                            </div>
                            <div class="module-content module--search-box">
                                <form class="form-search">
                                    <input type="text" class="form-control" placeholder="Search anything">
                                    <button type="submit"><span class="fa fa-arrow-right"></span></button>
                                </form><!-- .form-search end -->
                            </div>
                        </div><!-- .module-search end -->
                        <div class="vertical-divider pull-left mr-30"></div>
                        <div class="module module-lang pull-left">
                            <div class="module-icon">
                                <input type="checkbox" id="darkmode-toggle" class="toggle-checkbox sr-only">
                                <label for="darkmode-toggle" class="toggle">
                                    <span class="toggle-label">
                                        <span class="toggle-handle"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="module module-dropdown module-currency module-dropdown-right pull-left">
                            <div class="module-icon  dropdown">
                                <?php if (!isset($_SESSION['customer_email'])) : ?>
                                    <!-- Display Login/Register Button -->
                                    <a href="login.php" class="dropdown-toggle">LOGIN</a> | <a href="register.php" class="dropdown-toggle">REGISTER</a>
                                <?php else : ?>
                                    <!-- Display Customer's Photo with MY ACCOUNT Dropdown -->
                                    <?php
                                    // Fetch the customer's photo from the database
                                    // Replace 'your_database_connection' with your database connection code
                                    $customer_email = $_SESSION['customer_email'];
                                    $sql = "SELECT customers_photo FROM customers WHERE customer_email = '$customer_email'";
                                    $result = mysqli_query($conn, $sql);
                                    $row = mysqli_fetch_assoc($result);
                                    $customers_photo = $row['customers_photo'];
                                    ?>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" id="myAccountDropdown" data-toggle="dropdown">
                                            <!-- Display Customer's Photo -->
                                            <img src="admin/<?php echo $customers_photo; ?>" alt="Customer Photo" class="customer-photo mb-2" style="border-radius: 50%; width: 40px; height: 40px; object-fit: cover; border: 2px solid #ffffff;">
                                            <i class="fa fa-caret-down"></i>
                                        </button>
                                        <div class="module-dropdown-menu module-content" aria-labelledby="myAccountDropdown">
                                            <a class="dropdown-item" href="Settings.php">Settings</a>
                                            <a class="dropdown-item" href="wishlist.php">Wishlist</a>
                                            <a class="dropdown-item" href="logout.php">Logout</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                        <!-- Module Cart -->
                        <div class="module module-cart pull-left">
                            <div class="module-icon cart-icon">
                                <i class="icon-bag"></i>
                                <span class="title">shop cart</span>
                                <label class="module-label">0</label> <!-- Initial cart count set to 0 -->
                            </div>
                            <div class="module-content module-box cart-box">
                                <div class="cart-overview">
                                    <ul class="list-unstyled">
                                        <!-- Products will be dynamically added here -->
                                    </ul>
                                </div>
                                <div class="cart-total">
                                    <div class="total-desc">
                                        Sub total
                                    </div>
                                    <div class="total-price">
                                        $0.00 <!-- Initial total set to 0 -->
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="cart--control">
                                    <a class="btn btn--white btn--bordered btn--rounded" href="cart.php">view cart </a>
                                    <!-- <a class="btn btn--primary btn--rounded" href="#">Checkout</a>-->
                                </div>
                            </div>
                        </div>

                        <!-- .module-cart end -->
                    </div>
                </div>
                <!-- /.navbar-collapse -->
            </div>
        </nav>
    </header>

    <!-- Show in Mobile Only -->
    <header id="navbar-spy" class="header header-1 header-transparent d-block d-xl-none">
        <nav id="primary-menu" class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img class="logo" src="assets/images/logo/logo-dark.png" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mr-auto">
                        <!-- home Menu -->
                        <li class="has-dropdown mega-dropdown active">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle menu-item">Home</a>
                            <ul class="dropdown-menu mega-dropdown-menu">
                                <li>
                                    <div class="container">
                                        <div class="row">
                                            <!-- Column #1 -->
                                            <div class="col-md-12 col-lg-3">
                                                <ul>
                                                    <li>
                                                        <a href="home-1.html">Home 1</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-2.html">Home 2</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-3.html">Home 3</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-4.html">Home 4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- .col-lg-3 end -->

                                            <!-- Column #2 -->
                                            <div class="col-md-12 col-lg-3">
                                                <ul>
                                                    <li>
                                                        <a href="home-5.html">Home 5</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-6.html">Home 6</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-8.html">Home 7</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-8.html">Home 8</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- .col-lg-3 end -->

                                            <!-- Column #3 -->
                                            <div class="col-md-12 col-lg-3">
                                                <ul>
                                                    <li>
                                                        <a href="home-9.html">Home 9</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-10.html">Home 10</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-11.html">Home 11</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-12.html">Home 12</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- .col-lg-3 end -->

                                            <!-- Column #4 -->
                                            <div class="col-md-12 col-lg-3">
                                                <ul>
                                                    <li>
                                                        <a href="home-13.html">Home 13</a>
                                                    </li>
                                                    <li>
                                                        <a href="home-14.html">Home 14</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- .col-lg-3 end -->
                                        </div>
                                        <!-- .row end -->
                                    </div>
                                    <!-- container end -->
                                </li>
                            </ul>
                            <!-- .mega-dropdown-menu end -->
                        </li><!-- li end -->

                        <!-- shop Menu -->
                        <li class="has-dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle link-hover" data-hover="shop">shop</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Products Layout</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="shop-layout-fullwidth.html">fullwidth</a>
                                        </li>
                                        <li>
                                            <a href="shop-layout-sidebar-left.html">sidebar left</a>
                                        </li>
                                        <li>
                                            <a href="shop-layout-sidebar-right-2.html">sidebar right</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Products Columns</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="shop-4columns.html">4 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-3columns.html">3 columns</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Products Cards</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="shop-product-grid.html">grid</a>
                                        </li>
                                        <li>
                                            <a href="shop-product-hero-3columns.html">hero 3 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-product-hero-2columns.html">hero 2 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-product-list.html">list</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Products Dark</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="shop-dark-sidebar.html">with sidebar</a>
                                        </li>
                                        <li>
                                            <a href="shop-dark-3columns.html">3 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-dark-4columns.html">4 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-dark-5columns.html">5 columns</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Products Hover</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="shop-hover-2.html">hover 2</a>
                                        </li>
                                        <li>
                                            <a href="shop-hover-4columns.html">hover 4 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-hover-3columns.html">hover 3 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-hover-2columns.html">hover 2 columns</a>
                                        </li>
                                        <li>
                                            <a href="shop-hover-variation.html">hover variation</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Single Product</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="product-boxed.html">Product Boxed</a>
                                        </li>
                                        <li>
                                            <a href="product-carousel.html">Product Carousel</a>
                                        </li>
                                        <li>
                                            <a href="product-dark.html">Product Dark</a>
                                        </li>
                                        <li>
                                            <a href="product-fullwidth.html">Product Fullwidth</a>
                                        </li>
                                        <li>
                                            <a href="product-hero.html">Product Hero</a>
                                        </li>
                                        <li>
                                            <a href="product-masonry.html">Product Masonry</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Single Gallery</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="product-gallery.html">Gallery Fullwidth</a>
                                        </li>
                                        <li>
                                            <a href="product-gallery-horizontal.html">Gallery Horizontal</a>
                                        </li>
                                        <li>
                                            <a href="product-gallery-slide.html">Gallery Slide</a>
                                        </li>
                                        <li>
                                            <a href="product-gallery-vertical.html">Gallery Vertical</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li><!-- li end -->

                        <!-- Pages Menu -->
                        <li class="has-dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle link-hover" data-hover="pages">page</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">about us</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-about-1.html">About US 1</a>
                                        </li>
                                        <li>
                                            <a href="page-about-2.html">About US 2</a>
                                        </li>
                                        <li>
                                            <a href="page-about-dark.html">About US dark</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">contact us</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-contact-1.html">page contact 1</a>
                                        </li>
                                        <li>
                                            <a href="page-contact-2.html">page contact 2</a>
                                        </li>
                                        <li>
                                            <a href="page-contact-dark.html">page contact dark</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">untility pages</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-404.html">page 404</a>
                                        </li>
                                        <li>
                                            <a href="page-404-dark.html">page 404 dark</a>
                                        </li>
                                        <li>
                                            <a href="page-privacy.html">page privacy</a>
                                        </li>
                                        <li>
                                            <a href="page-terms.html">page terms</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">page tempalates</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-template-fullwidth.html">fullwidth</a>
                                        </li>
                                        <li>
                                            <a href="page-template-right-sidebar.html">right sidebar</a>
                                        </li>
                                        <li>
                                            <a href="page-template-left-sidebar.html">left sidebar</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li><!-- li end -->

                        <!-- collection Menu -->
                        <li class="has-dropdown mega-dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle menu-item">Collections</a>
                            <ul class="dropdown-menu mega-dropdown-menu collections-menu">
                                <li>
                                    <div class="container">
                                        <div class="row">
                                            <!-- Column #1 -->
                                            <div class="col-md-12 col-lg-5ths">
                                                <div class="collection--menu-content">
                                                    <h5>Furniture</h5>
                                                    <ul>
                                                        <li>
                                                            <a href="shop-layout-fullwidth.html">chair</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-layout-sidebar-left.html">sofa</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-4columns.html">table</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-3columns.html">bed</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="menu--img">
                                                    <img src="assets/images/slider/layers/1.png" alt="img" class="img-fluid">
                                                </div>
                                            </div>
                                            <!-- .col-lg-5ths end -->

                                            <!-- Column #2 -->
                                            <div class="col-md-12 col-lg-5ths">
                                                <div class="collection--menu-content">
                                                    <h5>Lighting</h5>
                                                    <ul>
                                                        <li>
                                                            <a href="shop-layout-fullwidth.html">Wall Lamp</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-layout-sidebar-left.html">Bedroom Lamp</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-4columns.html">Garden Lamp</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-3columns.html">Desktop Lamp</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="menu--img">
                                                    <img src="assets/images/slider/layers/37.png" alt="img" class="img-fluid">
                                                </div>
                                            </div>
                                            <!-- .col-lg-5ths end -->

                                            <!-- Column #3 -->
                                            <div class="col-md-12 col-lg-5ths">
                                                <div class="collection--menu-content">
                                                    <h5>Wood Shelf</h5>
                                                    <ul>
                                                        <li>
                                                            <a href="shop-layout-fullwidth.html">wood Living</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-layout-sidebar-left.html">wood Bedroom</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-4columns.html">wood Garden</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-3columns.html">wood tables</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="menu--img">
                                                    <img src="assets/images/slider/layers/34.png" alt="img" class="img-fluid">
                                                </div>
                                            </div>
                                            <!-- .col-lg-5ths end -->

                                            <!-- Column #4 -->
                                            <div class="col-md-12 col-lg-5ths">
                                                <div class="collection--menu-content">
                                                    <h5>Accessories</h5>
                                                    <ul>
                                                        <li>
                                                            <a href="shop-layout-fullwidth.html">Shoes</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-layout-sidebar-left.html">Bags</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-4columns.html">Jewellery</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-3columns.html">Scarves</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="menu--img">
                                                    <img src="assets/images/slider/layers/35.png" alt="img" class="img-fluid">
                                                </div>
                                            </div>
                                            <!-- .col-lg-5ths end -->

                                            <!-- Column #5 -->
                                            <div class="col-md-12 col-lg-5ths">
                                                <div class="collection--menu-content">
                                                    <h5>Sale Off</h5>
                                                    <ul>
                                                        <li>
                                                            <a href="shop-layout-fullwidth.html">Sunglasses</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-layout-sidebar-left.html">jackets</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-4columns.html">Shirts</a>
                                                        </li>
                                                        <li>
                                                            <a href="shop-3columns.html">Socks</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="menu--img">
                                                    <img src="assets/images/slider/layers/36.png" alt="img" class="img-fluid">
                                                </div>
                                            </div>
                                            <!-- .col-lg-5ths end -->
                                        </div>
                                        <!-- .row end -->
                                    </div>
                                    <!-- container end -->
                                </li>
                            </ul>
                            <!-- .mega-dropdown-menu end -->
                        </li><!-- li end -->

                        <!-- Blog Menu-->
                        <li class="has-dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle menu-item">Blog</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">blog Grid</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="blog-grid.html">fullwidth</a>
                                        </li>
                                        <li>
                                            <a href="blog-grid-sidebar-right.html">right sidebar</a>
                                        </li>
                                        <li>
                                            <a href="blog-grid-sidebar-left.html">left sidebar</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="blog-masonry.html">blog masonry</a>
                                </li>
                                <li>
                                    <a href="blog-parallax.html">blog parallax</a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">blog single</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="blog-single.html">fullwidth</a>
                                        </li>
                                        <li>
                                            <a href="blog-single-sidebar-right.html">right sidebar</a>
                                        </li>
                                        <li>
                                            <a href="blog-single-sidebar-left.html">left sidebar</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li><!-- li end -->

                        <!-- features Menu -->
                        <li class="has-dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle link-hover" data-hover="pages">features</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Headers</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="header-1.html">Header 1</a>
                                        </li>
                                        <li>
                                            <a href="header-2.html">Header 2</a>
                                        </li>
                                        <li>
                                            <a href="header-3.html">Header 3</a>
                                        </li>
                                        <li>
                                            <a href="header-4.html">Header 4</a>
                                        </li>
                                        <li>
                                            <a href="header-5.html">Header 5</a>
                                        </li>
                                        <li>
                                            <a href="header-6.html">Header 6</a>
                                        </li>
                                        <li>
                                            <a href="header-7.html">Header 7</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">footers</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="footer-1.html">footer 1</a>
                                        </li>
                                        <li>
                                            <a href="footer-2.html">footer 2</a>
                                        </li>
                                        <li>
                                            <a href="footer-3.html">footer 3</a>
                                        </li>
                                        <li>
                                            <a href="footer-4.html">footer 4</a>
                                        </li>
                                        <li>
                                            <a href="footer-5.html">footer 5</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">sliders</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="slider-1.html">slider 1</a>
                                        </li>
                                        <li>
                                            <a href="slider-2.html">slider 2</a>
                                        </li>
                                        <li>
                                            <a href="slider-3.html">slider 3</a>
                                        </li>
                                        <li>
                                            <a href="slider-4.html">slider 4</a>
                                        </li>
                                        <li>
                                            <a href="slider-5.html">slider 5</a>
                                        </li>
                                        <li>
                                            <a href="slider-6.html">slider 6</a>
                                        </li>
                                        <li>
                                            <a href="slider-7.html">slider 7</a>
                                        </li>
                                        <li>
                                            <a href="slider-8.html">slider 8</a>
                                        </li>
                                        <li>
                                            <a href="slider-9.html">slider 9</a>
                                        </li>
                                        <li>
                                            <a href="slider-10.html">slider 10</a>
                                        </li>
                                        <li>
                                            <a href="slider-11.html">slider 11</a>
                                        </li>
                                        <li>
                                            <a href="slider-12.html">slider 12</a>
                                        </li>
                                        <li>
                                            <a href="slider-13.html">slider 13</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">cart</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-cart.html">page cart</a>
                                        </li>
                                        <li>
                                            <a href="page-cart-dark.html">page cart dark</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">checkout</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-checkout.html">page checkout</a>
                                        </li>
                                        <li>
                                            <a href="page-checkout-dark.html">checkout dark</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">login</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-login.html">page login</a>
                                        </li>
                                        <li>
                                            <a href="page-login-dark.html">page login dark</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">wishlist</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="page-wishlist.html">page wishlist</a>
                                        </li>
                                        <li>
                                            <a href="page-wishlist-dark.html">page wishlist dark</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="page-soon.html">page soon</a>
                                </li>
                                <li>
                                    <a href="page-popup.html">page popup</a>
                                </li>
                            </ul>
                        </li><!-- li end -->
                    </ul>
                    <div class="module-container">
                        <!-- Module Search -->
                        <div class="module module-search pull-left">
                            <div class="module-icon search-icon">
                                <i class="lnr lnr-magnifier"></i>
                                <span class="title">Search</span>
                            </div>
                            <div class="module-content module--search-box">
                                <form class="form-search">
                                    <input type="text" class="form-control" placeholder="Search anything">
                                    <button type="submit"><span class="fa fa-arrow-right"></span></button>
                                </form><!-- .form-search end -->
                            </div>
                        </div><!-- .module-search end -->
                        <div class="vertical-divider pull-left mr-30"></div>
                        <div class="module module-lang pull-left">
                            <div class="module-icon">
                                <input type="checkbox" id="darkmode-toggle" class="toggle-checkbox sr-only">
                                <label for="darkmode-toggle" class="toggle">
                                    <span class="toggle-label">
                                        <span class="toggle-handle"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="module module-dropdown module-currency module-dropdown-right pull-left">
                            <div class="module-icon dropdown">
                                <?php if (!isset($_SESSION['customer_email'])) : ?>
                                    <!-- Display Login/Register Button -->
                                    <a href="login.php" class="dropdown-toggle">LOGIN / REGISTER</a>
                                <?php else : ?>
                                    <!-- Display MY ACCOUNT Dropdown -->
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" id="myAccountDropdown" data-toggle="dropdown">
                                            MY ACCOUNT <i class="fa fa-caret-down"></i>
                                        </button>
                                        <div class="module-dropdown-menu module-content" aria-labelledby="myAccountDropdown">
                                            <a class="dropdown-item" href="my_account.php">Profile</a>
                                            <a class="dropdown-item" href="logout.php">Logout</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Module Cart -->
                        <div class="module module-cart pull-left">
                            <div class="module-icon cart-icon">
                                <i class="icon-bag"></i>
                                <span class="title">shop cart</span>
                                <label class="module-label">2</label>
                            </div>
                            <div class="module-content module-box cart-box">
                                <div class="cart-overview">
                                    <ul class="list-unstyled">
                                        <li>
                                            <img class="img-fluid" src="assets/images/products/thumb/1.jpg" alt="product" />
                                            <div class="product-meta">
                                                <h5 class="product-title">Hebes Great Chair</h5>
                                                <p class="product-qunt">Quantity: 01</p>
                                                <p class="product-price">$24.00</p>
                                            </div>
                                            <a class="cart-cancel" href="#"><i class="lnr lnr-cross"></i></a>
                                        </li>
                                        <li>
                                            <img class="img-fluid" src="assets/images/products/thumb/2.jpg" alt="product" />
                                            <div class="product-meta">
                                                <h5 class="product-title">Hebes Great Chair</h5>
                                                <p class="product-qunt">Quantity: 01</p>
                                                <p class="product-price">$24.00</p>
                                            </div>
                                            <a class="cart-cancel" href="#"><i class="lnr lnr-cross"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="cart-total">
                                    <div class="total-desc">
                                        Sub total
                                    </div>
                                    <div class="total-price">
                                        $48.00
                                    </div>
                                </div>
                                <div class="clearfix">
                                </div>
                                <div class="cart--control">
                                    <a class="btn btn--white btn--bordered btn--rounded" href="cart.php">view cart </a>
                                    <a class="btn btn--primary btn--rounded" href="#">Checkout</a>
                                </div>
                            </div>
                        </div>
                        <!-- .module-cart end -->
                    </div>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
    </header>