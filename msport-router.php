<?php
// MSport E-commerce Router
// Handles all application routes and functionality

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Get the request URI and clean it
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

// Database connection
try {
    include_once 'db_connection.php';
} catch (Exception $e) {
    // If database fails, show maintenance page
    include 'maintenance.php';
    exit;
}

// Route handling
switch ($uri) {
    // Main pages
    case '/':
    case '/index.php':
        include 'header.php';
        include 'nav.php';
        include 'index.php';
        include 'footer.php';
        break;
        
    case '/shop':
    case '/shop.php':
        include 'header.php';
        include 'nav.php';
        include 'shop.php';
        include 'footer.php';
        break;
        
    case '/product':
    case '/product.php':
        include 'header.php';
        include 'nav.php';
        include 'product.php';
        include 'footer.php';
        break;
        
    case '/cart':
    case '/cart.php':
        include 'header.php';
        include 'nav.php';
        include 'cart.php';
        include 'footer.php';
        break;
        
    case '/checkout':
    case '/checkout.php':
        include 'header.php';
        include 'nav.php';
        include 'checkout.php';
        include 'footer.php';
        break;
        
    case '/login':
    case '/login.php':
        include 'header.php';
        include 'nav.php';
        include 'login.php';
        include 'footer.php';
        break;
        
    case '/register':
    case '/register.php':
        include 'header.php';
        include 'nav.php';
        include 'register.php';
        include 'footer.php';
        break;
        
    case '/about':
    case '/about.php':
        include 'header.php';
        include 'nav.php';
        include 'About.php';
        include 'footer.php';
        break;
        
    case '/contact':
    case '/contact.php':
        include 'header.php';
        include 'nav.php';
        include 'Contact.php';
        include 'footer.php';
        break;
        
    case '/blog':
    case '/blog.php':
        include 'header.php';
        include 'nav.php';
        include 'blog.php';
        include 'footer.php';
        break;
        
    case '/blog-list':
    case '/blog_list.php':
        include 'header.php';
        include 'nav.php';
        include 'blog_list.php';
        include 'footer.php';
        break;
        
    case '/wishlist':
    case '/wishlist.php':
        include 'header.php';
        include 'nav.php';
        include 'wishlist.php';
        include 'footer.php';
        break;
        
    // AJAX endpoints
    case '/addToCart':
    case '/addToCart.php':
        include 'addToCart.php';
        break;
        
    case '/removeCartItem':
    case '/removeCartItem.php':
        include 'removeCartItem.php';
        break;
        
    case '/updateQuantity':
    case '/updateQuantity.php':
        include 'updateQuantity.php';
        break;
        
    case '/loadCart':
    case '/loadCart.php':
        include 'loadCart.php';
        break;
        
    case '/apply_coupon':
    case '/apply_coupon.php':
        include 'apply_coupon.php';
        break;
        
    case '/validateCoupon':
    case '/validateCoupon.php':
        include 'validateCoupon.php';
        break;
        
    case '/place_order':
    case '/place_order.php':
        include 'place_order.php';
        break;
        
    case '/order_success':
    case '/order_success.php':
        include 'header.php';
        include 'nav.php';
        include 'order_success.php';
        include 'footer.php';
        break;
        
    case '/wishlist_action':
    case '/wishlist_action.php':
        include 'wishlist_action.php';
        break;
        
    case '/check_wishlist':
    case '/check_wishlist.php':
        include 'check_wishlist.php';
        break;
        
    case '/get_product_details':
    case '/get_product_details.php':
        include 'get_product_details.php';
        break;
        
    case '/get_product_photo':
    case '/get_product_photo.php':
        include 'get_product_photo.php';
        break;
        
    case '/get_similar_products':
    case '/get_similar_products.php':
        include 'get_similar_products.php';
        break;
        
    case '/add_review':
    case '/add_review.php':
        include 'add_review.php';
        break;
        
    case '/edit_review':
    case '/edit_review.php':
        include 'edit_review.php';
        break;
        
    case '/comment_bloc':
    case '/comment_bloc.php':
        include 'comment_bloc.php';
        break;
        
    case '/confirm':
    case '/confirm.php':
        include 'confirm.php';
        break;
        
    case '/logout':
    case '/logout.php':
        include 'logout.php';
        break;
        
    // Admin routes
    case '/admin':
    case '/admin/':
    case '/admin/index.php':
        include 'admin/index.php';
        break;
        
    case '/admin/login':
    case '/admin/login.php':
        include 'admin/login.php';
        break;
        
    case '/admin/logout':
    case '/admin/logout.php':
        include 'admin/logout.php';
        break;
        
    // Health and system endpoints
    case '/health':
    case '/health.php':
        include 'health.php';
        break;
        
    case '/db-status':
    case '/db-status.php':
        include 'db-status.php';
        break;
        
    case '/test':
        header('Content-Type: text/html');
        echo '<h1>Test Page</h1><p>PHP is working correctly!</p><p>Time: ' . date('Y-m-d H:i:s') . '</p>';
        break;
        
    case '/favicon.ico':
        http_response_code(204);
        break;
        
    // Static files - let PHP built-in server handle them
    default:
        $path = realpath(__DIR__ . $uri);
        if ($path && str_starts_with($path, __DIR__) && is_file($path)) {
            return false; // Let the built-in server serve the file
        }
        
        // 404 - Page not found
        include 'header.php';
        include 'nav.php';
        echo '<div class="container"><h1>Page Not Found</h1><p>The page you are looking for does not exist.</p></div>';
        include 'footer.php';
        break;
}

exit;
