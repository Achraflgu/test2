/*global jQuery */
/* Contents
// ------------------------------------------------>
    1.  LOADING SCREEN
    2.  BACKGROUND INSERT
    3.	NAV MODULE
    4.  MOBILE MENU
    5.  HEADER AFFIX
    6.  COUNTER UP
    7.  COUNTDOWN DATE
    8.  AJAX MAILCHIMP
    9.  AJAX CAMPAIGN MONITOR
    10. OWL CAROUSEL
    11. MAGNIFIC POPUP
    12. MAGNIFIC POPUP VIDEO
    13. SWITCH GRID
    14. BACK TO TOP
    15. BLOG FLITER
    16. SCROLL TO
    17. SLIDER RANGE
    18. SCROLL TO
    19. GOOGLE MAP
    20. WIDGET CATEGORY TOGGLE MENU
  21. ToolTIP
  22. ANIMATION
  23. PARALLAX EFFECT
  24. EQUAL IMAGE AND CONTENT CATEGORY
  25. PRODUCT QANTITY
*/
(function ($) {
    "use strict";

    /* ------------------  LOADING SCREEN ------------------ */

    $(window).on("load", function () {
        setTimeout(function () {
            $(".preloader")
                .fadeOut(1000, function () {  // Fading out over 5 seconds
                    $(this).remove();  // Removing the preloader element after fade out
                });
        });  // 5 seconds delay for the fadeOut
    });

    document.addEventListener("DOMContentLoaded", function () {
        var darkModeEnabled = localStorage.getItem("darkModeEnabled") === "true";
        var darkModeLink = document.querySelector("link[href='assets/css/style-dark.css']");
        var darkModeToggle = document.getElementById("darkmode-toggle");
        var navbar = document.querySelector(".navbar");
        var header = document.querySelector(".header");
        var logo = document.querySelectorAll(".logo"); // Select both logos

        function toggleDarkMode() {
            if (darkModeEnabled) {
                darkModeLink.disabled = true; // Disable the dark mode stylesheet
                darkModeEnabled = false;
                navbar.classList.remove("navbar-dark");
                navbar.classList.add("navbar-light");
                header.classList.remove("header-dark");
                header.classList.add("header-light");
                logo.forEach(function (img) {
                    img.src = "assets/images/logo/logo-dark.png"; // Change to dark logo
                });
            } else {
                darkModeLink.disabled = false; // Enable the dark mode stylesheet
                darkModeEnabled = true;
                navbar.classList.remove("navbar-light");
                navbar.classList.add("navbar-dark");
                header.classList.remove("header-light");
                header.classList.add("header-dark");
                logo.forEach(function (img) {
                    img.src = "assets/images/logo/logo-light-red.png"; // Change to light logo
                });
            }

            // Update localStorage
            localStorage.setItem("darkModeEnabled", darkModeEnabled);
        }

        // Initialize dark mode based on localStorage
        if (darkModeEnabled) {
            darkModeLink.disabled = false;
            darkModeToggle.checked = true;
            navbar.classList.remove("navbar-light");
            navbar.classList.add("navbar-dark");
            header.classList.remove("header-light");
            header.classList.add("header-dark");
            logo.forEach(function (img) {
                img.src = "assets/images/logo/logo-light-red.png"; // Initial light logo
            });
        } else {
            darkModeLink.disabled = true;
            darkModeToggle.checked = false;
            navbar.classList.remove("navbar-dark");
            navbar.classList.add("navbar-light");
            header.classList.remove("header-dark");
            header.classList.add("header-light");
            logo.forEach(function (img) {
                img.src = "assets/images/logo/logo-dark.png"; // Initial dark logo
            });
        }

        // Toggle Dark Mode on checkbox change
        darkModeToggle.addEventListener("change", function () {
            toggleDarkMode();
        });
    });

    $(document).ready(function () {
        $('#product-detalis9 .product--meta-select3 select').change(function () {
            const productId = $(this).val();
            // Check if the selected option is a product ID
            if (!isNaN(productId)) {
                // Reload the page with the new product ID
                window.location.href = 'product.php?id=' + productId;
            }
        });

        // Function to load product details for a given product ID
        function loadProductDetails(productId) {
            // Make AJAX call to fetch product details
            $.ajax({
                url: 'get_product_details.php',
                type: 'GET',
                data: { product_id: productId },
                dataType: 'json',
                success: function (response) {
                    var currentDate = response.currentDate;
                    // Populate product details in the HTML elements
                    $('#product-detalis9 .product--title h3').text(response.product_name);
                    $('#product-detalis9 .product--rating').html(generateStars(response.avg_rating));
                    $('#product-detalis9 .product--review').text(response.reviews + ' Reviews');
                    // Update product price based on sale status
                    if (response.product_tag === 'Sale' && response.currentDate >= response.sale_start_date && response.currentDate <= response.sale_end_date) {
                        $('#product-detalis9 .product--price').html('<span class="original-price">$' + response.product_price + '</span><span class="sale-price">$' + response.product_sale_price + '</span>');
                    } else {
                        $('#product-detalis9 .product--price').text('$' + response.product_price);
                    }


                    $('#product-detalis9 .product--desc-tabs #product--desc-tabs-1 .product--desc p').text(response.product_description);
                    $('#product-detalis9 .product--desc-tabs #product--desc-tabs-2 .product--desc p').text(response.product_features);
                    $('#product-detalis9 .product--desc-tabs #product--desc-tabs-3 .product--desc p').text(response.product_details);
                    $('#product-detalis4 .tab-content #description .product--desc p').text(response.product_description);
                    $('#product-detalis4 .tab-content #description .product--desc-list p').text(response.product_details);
                    $('#addtional-info .product--desc p').text(response.product_features);
                    $('.product--desc-list').html(response.product_details);
                    $('#product-detalis9 .product--meta-info li:eq(0) span').text(response.product_stock_quantity > 0 ? 'In Stock' : 'Out of Stock');
                    $('#product-detalis9 .product--meta-info li:eq(1) span').text(response.product_id);
                    $('#product-detalis9 .add-to-cart').attr('data-product-id', response.product_id);
                    $('#product-detalis9 .add-to-wishlist').attr('data-product-id', response.product_id);
                    $('#product-detalis9 .compare').attr('data-product-id', response.product_id);
                    var reviewsCount = response.reviews;

                    // Update the text of the reviews tab link
                    $('#product-detalis4 ul.nav-tabs li:nth-child(3) a').text('reviews(' + reviewsCount + ')');
                    // Update product images
                    // Update product images
                    $('#product-detalis9 .products-gallery-carousel.products-gallery-carousel-1 .product-img img').each(function(index) {
                        // Determine the field name based on the index
                        var photoField = 'product_photo';
                        if (index === 1) photoField = 'product_photo_1';
                        else if (index === 2) photoField = 'product_photo_2';
                        else if (index === 3) photoField = 'product_photo_3';
                    
                        // Set the src attribute of the img tag
                        var imageUrl = response[photoField] ? (response[photoField].startsWith('http') ? response[photoField] : 'admin/' + response[photoField]) : '';
                        $('#product-detalis9 .products-gallery-carousel.products-gallery-carousel-1 .product-img #product-img-' + (index + 1)).attr('src', imageUrl).toggle(!!response[photoField]);
                    });
                    
                    // Update thumbnails
                    $('#product-detalis9 .owl-thumbs .owl-thumb-item').each(function(index) {
                        // Determine the field name based on the index
                        var thumbField = 'product_photo';
                        if (index === 1) thumbField = 'product_photo_1';
                        else if (index === 2) thumbField = 'product_photo_2';
                        else if (index === 3) thumbField = 'product_photo_3';
                    
                        // Set the src attribute of the img tag
                        var thumbImageUrl = response[thumbField] ? (response[thumbField].startsWith('http') ? response[thumbField] : 'admin/' + response[thumbField]) : '';
                        $('#product-detalis9 .owl-thumbs .owl-thumb-item #thumb-' + (index + 1)).attr('src', thumbImageUrl).toggle(!!response[thumbField]);
                    });
                    

                    const keywords = JSON.parse(response.product_keywords);
                    updateProductMeta(keywords);
                    loadSimilarProducts(response.product_name, keywords);

                    // Check wishlist items after modal is displayed
                    checkWishlistItems();
                    // Show the product details section
                    $('#product-detalis9').show();
                },
                error: function (xhr, status, error) {
                    // Handle error if necessary
                    console.error(error);
                }
            });
        }

        // Get the product ID from the URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');

        // Call the function to load product details with the extracted product ID
        loadProductDetails(productId);
    });


    $(document).ready(function () {
        $('a[data-target="#product-popup"], div[data-target="#product-popup"]').on('click', function () {
            resetModal();
            var productId = $(this).data('product-id');
            loadProductPopup(productId);
        });

        // Add event listener to select boxes for loading similar products
        $('#product-popup .product--meta-select3 select').change(function () {
            const productId = $(this).val();
            // Check if the selected option is a product ID
            if (!isNaN(productId)) {
                loadProductPopup(productId);
            }
        });

        // Function to load product popup for a given product ID
        function loadProductPopup(productId) {
            // Make AJAX call to fetch product details
            $.ajax({
                url: 'get_product_details.php',
                type: 'GET',
                data: { product_id: productId },
                dataType: 'json',
                success: function (response) {
                    var currentDate = response.currentDate;
                    // Populate product popup with the loaded product details
                    // Update product name
                    $('#product-popup .product--title h3').text(response.product_name);
                    // Update other product details
                    $('#product-popup .product--rating').html(generateStars(response.avg_rating));
                    $('#product-popup .product--review').text(response.reviews + ' Reviews');
                    if (response.product_tag === 'Sale' && response.currentDate >= response.sale_start_date && response.currentDate <= response.sale_end_date) {
                        $('#product-popup .product--price').html('<span class="original-price">$' + response.product_price + '</span><span class="sale-price">$' + response.product_sale_price + '</span>');
                    } else {
                        $('#product-popup .product--price').text('$' + response.product_price);
                    }
                    $('#product-popup .product--desc-tabs #popup--desc-tabs-1 .product--desc p').text(response.product_description);
                    $('#product-popup .product--desc-tabs #popup--desc-tabs-2 .product--desc p').text(response.product_features);
                    $('#product-popup .product--desc-tabs #popup--desc-tabs-3 .product--desc p').text(response.product_details);
                    $('#product-popup .product--details').html(response.product_details);
                    $('#product-popup .product--features').html(response.product_features);
                    const stockStatus = response.product_stock_quantity > 0 ? 'In Stock' : 'Out Stock';
                    $('#product-popup .product--meta-info li:eq(0) span').text(stockStatus);
                    if (stockStatus === 'In Stock') {
                        $('.add-to-cart').removeAttr('disabled').removeClass('disabled');
                        $('.add-to-cart').css({'cursor': 'pointer', 'opacity': '1'});
                    } else {
                        $('.add-to-cart').attr('disabled', 'disabled').addClass('disabled');
                        $('.add-to-cart').css({'cursor': 'not-allowed', 'opacity': '0.7'});
                    }
                    
                    $('#product-popup .product--meta-info li:eq(1) span').text(response.product_id);
                    $(".add-to-cart").data("product-id", response.product_id);
                    $("#product-popup .add-to-wishlist").attr("data-product-id", response.product_id);

                    // Update product images
                    $('#product-popup .products-gallery-carousel.products-gallery-carousel-2 .product-img #product-img-1').attr('src', response.product_photo ? (response.product_photo.startsWith('http') ? response.product_photo : 'admin/' + response.product_photo) : '').toggle(!!response.product_photo);
$('#product-popup .products-gallery-carousel.products-gallery-carousel-2 .product-img #product-img-2').attr('src', response.product_photo_1 ? (response.product_photo_1.startsWith('http') ? response.product_photo_1 : 'admin/' + response.product_photo_1) : '').toggle(!!response.product_photo_1);
$('#product-popup .products-gallery-carousel.products-gallery-carousel-2 .product-img #product-img-3').attr('src', response.product_photo_2 ? (response.product_photo_2.startsWith('http') ? response.product_photo_2 : 'admin/' + response.product_photo_2) : '').toggle(!!response.product_photo_2);
$('#product-popup .products-gallery-carousel.products-gallery-carousel-2 .product-img #product-img-4').attr('src', response.product_photo_3 ? (response.product_photo_3.startsWith('http') ? response.product_photo_3 : 'admin/' + response.product_photo_3) : '').toggle(!!response.product_photo_3);

// Update thumbnails
$('#product-popup .owl-thumbs .owl-thumb-item #thumb-1').attr('src', response.product_photo ? (response.product_photo.startsWith('http') ? response.product_photo : 'admin/' + response.product_photo) : '').toggle(!!response.product_photo);
$('#product-popup .owl-thumbs .owl-thumb-item #thumb-2').attr('src', response.product_photo_1 ? (response.product_photo_1.startsWith('http') ? response.product_photo_1 : 'admin/' + response.product_photo_1) : '').toggle(!!response.product_photo_1);
$('#product-popup .owl-thumbs .owl-thumb-item #thumb-3').attr('src', response.product_photo_2 ? (response.product_photo_2.startsWith('http') ? response.product_photo_2 : 'admin/' + response.product_photo_2) : '').toggle(!!response.product_photo_2);
$('#product-popup .owl-thumbs .owl-thumb-item #thumb-4').attr('src', response.product_photo_3 ? (response.product_photo_3.startsWith('http') ? response.product_photo_3 : 'admin/' + response.product_photo_3) : '').toggle(!!response.product_photo_3);


                    // Update product meta information
                    const keywords = JSON.parse(response.product_keywords);
                    updateProductMeta(keywords);
                    loadSimilarProducts(response.product_name, keywords);

                    // Trigger the modal display
                    $('#product-popup').modal('show');

                    // Check wishlist items after modal is displayed
                    checkWishlistItems();
                }
            });
        }
    });

    function updateProductMeta(keywords) {
        const colorSelect = $('.product--meta-select3 select:eq(0)');
        const sizeSelect = $('.product--meta-select3 select:eq(1)');
        colorSelect.empty();
        sizeSelect.empty();

        // List of predefined color names
        const colorNames = ['red', 'blue', 'green', 'yellow', 'black', 'white', 'orange']; // Add more color names as needed

        // Extract color, weight, and size options from keywords
        keywords.forEach(keyword => {
            // Check if the keyword matches any known color names
            if (colorNames.includes(keyword.toLowerCase())) {
                colorSelect.append($('<option>', { value: keyword, text: keyword }));
            } else if (keyword.startsWith('w-')) {
                const weight = keyword.substring(2) + ' kg';
                sizeSelect.append($('<option>', { value: weight, text: weight }));
            } else if (keyword.startsWith('s-')) {
                const size = keyword.substring(2);
                sizeSelect.append($('<option>', { value: size, text: size }));
            }
        });
    }


    function loadSimilarProducts(productName, keywords) {
        // Load products with the same name
        $.ajax({
            url: 'get_similar_products.php',
            type: 'GET',
            data: { product_name: productName },
            dataType: 'json',
            success: function (response) {
                // Extract color, weight, and size options from similar products' keywords
                const similarOptions = { color: new Map(), weight: new Map(), size: new Map() };
                response.forEach(product => {
                    const productId = product.product_id;
                    const productKeywords = JSON.parse(product.product_keywords);
                    productKeywords.forEach(keyword => {
                        // Check if the keyword is a color name
                        if (isColorName(keyword.toLowerCase())) {
                            if (!keywords.includes(keyword)) {
                                similarOptions.color.set(keyword, productId);
                            }
                        } else if (keyword.startsWith('w-')) {
                            const weight = keyword.substring(2) + ' kg';
                            if (!keywords.includes(keyword)) {
                                similarOptions.weight.set(productId, weight);
                            }
                        } else if (keyword.startsWith('s-')) {
                            const size = keyword.substring(2);
                            if (!keywords.includes(keyword)) {
                                similarOptions.size.set(productId, size);
                            }
                        }
                    });
                });

                // Populate select boxes with unique similar options
                const colorSelect = $('.product--meta-select3 select:eq(0)');
                const sizeSelect = $('.product--meta-select3 select:eq(1)');
                colorSelect.on('change', function () {
                    const selectedColor = $(this).val();
                    sizeSelect.empty();
                    similarOptions.weight.forEach((weight, productId) => {
                        if (productId === selectedColor) {
                            sizeSelect.append($('<option>', { value: weight, text: weight }));
                        }
                    });
                    similarOptions.size.forEach((size, productId) => {
                        if (productId === selectedColor) {
                            sizeSelect.append($('<option>', { value: size, text: size }));
                        }
                    });
                });
                similarOptions.color.forEach((productId, color) => {
                    colorSelect.append($('<option>', { value: productId, text: color }));
                });
            }
        });
    }


    // Function to check if a keyword is a color name
    function isColorName(keyword) {
        const colorNames = ['red', 'blue', 'green', 'yellow', 'black', 'white', 'orange']; // Add more color names as needed
        return colorNames.includes(keyword);
    }





    // Function to check wishlist items
    function checkWishlistItems() {
        $.ajax({
            url: 'check_wishlist.php',
            type: 'GET',
            success: function (response) {
                try {
                    if (!response.trim()) {
                        console.warn('Empty response received.');
                        return;
                    }

                    var wishlistItems = JSON.parse(response);

                    wishlistItems.forEach(function (item) {
                        $('.add-to-wishlist[data-product-id="' + item.product_id + '"]').addClass('added');
                    });
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    alert('Error fetching wishlist items. Please try again later.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                alert('Error fetching wishlist items. Please try again later.');
            }
        });
    }

    function resetModal() {
        // Reset thumbnails

        $('#thumb-1').attr('src', '').addClass('active');
        $('#thumb-2').attr('src', '').removeClass('active');
        $('#thumb-3').attr('src', '').removeClass('active');
        $('#thumb-4').attr('src', '').removeClass('active');

        // Show first product image
        var firstThumbSrc = $('#thumb-1').attr('src');
        $('#product-img-1').attr('src', firstThumbSrc);

        // Reset Owl Carousel active item
        $('.owl-thumbs .owl-thumb-item').removeClass('active');
        $('.owl-thumbs .owl-thumb-item:first-child').addClass('active');

        // Manually set the active item for Owl Carousel
        var owl = $('.products-gallery-carousel-2 .owl-carousel');
        owl.trigger('to.owl.carousel', [0]);

        $('#product-popup .product--desc-tabs #popup--desc-tabs-1').addClass('active show');
        $('#product-popup .product--desc-tabs #popup--desc-tabs-2').removeClass('active show');
        $('#product-popup .product--desc-tabs #popup--desc-tabs-3').removeClass('active show');

        // Show only the first tab content
        $('#product-popup #popup--desc-tabs-1').addClass('active show');
        $('#product-popup #popup--desc-tabs-2').removeClass('active show');
        $('#product-popup #popup--desc-tabs-3').removeClass('active show');

        // Set the first tab link as active
        $('#product-popup .nav.nav-tabs li a').removeClass('active');
        $('#product-popup .nav.nav-tabs li:first-child a').addClass('active');

        // Reset product title, rating, review, price, etc.
        $('.product--meta-info li span').text('');
        $('.select--box select').val('');
        $('.qty').val('1');

        $('#product-popup .add-to-wishlist').removeClass('added');
        $('#product-popup .product--meta-select3 select:eq(0)').empty();
        $('#product-popup .product--meta-select3 select:eq(1)').empty();
    }





    function generateStars(rating) {
        var stars = '';
        for (var i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fa fa-star active"></i>';
            } else {
                stars += '<i class="fa fa-star"></i>';
            }
        }
        return stars;
    }

    $('.btn--primary.btn--rounded.btn--block').click(function (e) {
        e.preventDefault();
    
        // Prepare the cart data
        var cartData = {
            products: [],
            total: $('.total span').text(), // Get the total amount from the cart
            subtotal: $('.sub--total span').text(), // Get the subtotal from the cart
            discountTotal: $('.discount-amount').text() // Get the discount total from the cart
        };
    
        // Iterate over each product in the cart and add it to the cartData object
        $('.cart-product').each(function () {
            var productId = $(this).find('.product-id').val();
            var productName = $(this).find('.cart-product-name h6').text();
            var productPrice = $(this).find('.cart-product-total span').text();
            var productQuantity = $(this).find('.qty').val();
            var productColor = $(this).find('ul li:contains("Color") span').last().text().trim(); // Get the last span text within li containing "Color"
            var productWeight = $(this).find('ul li:contains("Weight") span').last().text().trim(); // Get the last span text within li containing "Weight"
            var productSize = $(this).find('ul li:contains("Size") span').last().text().trim(); // Get the last span text within li containing "Size"
    
            cartData.products.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: productQuantity,
                color: productColor,
                weight: productWeight,
                size: productSize
            });
        });
    
        // Store the cart data in session storage
        sessionStorage.setItem('cartData', JSON.stringify(cartData));
    
        // Redirect to the checkout page
        window.location.href = 'checkout.php';
    });
    

    /* ------------------ CART COUPON ------------------ */

    // Function to apply coupon code
    $(document).ready(function () {
        // Event listener for applying coupon
        $('.cart-product-action .btn--secondary').click(function (e) {
            e.preventDefault();
            applyCoupon();
        });

        // Check if a coupon code is stored in localStorage when the page loads
        if (window.location.pathname === '/msport/cart.php') {
            var storedCouponCode = localStorage.getItem('couponCode');
            if (storedCouponCode) {
                // Apply the coupon code from localStorage
                $('#coupon').val(storedCouponCode);
                applyCoupon();
            }
        }
    });

    function applyCoupon() {
        var couponCode = $('#coupon').val(); // Get the coupon code from the input field

        // Retrieve product IDs and prices from the cart table
        var products = [];
        $('.cart-product').each(function () {
            var productId = $(this).find('.product-id').val();
            var priceString = $(this).find('.cart-product-total span').text();
            var price = parseFloat(priceString.replace('$', ''));
            if (!isNaN(price)) {
                products.push({ id: productId, price: price });
            }
        });

        console.log('Products in Cart:', products); // Log products to console

        // Send an AJAX request to validate the coupon code and get discount details
        $.ajax({
            url: 'validateCoupon.php',
            type: 'POST',
            dataType: 'json',
            data: { coupon_code: couponCode, products: products }, // Pass products to the server
            success: function (response) {
                if (response.status === 'success') {
                    var discountAmount = response.discount_amount; // Get the discount amount from the response
                    var discountedProducts = response.discounted_products; // Get the list of products eligible for discount
                    console.log('Discount Amount:', response.discount_amount);
                    console.log('Discounted Products:', response.discounted_products);

                    if (discountedProducts.length > 0) {
                        // Recalculate cart total only if there are discounted products
                        updateCartTotal(discountAmount, discountedProducts);

                        // Update prices for eligible products in the cart
                        discountedProducts.forEach(function (discountedProduct) {
                            var productId = discountedProduct.product_id;
                            var discountPercentage = parseFloat(discountedProduct.discount_percentage);

                            // Find the corresponding product in the products array
                            var product = products.find(function (prod) {
                                return prod.id === productId;
                            });

                            if (product) {
                                // Calculate the discounted price
                                var discountedPrice = product.price - (product.price * (discountPercentage / 100));
                                var discountAmount = product.price - discountedPrice;
                                // Update the UI with the discounted price
                                $('#product_' + productId + '_price .product-price').text('$' + discountedPrice.toFixed(2));

                                console.log('Updated Price for Product ID ' + productId + ': $' + discountedPrice.toFixed(2)); // Log updated price
                                console.log('Discount Amount for Product ID ' + productId + ': $' + discountAmount.toFixed(2)); // Log discount amount

                            }
                        });

                        // Store coupon code in localStorage
                        localStorage.setItem('couponCode', couponCode);
                    } else {
                        // No discounted products, so do nothing
                        console.log('No discounted products for this coupon.');
                    }
                } else {
                    // Display error message if the coupon is invalid
                    alert('Invalid coupon code. Please try again.');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error applying coupon:', textStatus, errorThrown);
                console.log('Server response:', jqXHR.responseText);
            }
        });
    }

    // Function to update cart total
    function updateCartTotal(totalProductDiscount, discountedProducts) {
        var cartSubtotal = 0;
        var totalDiscountAmount = 0;

        $('.cart-product').each(function () {
            var productId = $(this).find('.product-id').val();
            var priceString = $(this).find('.cart-product-total span').text();
            var price = parseFloat(priceString.replace('$', ''));
            if (!isNaN(price)) {
                // Check if the product is in the list of discounted products
                var discountedProduct = discountedProducts.find(function (product) {
                    return product.product_id === productId;
                });

                if (discountedProduct) {
                    var discountPercentage = parseFloat(discountedProduct.discount_percentage);
                    var discountedPrice = price - (price * (discountPercentage / 100));
                    totalDiscountAmount += price - discountedPrice;

                    // Update the UI with the discounted price
                    var cartTotalElement = $(this).find('.cart-product-total span');
                    cartTotalElement.html('<del>$' + price.toFixed(2) + '</del>    $' + discountedPrice.toFixed(2));
                }

                cartSubtotal += price;
            }
        });

        console.log('Cart Subtotal:', cartSubtotal); // Log the cart subtotal to the console
        console.log('Discount Total:', totalDiscountAmount); // Log the total discount amount to the console

        var discountTotal = totalDiscountAmount; // Use only the total discount amount calculated for eligible products
        console.log('Discount Total:', discountTotal); // Log the discount total to the console

        var grandTotal = cartSubtotal - discountTotal;
        console.log('Grand Total:', grandTotal); // Log the grand total to the console

        // Update the UI with the new subtotal, discount total, and grand total
        $('.total span').text('$' + grandTotal.toFixed(2));
        $('.discount-amount').text('$' + discountTotal.toFixed(2));
    }






    /* ------------------ PAGE CART & CART BOX ------------------ */


    $(document).ready(function () {
        function loadCart() {
            $.ajax({
                url: 'loadCart.php',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        updateCartUI(response);
                    } else {
                        console.error('Error loading cart:', response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error loading cart:', textStatus, errorThrown);
                    console.log('Server response:', jqXHR.responseText);
                }
            });
        }

        function updateCartUI(response) {
            var cartTotal = parseFloat(response.cartTotal).toFixed(2);
            var cartCount = response.cartCount;
            var products = response.products;
            var currentDate = new Date().toISOString().slice(0, 10);

            // Update cart total and count
            $('.cart-total .total-price').text('$' + cartTotal);
            $('.cart-icon .module-label').text(cartCount);
            $('.sub--total span').text('$' + cartTotal);
            $('.total span').text('$' + cartTotal);

            // Clear existing cart overview and table body
            var cartOverview = $('.cart-overview ul');
            var cartTableBody = $('#shopcart .cart-table tbody');
            cartOverview.empty();
            cartTableBody.empty();

            // Iterate through each product in the response
            products.forEach(function (product) {
                // Initialize priceHtml
                var priceHtml = '';

                // Check if the product is on sale and within the sale period
                console.log("Current Date:", currentDate);
                console.log("Product Sale Start Date:", product.sale_start_date);
                console.log("Product Sale End Date:", product.sale_end_date);

                if (product.product_tag === 'Sale' && currentDate >= product.sale_start_date && currentDate <= product.sale_end_date) {
                    console.log("Product is on sale and within the sale period");
                    priceHtml = `<p class="product-price"><del>$${product.product_price}</del> $${product.product_sale_price}</p>`;
                } else {
                    console.log("Product is not on sale or the current date is not within the sale period");
                    priceHtml = `<p class="product-price">$${product.product_price}</p>`;
                }



                // Construct HTML for cart overview
                var productHtmlOverview = `
                    <li>
                        <img class="img-fluid" src="admin/${product.product_photo}" alt="product" />
                        <div class="product-meta">
                            <h5 class="product-title">${product.product_name}</h5>
                            <div class="product-quantity">
                                <button class="quantity-btn minus" data-cart-id="${product.cart_id}">-</button>
                                <input type="text" id="pro${product.cart_id}-qunt" value="${product.quantity}" class="quantity-input" readonly>
                                <button class="quantity-btn plus" data-cart-id="${product.cart_id}">+</button>
                            </div>
                            <p class="product-price">
    ${(product.product_tag === 'Sale' && currentDate >= product.sale_start_date && currentDate <= product.sale_end_date) ?
                        `<del>$${product.product_price * product.quantity}</del> $${product.product_sale_price * product.quantity}` :
                        `$${product.product_price * product.quantity}`}
</p>
                        </div>
                        <a class="cart-cancel" href="#" data-cart-id="${product.cart_id}"><i class="lnr lnr-cross"></i></a>
                    </li>`;
                cartOverview.append(productHtmlOverview);

                // Construct HTML for cart table
                // Construct HTML for cart table
                var productHtmlTable = `
<tr class="cart-product">
    <td class="cart-product-item">
        <div class="cart-product-img">
            <img src="admin/${product.product_photo}" alt="product" style="max-width: 100px; max-height: 100px;" />
        </div>
        <div class="cart-product-content">
            <div class="cart-product-name">
                <h6>${product.product_name}</h6>
            </div>
            <ul class="list-unstyled mb-0">
                ${product.color ? `<li><span>Color:</span><span>${product.color}</span></li>` : ''}
                ${product.size ?
                        (['m', 'l', 'xl', 'xxl', 's', 'xxxl', 'xxxxl', 'xxxxxl'].includes(product.size.toLowerCase()) ?
                            `<li><span>Size:</span><span>${product.size}</span></li>` :
                            `<li><span>Weight:</span><span>${product.size}</span></li>`)
                        : ''}
            </ul>
        </div>
    </td>
    <td class="cart-product-price">
        ${priceHtml}
    </td>
    <td class="cart-product-quantity">
        <div class="product-quantity">
            <button class="minus" data-cart-id="${product.cart_id}">-</button>
            <input type="text" id="pro${product.cart_id}-qunt" value="${product.quantity}" class="qty" readonly>
            <button class="plus" data-cart-id="${product.cart_id}">+</button>
        </div>
    </td>
    <td class="cart-product-total">
        <span>$${(product.product_tag === 'Sale' && currentDate >= product.sale_start_date && currentDate <= product.sale_end_date) ? product.product_sale_price * product.quantity : product.product_price * product.quantity}</span>
        <div class="cart-product-remove" data-cart-id="${product.cart_id}">x</div>
        <!-- Hidden input field to store the product ID -->
        <input type="hidden" class="product-id" value="${product.product_id}">
    </td>
</tr>`;
                cartTableBody.append(productHtmlTable);

            });

            $('.minus').click(function (e) {
                e.preventDefault();
                var cartId = $(this).data('cart-id');
                var quantityInput = $(this).siblings('.quantity-input, .qty');
                var currentQuantity = parseInt(quantityInput.val());

                if (currentQuantity > 1) {
                    updateCartQuantity('decrease', cartId, currentQuantity);
                } else {
                    console.log('Minimum quantity reached');
                }
            });

            $('.plus').click(function (e) {
                e.preventDefault();
                var cartId = $(this).data('cart-id');
                updateCartQuantity('increase', cartId);
            });

            $('.cart-cancel, .cart-product-remove').click(function (e) {
                e.preventDefault();
                var cartId = $(this).data('cart-id');
                removeCartItem(cartId);
            });
        }


        function updateCartQuantity(action, cartId, currentQuantity = null) {
            $.ajax({
                url: 'addToCart.php',
                type: 'POST',
                data: {
                    action: action,
                    cartId: cartId,
                    quantity: currentQuantity
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        loadCart();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error updating cart quantity:', textStatus, errorThrown);
                    console.log('Server response:', jqXHR.responseText);
                }
            });
        }

        function removeCartItem(cartId) {
            $.ajax({
                url: 'addToCart.php',
                type: 'POST',
                data: {
                    action: 'remove',
                    cartId: cartId
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        loadCart();
                    } else {
                        console.error('Error removing cart item:', response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error removing cart item:', textStatus, errorThrown);
                    console.log('Server response:', jqXHR.responseText);
                }
            });
        }

        // Load cart data when the page loads
        loadCart();



        // Modal Add to Cart
        $(document).on('click', '#product-popup .add-to-cart', function (e) {
            e.preventDefault();

            var productId = $(this).data("product-id");
            var modalQuantityInput = $("#product-popup .qty");
            var quantity = modalQuantityInput.val();
            var color = $('#product-popup .product--meta-select3 select:eq(0)').val();
            var size = $('#product-popup .product--meta-select3 select:eq(1)').val();

            $.ajax({
                url: 'addToCart.php',
                type: 'POST',
                data: {
                    action: 'add',
                    productId: productId,
                    quantity: quantity,
                    color: color,
                    size: size
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        loadCart();
                        $("#product-popup").modal('hide');
                    } else {
                        console.error('Error adding to cart:', response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    console.log('Server response:', jqXHR.responseText);
                }
            });
        });

        $(document).on('click', '#product-detalis9 .add-to-cart', function (e) {
            e.preventDefault();

            var productId = $(this).data("product-id");
            var modalQuantityInput = $("#product-detalis9 .qty"); // Update selector to target #product-detalis9
            var quantity = modalQuantityInput.val();
            var color = $('#product-detalis9 .product--meta-select3 select:eq(0)').val();
            var size = $('#product-detalis9 .product--meta-select3 select:eq(1)').val();

            $.ajax({
                url: 'addToCart.php',
                type: 'POST',
                data: {
                    action: 'add',
                    productId: productId,
                    quantity: quantity,
                    color: color,
                    size: size
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        loadCart();
                        $("#product-popup").modal('hide');
                    } else {
                        console.error('Error adding to cart:', response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    console.log('Server response:', jqXHR.responseText);
                }
            });
        });

        // Index Page Add to Cart
        $(document).on('click', '.add-to-cart-index', function (e) {
            e.preventDefault();

            var productId = $(this).data("product-id");

            $.ajax({
                url: 'addToCart.php',
                type: 'POST',
                data: {
                    action: 'add',
                    productId: productId,
                    quantity: 1 // Default quantity for index page
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        loadCart();
                    } else {
                        console.error('Error adding to cart:', response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    console.log('Server response:', jqXHR.responseText);
                }
            });
        });

    });








    /* ------------------  ICON ADD WISHLIST ------------------ */
    $(document).ready(function () {
        // Check wishlist items on page load
        checkWishlistItems();

        $('.add-to-wishlist').on('click', function (e) {
            e.preventDefault();

            var productId = $(this).data('product-id');
            var action = $(this).hasClass('added') ? 'remove' : 'add';

            $.ajax({
                url: 'wishlist_action.php',
                type: 'POST',
                data: {
                    action: action,
                    product_id: productId
                },
                success: function (response) {
                    if (response === 'not_logged_in') {
                        alert('Please log in to add items to wishlist.');
                        return;
                    }

                    if (action === 'add') {
                        $('.add-to-wishlist[data-product-id="' + productId + '"]').addClass('added');
                    } else {
                        $('.add-to-wishlist[data-product-id="' + productId + '"]').removeClass('added');
                    }
                }
            });
        });

        $('.cart-product-remove').on('click', function (e) {
            e.preventDefault();

            var productId = $(this).data('product-id');

            $.ajax({
                url: 'wishlist_action.php',
                type: 'POST',
                data: {
                    action: 'remove',
                    product_id: productId
                },
                success: function (response) {
                    if (response === 'not_logged_in') {
                        alert('Please log in to remove items from wishlist.');
                        return;
                    }

                    // Remove the corresponding row from the wishlist table
                    $('.add-to-wishlist[data-product-id="' + productId + '"]').removeClass('added');
                    $(e.target).closest('.cart-product').remove();
                }
            });
        });
    });




    /* ------------------  HOVER PHOTO ------------------ */

    // Hover event for color boxes
    $(document).on('mouseenter', '.color-box', function () {
        var productId = $(this).data('product-id');
        var productPhotoPopup = $(this).next('.product-photo-popup');
        getProductPhoto(productId, productPhotoPopup);
    });

    // Function to fetch and display the product photo
    function getProductPhoto(productId, productPhotoPopup) {
        $.ajax({
            url: 'get_product_photo.php',
            method: 'POST',
            data: { productId: productId },
            success: function (response) {
                // Display the product photo in the popup
                var imageUrl = response.startsWith('http') ? response : 'admin/' + response;
                productPhotoPopup.html('<img src="' + imageUrl + '" alt="Product Photo" style="mix-blend-mode: multiply;">'); productPhotoPopup.show();
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Hide product photo popup on mouse leave
    $(document).on('mouseleave', '.color-box', function () {
        $(this).next('.product-photo-popup').hide();
    });


    /* ------------------  Filter ------------------ */

    $(document).ready(function () {
        // Listen for clicks on star icons within the rating selector
        $('.product--rating a').click(function (e) {
            e.preventDefault(); // Prevent default anchor behavior

            // Remove the 'active' class from all stars
            $('.product--rating a i').removeClass('active');

            // Add the 'active' class only to the clicked star
            $(this).find('i').addClass('active');

            // Determine the new rating based on the number of active stars
            var newRating = $(this).prevAll().length + 1;

            // Update a hidden input field or any other element to store the new rating
            $('#rating_input').val(newRating); // Assuming you have an input field with id="rating_input"
        });
    });





    /* ------------------  Background INSERT ------------------ */

    var $bgSection = $(".bg-section"),
        $bgPattern = $(".bg-pattern"),
        $colBg = $(".col-bg");

    $bgSection.each(function () {
        var bgSrc = $(this)
            .children("img")
            .attr("src");
        var bgUrl = "url(" + bgSrc + ")";
        $(this)
            .parent()
            .css("backgroundImage", bgUrl);
        $(this)
            .parent()
            .addClass("bg-section");
        $(this).remove();
    });

    $bgPattern.each(function () {
        var bgSrc = $(this)
            .children("img")
            .attr("src");
        var bgUrl = "url(" + bgSrc + ")";
        $(this)
            .parent()
            .css("backgroundImage", bgUrl);
        $(this)
            .parent()
            .addClass("bg-pattern");
        $(this).remove();
    });

    $colBg.each(function () {
        var bgSrc = $(this)
            .children("img")
            .attr("src");
        var bgUrl = "url(" + bgSrc + ")";
        $(this)
            .parent()
            .css("backgroundImage", bgUrl);
        $(this)
            .parent()
            .addClass("col-bg");
        $(this).remove();
    });

    /* ------------------  NAV MODULE  ------------------ */

    var $moduleIcon = $(".module-icon"),
        $moduleCancel = $(".module-cancel");

    $moduleIcon.on("click", function (e) {
        $(this)
            .parent()
            .siblings()
            .removeClass("module-active"); // Remove the class .active form any sibiling.
        $(this)
            .parent(".module")
            .toggleClass("module-active"); //Add the class .active to parent .module for this element.
        e.stopPropagation();
    });
    // If Click on [ Search-cancel ] Link
    $moduleCancel.on("click", function (e) {
        $(".module").removeClass("module-active");
        e.stopPropagation();
        e.preventDefault();
    });

    $(".side-nav-icon").on("click", function () {
        if (
            $(this)
                .parent()
                .hasClass("module-active")
        ) {
            $(".wrapper").addClass("hamburger-active");
            $(this).addClass("module-hamburger-close");
        } else {
            $(".wrapper").removeClass("hamburger-active");
            $(this).removeClass("module-hamburger-close");
        }
    });

    // If Click on [ Document ] and this click outside [ hamburger panel ]
    $(document).on("click", function (e) {
        if (
            $(e.target).is(
                ".hamburger-panel,.hamburger-panel .list-links,.hamburger-panel .list-links a,.hamburger-panel .social-share,.hamburger-panel .social-share a i,.hamburger-panel .social-share a,.hamburger-panel .copywright"
            ) === false
        ) {
            $(".wrapper").removeClass("page-transform"); // Remove the class .active form .module when click on outside the div.
            $(".module-side-nav").removeClass("module-active");
            e.stopPropagation();
        }
    });

    // If Click on [ Document ] and this click outside [ module ]
    $(document).on("click", function (e) {
        if (
            !$(e.target).closest(".module").length &&
            !$(e.target).closest(".module-content").length
        ) {
            $module.removeClass("module-active"); // Remove the class .active form .module when click on outside the div.
            e.stopPropagation();
        }
    });


    /* ------------------  MOBILE MENU ------------------ */

    var $dropToggle = $("ul.dropdown-menu [data-toggle=dropdown]"),
        $module = $(".module");

    $dropToggle.on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        $(this)
            .parent()
            .siblings()
            .removeClass("show");
        $(this)
            .parent()
            .toggleClass("show");
    });

    $module.on("click", function () {
        $(this).toggleClass("toggle-module");
    });

    $module
        .find("input.form-control", ".btn", ".module-cancel")
        .click(function (e) {
            e.stopPropagation();
        });

    /* ------------------  COUNTER UP ------------------ */

    $(".counting").counterUp({
        delay: 10,
        time: 1000
    });

    /* ------------------ COUNTDOWN DATE ------------------ */

    $(".countdown").each(function () {
        var $countDown = $(this),
            countDate = $countDown.data("count-date"),
            newDate = new Date(countDate);
        $countDown.countdown({
            until: newDate,
            format: "dHMS"
        });
    });

    /* ------------------  AJAX MAILCHIMP ------------------ */

    $(".mailchimp").ajaxChimp({
        url:
            "http://wplly.us5.list-manage.com/subscribe/post?u=91b69df995c1c90e1de2f6497&id=aa0f2ab5fa", //Replace with your own mailchimp Campaigns URL.
        callback: chimpCallback
    });

    function chimpCallback(resp) {
        if (resp.result === "success") {
            $(".subscribe-alert")
                .html('<h5 class="alert alert-success">' + resp.msg + "</h5>")
                .fadeIn(1000);
            //$('.subscribe-alert').delay(6000).fadeOut();
        } else if (resp.result === "error") {
            $(".subscribe-alert")
                .html('<h5 class="alert alert-danger">' + resp.msg + "</h5>")
                .fadeIn(1000);
        }
    }

    /* ------------------  AJAX CAMPAIGN MONITOR  ------------------ */

    $("#campaignmonitor").submit(function (e) {
        e.preventDefault();
        $.getJSON(this.action + "?callback=?", $(this).serialize(), function (
            data
        ) {
            if (data.Status === 400) {
                alert("Error: " + data.Message);
            } else {
                // 200
                alert("Success: " + data.Message);
            }
        });
    });

    /* ------------------  AJAX CONTACT FORM  ------------------ */
   

    

    /* ------------------ OWL CAROUSEL ------------------ */

    var $productsSlider = $(".products-slider");

    $(".carousel").each(function () {
        var $Carousel = $(this);
        $Carousel.owlCarousel({
            loop: $Carousel.data("loop"),
            autoplay: $Carousel.data("autoplay"),
            margin: $Carousel.data("space"),
            nav: $Carousel.data("nav"),
            dots: $Carousel.data("dots"),
            center: $Carousel.data("center"),
            dotsSpeed: $Carousel.data("speed"),
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: $Carousel.data("slide-rs")
                },
                1000: {
                    items: $Carousel.data("slide")
                }
            }
        });
    });

    $productsSlider.owlCarousel({
        thumbs: true,
        thumbsPrerendered: true,
        loop: true,
        margin: 0,
        autoplay: false,
        nav: false,
        dots: false,
        dotsSpeed: 200,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });

    /* ------------------ MAGNIFIC POPUP ------------------ */

    var $imgPopup = $(".img-popup");

    $imgPopup.magnificPopup({
        type: "image"
    });
    $(".img-gallery-item").magnificPopup({
        type: "image",
        gallery: {
            enabled: true
        }
    });

    /* ------------------  MAGNIFIC POPUP VIDEO ------------------ */

    $(".popup-video,.popup-gmaps").magnificPopup({
        disableOn: 700,
        mainClass: "mfp-fade",
        removalDelay: 0,
        preloader: false,
        fixedContentPos: false,
        type: "iframe",
        iframe: {
            markup:
                '<div class="mfp-iframe-scaler">' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                "</div>",
            patterns: {
                youtube: {
                    index: "youtube.com/",
                    id: "v=",
                    src: "//www.youtube.com/embed/%id%?autoplay=1"
                }
            },
            srcAction: "iframe_src"
        }
    });

    /* ------------------  SWITCH GRID ------------------ */

    var $switchList = $("#switch-list"),
        $switchGrid = $("#switch-grid"),
        $productItem = $(".product-item");

    $switchList.on("click", function (event) {
        event.preventDefault();
        $(this).addClass("active");
        $(this)
            .siblings()
            .removeClass("active");
        $productItem.each(function () {
            $(this).addClass("product-list");
            $(this).removeClass("product-grid");
        });
    });

    $switchGrid.on("click", function (event) {
        event.preventDefault();
        $(this).addClass("active");
        $(this)
            .siblings()
            .removeClass("active");
        $productItem.each(function () {
            $(this).removeClass("product-list");
            $(this).addClass("product-grid");
        });
    });

    /* ------------------  BACK TO TOP ------------------ */

    var backTop = $("#back-to-top");

    if (backTop.length) {
        var scrollTrigger = 200, // px
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    backTop.addClass("show");
                } else {
                    backTop.removeClass("show");
                }
            };

        backToTop();

        $(window).on("scroll", function () {
            backToTop();
        });

        backTop.on("click", function (e) {
            e.preventDefault();
            $("html,body").animate(
                {
                    scrollTop: 0
                },
                700
            );
        });
    }

    /* ------------------ BLOG FLITER ------------------ */

    var $blogFilter = $(".blog-filter"),
        blogLength = $blogFilter.length,
        blogFinder = $blogFilter.find("a"),
        $blogAll = $("#enrty-all");

    // init Isotope For shop
    blogFinder.on("click", function (e) {
        e.preventDefault();
        $blogFilter.find("a.active-filter").removeClass("active-filter");
        $(this).addClass("active-filter");
    });

    if (blogLength > 0) {
        $blogAll.imagesLoaded().progress(function () {
            $blogAll.isotope({
                filter: "*",
                animationOptions: {
                    duration: 750,
                    itemSelector: ".blog-entry",
                    easing: "linear",
                    queue: false
                }
            });
        });
    }

    blogFinder.on("click", function (e) {
        e.preventDefault();
        var $selector = $(this).attr("data-filter");
        $blogAll.imagesLoaded().progress(function () {
            $blogAll.isotope({
                filter: $selector,
                animationOptions: {
                    duration: 750,
                    itemSelector: ".blog-entry",
                    easing: "linear",
                    queue: false
                }
            });
            return false;
        });
    });

    /* ------------------  SCROLL TO ------------------ */

    var aScroll = $('a[data-scroll="scrollTo"]');

    aScroll.on("click", function (event) {
        var target = $($(this).attr("href"));
        if (target.length) {
            event.preventDefault();
            $("html, body").animate(
                {
                    scrollTop: target.offset().top
                },
                1000
            );
            if ($(this).hasClass("menu-item")) {
                $(this)
                    .parent()
                    .addClass("active");
                $(this)
                    .parent()
                    .siblings()
                    .removeClass("active");
            }
        }
    });

    /* ------------------ SLIDER RANGE ------------------ */



    /* ------------------ GOOGLE MAP ------------------ */

    $(".googleMap").each(function () {
        var $gmap = $(this);
        $gmap.gMap({
            address: $gmap.data("map-address"),
            zoom: $gmap.data("map-zoom"),
            maptype: $gmap.data("map-type"),
            markers: [
                {
                    address: $gmap.data("map-address"),
                    maptype: $gmap.data("map-type"),
                    html: $gmap.data("map-info"),
                    icon: {
                        image: $gmap.data("map-maker-icon"),
                        iconsize: [76, 61],
                        iconanchor: [76, 61]
                    }
                }
            ]
        });
    });

    /* ------------------ WIDGET CATEGORY TOGGLE MENU  ------------------ */

    var $widgetCategoriesLink = $(".widget-categories2 .main--list > li > a");

    $widgetCategoriesLink.on("click", function (e) {
        $(this)
            .parent()
            .siblings()
            .removeClass("active");
        $(this)
            .parent()
            .toggleClass("active");
        e.stopPropagation();
        e.preventDefault();
    });

    /* ------------------  ToolTIP ------------------ */

    $('[data-toggle="tooltip"]').tooltip();

    /* ------------------ ANIMATION ------------------ */

    new WOW().init();

    /* ------------------  PARALLAX EFFECT ------------------ */

    siteFooter();
    $(window).resize(function () {
        siteFooter();
    });

    function siteFooter() {
        var siteContent = $("#wrapperParallax");
        var contentParallax = $(".contentParallax");

        var siteFooter = $("#footerParallax");
        var siteFooterHeight = siteFooter.height();

        siteContent.css({
            "margin-bottom": siteFooterHeight
        });
    }

    /* ------------------ EQUAL IMAGE AND CONTENT CATEGORY ------------------ */

    var $categoryImg = $(".category-5 .category--img"),
        $categoryContent = $(".category-5 .category--content"),
        $categoryContentHeight = $categoryContent.outerHeight();

    $categoryImg.css("height", $categoryContentHeight);

    /* ------------------ PRODUCT QANTITY ------------------ */

    var $productQuantity = $(".product-quantity");

    $productQuantity.on("click", ".plus", function (e) {
        var $input = $(this).prev("input.qty");
        var val = parseInt($input.val());
        var step = $input.attr("step");
        step = "undefined" !== typeof step ? parseInt(step) : 1;
        $input.val(val + step).change();
    });

    $productQuantity.on("click", ".minus", function (e) {
        var $input = $(this).next("input.qty");
        var val = parseInt($input.val());
        var step = $input.attr("step");
        step = "undefined" !== typeof step ? parseInt(step) : 1;
        if (val > 0) {
            $input.val(val - step).change();
        }
    });
})(jQuery);
