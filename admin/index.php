<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) { // Change from 'username' to 'email'
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Get the email of the logged-in admin
$email = $_SESSION['email']; // Change from 'username' to 'email'

// Query the database to fetch admin details
$sql = "SELECT * FROM admins WHERE admin_email = '$email'";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) == 1) {
        // Fetch admin details
        $row = mysqli_fetch_assoc($result);
        $admin_name = $row['admin_name'];
        $admin_job = $row['admin_job'];
        // Assuming you store the photo as blob in the database
        $admin_photo = $row['admin_photo'];
    } else {
        // Admin not found, handle error
        $admin_name = "Admin Name Not Found";
        $admin_job = "Admin Job Not Found";
        // You might want to redirect to a different page or display an error message
    }
} else {
    // Error in executing the query, handle error
    $admin_name = "Error Retrieving Admin Name";
    $admin_job = "Error Retrieving Admin Job";
    echo "SQL Error: " . mysqli_error($conn); // Debugging line
    // You might want to redirect to a different page or display an error message
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/styles/default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rk4bir/simple-tags-input@1.0.0/src/simple-tag-input.min.css">
    <title>Admin Dashboard Panel</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            /* ===== Colors ===== */
            --primary-color: #0E4BF1;
            --panel-color: #FFF;
            --text-color: #000;
            --black-light-color: #707070;
            --border-color: #e6e5e5;
            --toggle-color: #DDD;
            --box1-color: #4DA3FF;
            --box2-color: #FFE6AC;
            --box3-color: #E7D1FC;
            --title-icon-color: #fff;

            /* ====== Transition ====== */
            --tran-05: all 0.5s ease;
            --tran-03: all 0.3s ease;
            --tran-03: all 0.2s ease;
        }

        body {
            min-height: 100vh;
            background-color: var(--primary-color);
            padding: 0 !important;
            padding-right: 0 !important;
        }

        body.dark {
            --primary-color: #3A3B3C;
            --panel-color: #242526;
            --text-color: #CCC;
            --black-light-color: #CCC;
            --border-color: #4D4C4C;
            --toggle-color: #FFF;
            --box1-color: #3A3B3C;
            --box2-color: #3A3B3C;
            --box3-color: #3A3B3C;
            --title-icon-color: #CCC;
        }

        /* === Custom Scroll Bar CSS === */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0b3cc1;
        }

        body.dark::-webkit-scrollbar-thumb:hover,
        body.dark .activity-data::-webkit-scrollbar-thumb:hover {
            background: #3A3B3C;
        }

        body.dark span {
            color: #CCC;
        }

        .dark .card {
            color: #CCC;
            background: #3A3B3C;
        }

        .dark .card .btn-block {

            background: #3a3b3c;
        }

        nav {
            position: fixed;
            display: block;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            padding: 10px 14px;
            background-color: var(--panel-color);
            border-right: 1px solid var(--border-color);
            transition: var(--tran-05);
        }

        nav.close {
            width: 73px;
        }

        nav .logo-name {
            display: flex;
            align-items: center;
        }

        nav .logo-image {
            display: flex;
            justify-content: center;
            min-width: 45px;
        }

        nav .logo-image img {
            width: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        nav .logo-name .logo_name {
            font-size: 22px;
            font-weight: 600;
            color: var(--text-color);
            margin-left: 14px;
            transition: var(--tran-05);
        }

        nav.close .logo_name {
            opacity: 0;
            pointer-events: none;
        }

        nav .menu-items {
            display: block;
            margin-top: 40px;
            height: calc(100% - 90px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .menu-items li {
            list-style: none;
        }

        .menu-items li a {
            display: flex;
            align-items: center;
            height: 50px;
            text-decoration: none;
            position: relative;
        }

        /* Hover effect */
        .nav-links li a:hover:before {
            content: "";
            position: absolute;
            left: -7px;
            height: 5px;
            width: 5px;
            border-radius: 50%;
            background-color: var(--primary-color);
            /* Primary color for hover */
        }

        /* Active state */
        .nav-links li a.active:before {
            content: "";
            position: absolute;
            left: -7px;
            height: 5px;
            width: 5px;
            border-radius: 50%;
            background-color: var(--primary-color);
            /* Primary color for hover */
        }

        body.dark .nav-links li a:hover:before,
        body.dark .nav-links li a.active:before {
            background-color: var(--text-color);
            /* Text color for hover and active in dark mode */
        }


        .menu-items li a i {
            font-size: 24px;
            min-width: 45px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--black-light-color);
        }

        .menu-items li a .link-name {
            font-size: 18px;
            font-weight: 400;
            color: var(--black-light-color);
            transition: var(--tran-05);
        }

        nav.close li a .link-name {
            opacity: 0;
            pointer-events: none;
        }

        .nav-links li a:hover i,
        .nav-links li a:hover .link-name {
            color: var(--primary-color);
        }

        body.dark .nav-links li a:hover i,
        body.dark .nav-links li a:hover .link-name {
            color: var(--text-color);
        }

        .menu-items .logout-mode {
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
        }

        .menu-items .mode {
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .menu-items .mode-toggle {
            position: absolute;
            right: 14px;
            height: 50px;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .mode-toggle .switch {
            position: relative;
            display: inline-block;
            height: 22px;
            width: 40px;
            border-radius: 25px;
            background-color: var(--toggle-color);
        }

        .switch:before {
            content: "";
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            height: 15px;
            width: 15px;
            background-color: var(--panel-color);
            border-radius: 50%;
            transition: var(--tran-03);
        }

        body.dark .switch:before {
            left: 20px;
        }

        .dashboard {
            position: relative;
            left: 250px;
            background-color: var(--panel-color);
            min-height: 100vh;
            width: calc(100% - 250px);
            padding: 10px 14px;
            transition: var(--tran-05);
        }

        nav.close~.dashboard {
            left: 73px;
            width: calc(100% - 73px);
        }

        .dashboard .top {
            position: fixed;
            top: 0;
            left: 250px;
            display: flex;
            width: calc(100% - 250px);
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            background-color: var(--panel-color);
            transition: var(--tran-05);
            z-index: 10;
        }

        nav.close~.dashboard .top {
            left: 73px;
            width: calc(100% - 73px);
        }

        .dashboard .top .sidebar-toggle {
            font-size: 26px;
            color: var(--text-color);
            cursor: pointer;
        }

        .dashboard .top .search-box {
            position: relative;
            height: 45px;
            max-width: 600px;
            width: 100%;
            margin: 0 30px;
        }

        .top .search-box input {
            position: absolute;
            border: 1px solid var(--border-color);
            background-color: var(--panel-color);
            padding: 0 25px 0 50px;
            border-radius: 5px;
            height: 100%;
            width: 100%;
            color: var(--text-color);
            font-size: 15px;
            font-weight: 400;
            outline: none;
        }

        .top .search-box i {
            position: absolute;
            left: 15px;
            font-size: 22px;
            z-index: 10;
            top: 50%;
            transform: translateY(-50%);
            color: var(--black-light-color);
        }

        .top img {
            width: 40px;
            border-radius: 50%;
        }

        .dashboard .dash-content {
            padding-top: 50px;
            margin-top: 50px;
        }

        .dash-content .title {
            display: flex;
            align-items: center;
            margin: 60px 0 30px 0;
        }

        .dash-content .title i {
            position: relative;
            height: 35px;
            width: 35px;
            background-color: var(--primary-color);
            border-radius: 6px;
            color: var(--title-icon-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .dash-content .title .text {
            font-size: 24px;
            font-weight: 500;
            color: var(--text-color);
            margin-left: 10px;
        }

        .dash-content .boxes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .dash-content .boxes .box {
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 12px;
            width: calc(100% / 3 - 15px);
            padding: 15px 20px;
            background-color: var(--box1-color);
            transition: var(--tran-05);
        }

        .boxes .box i {
            font-size: 35px;
            color: var(--text-color);
        }

        .boxes .box .text {
            white-space: nowrap;
            font-size: 18px;
            font-weight: 500;
            color: var(--text-color);
        }

        .boxes .box .number {
            font-size: 40px;
            font-weight: 500;
            color: var(--text-color);
        }

        .boxes .box.box2 {
            background-color: var(--box2-color);
        }

        .boxes .box.box3 {
            background-color: var(--box3-color);
        }

        .dash-content .activity .activity-data {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .activity .activity-data {
            display: flex;
        }

        .activity-data .data {
            display: flex;
            flex-direction: column;
            margin: 0 15px;
        }

        .activity-data .data-title {
            font-size: 20px;
            font-weight: 500;
            color: var(--text-color);
        }

        .activity-data .data .data-list {
            font-size: 18px;
            font-weight: 400;
            margin-top: 20px;
            white-space: nowrap;
            color: var(--text-color);
        }

        @media (max-width: 1000px) {
            nav {
                width: 73px;
            }

            nav.close {
                width: 250px;
            }

            nav .logo_name {
                opacity: 0;
                pointer-events: none;
            }

            nav.close .logo_name {
                opacity: 1;
                pointer-events: auto;
            }

            nav li a .link-name {
                opacity: 0;
                pointer-events: none;
            }

            nav.close li a .link-name {
                opacity: 1;
                pointer-events: auto;
            }

            nav~.dashboard {
                left: 73px;
                width: calc(100% - 73px);
            }

            nav.close~.dashboard {
                left: 250px;
                width: calc(100% - 250px);
            }

            nav~.dashboard .top {
                left: 73px;
                width: calc(100% - 73px);
            }

            nav.close~.dashboard .top {
                left: 250px;
                width: calc(100% - 250px);
            }

            .activity .activity-data {
                overflow-X: scroll;
            }
        }

        @media (max-width: 780px) {
            .dash-content .boxes .box {
                width: calc(100% / 2 - 15px);
                margin-top: 15px;
            }
        }

        @media (max-width: 560px) {
            .dash-content .boxes .box {
                width: 100%;
            }
        }

        @media (max-width: 400px) {
            nav {
                width: 0px;
            }

            nav.close {
                width: 73px;
            }

            nav .logo_name {
                opacity: 0;
                pointer-events: none;
            }

            nav.close .logo_name {
                opacity: 0;
                pointer-events: none;
            }

            nav li a .link-name {
                opacity: 0;
                pointer-events: none;
            }

            nav.close li a .link-name {
                opacity: 0;
                pointer-events: none;
            }

            nav~.dashboard {
                left: 0;
                width: 100%;
            }

            nav.close~.dashboard {
                left: 73px;
                width: calc(100% - 73px);
            }

            nav~.dashboard .top {
                left: 0;
                width: 100%;
            }

            nav.close~.dashboard .top {
                left: 0;
                width: 100%;
            }

        }
    </style>

    <style>
        .admin-info {
            display: flex;
            align-items: center;
        }

        .admin-name {
            margin-right: 20px;
            /* Increase or decrease the margin as needed */
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .admin-photo {
            width: 100%;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border-radius: 50%;
            border: 1px solid #000;
            /* Adjust border color and thickness as needed */
            margin-left: 20px;
            /* Adjust margin to create space */
            transition: transform 0.3s ease;
            /* Add smooth transition effect */
        }

        .dark .admin-photo {
            border: 2px solid #ecf0f1;
        }

        .admin-photo:hover {
            transform: scale(1.1);
            cursor: pointer;
            /* Zoom in the photo slightly on hover */
        }

        .dark .dash-content {
            color: #CCC;
        }
    </style>
    <style>
        /* CSS for fade-out animation */
        .fade-out {
            animation: fadeOut ease 1s;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .alert {
            animation: fadeIn ease 2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .search-box {
            position: relative;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 5px 5px;
            z-index: 1;
        }

        .search-results div {
            padding: 5px 10px;
            cursor: pointer;
        }

        .search-results .no-results {
            padding: 5px 10px;
            color: #666;
        }
    </style>
</head>

<body style="padding: 0; padding-right: 0;">

    <div id="alertContainer" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;"></div>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="images/logo.png" alt="">
            </div>

            <span class="logo_name">CodingLab</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="index.php?page=dashboard" class="active">
                        <i class="uil uil-estate"></i> <!-- Dashboard icon -->
                        <span class="link-name">Dashboard</span>
                    </a></li>
                <li><a href="index.php?page=product">
                        <i class="uil uil-archive"></i> <!-- Product icon -->
                        <span class="link-name">Product</span>
                    </a></li>
                <li><a href="index.php?page=product_categories">
                        <i class="uil uil-folder"></i> <!-- Product Categories icon -->
                        <span class="link-name">Product Categories</span>
                    </a></li>
                <li><a href="index.php?page=brands">
                        <i class="uil uil-tag"></i> <!-- Brands icon -->
                        <span class="link-name">Brands</span>
                    </a></li>
                <li><a href="index.php?page=coupons">
                        <i class="uil uil-gift"></i> <!-- Coupons icon -->
                        <span class="link-name">Coupons</span>
                    </a></li>
                <li>
                    <a href="index.php?page=customers">
                        <i class="uil uil-user"></i> <!-- Customers icon -->
                        <span class="link-name">Customers</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=team">
                        <i class="uil uil-users-alt"></i> <!-- Team Members icon -->
                        <span class="link-name">Team Members</span>
                    </a>
                </li>


                <li>
                    <a href="index.php?page=orders">
                        <i class="uil uil-shopping-cart"></i> <!-- Orders icon -->
                        <span class="link-name">Orders</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=payments">
                        <i class="uil uil-dollar-sign-alt"></i> <!-- Payments icon -->
                        <span class="link-name">Payments</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=Blogs">
                        <i class="uil uil-newspaper"></i> <!-- Newspaper icon -->
                        <span class="link-name">Blogs</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=Contacts">
                        <i class="uil uil-phone"></i> <!-- Phone icon -->
                        <span class="link-name">Contacts</span>
                    </a>
                </li>


            </ul>

            <ul class="logout-mode">

                <li><a href="index.php?page=Profile">
                        <i class="uil uil-user"></i> <!-- Settings icon -->
                        <span class="link-name">Profile</span>
                    </a></li>


                <li><a href="logout.php"> <!-- Assuming logout.php handles the logout functionality -->
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Logout</span>
                    </a></li>

                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                        <span class="link-name">Dark Mode</span>
                    </a>

                    <div class="mode-toggle">
                        <span class="switch"></span>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>

            <div class="search-box">
                <i class="uil uil-search"></i>
                <input id="nav-search-input" type="text" placeholder="Search here...">
                <div id="search-results" class="search-results"></div>
            </div>

            <!-- Display admin's name followed by photo -->
            <div class="admin-info">
                <span class="welcome-message">Welcome, <?php echo $admin_name; ?></span>
                <img id="admin-photo" src="<?php echo $admin_photo; ?>" alt="Admin Photo" class="admin-photo">
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const adminPhoto = document.getElementById('admin-photo');

                adminPhoto.addEventListener('click', function() {
                    window.location.href = 'index.php?page=Profile';
                });
            });
        </script>

        <div class="dash-content">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
            <script src="https://cdn.jsdelivr.net/gh/rk4bir/simple-tags-input@1.0.0/src/simple-tag-input.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.3/jspdf.umd.min.js" async></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


            <!-- Chosen CSS -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Chosen JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>



        </div>

    </section>


</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('nav-search-input');
        const searchResults = document.getElementById('search-results');
        const navLinks = document.querySelectorAll('.nav-links .link-name');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();
            searchResults.innerHTML = ''; // Clear previous results

            navLinks.forEach(function(link) {
                const text = link.textContent.toLowerCase();

                if (text.includes(searchTerm)) {
                    const resultItem = document.createElement('div');
                    const highlightedText = highlightText(link.textContent, searchTerm);
                    resultItem.innerHTML = highlightedText;
                    resultItem.addEventListener('click', function() {
                        window.location.href = link.parentElement.getAttribute('href');
                    });
                    searchResults.appendChild(resultItem);
                }
            });

            if (searchResults.children.length === 0) {
                searchResults.innerHTML = '<div class="no-results">No results found</div>';
            }
        });

        // Handle keyboard navigation
        searchResults.addEventListener('keydown', function(event) {
            const currentResult = document.activeElement;
            let nextResult;

            if (event.key === 'ArrowDown') {
                nextResult = currentResult.nextElementSibling || searchResults.firstElementChild;
            } else if (event.key === 'ArrowUp') {
                nextResult = currentResult.previousElementSibling || searchResults.lastElementChild;
            }

            if (nextResult) {
                currentResult.blur();
                nextResult.focus();
                event.preventDefault();
            }
        });

        // Close search results dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchResults.contains(event.target) && event.target !== searchInput) {
                searchResults.innerHTML = '';
            }
        });

        // Highlight matching text
        function highlightText(text, searchTerm) {
            const regex = new RegExp('(' + searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
            return text.replace(regex, '<mark>$1</mark>');
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.nav-links a');
        const dashContent = document.querySelector('.dash-content');

        function loadContent(page) {
            fetch(page + '.php', {
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    dashContent.innerHTML = data;
                    // Execute JavaScript in loaded content
                    const scripts = dashContent.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.text = script.innerText;
                        dashContent.appendChild(newScript);
                        script.parentNode.removeChild(script);
                    });
                    console.log('Content loaded successfully:', data);

                    // Re-initialize any functionality dependent on the loaded content
                    initChosen(); // Call Chosen plugin initialization function after content is loaded
                })
                .catch(error => {
                    console.error('Error fetching content:', error);
                });
        }

        function initChosen() {
            $(".chzn-select").chosen({
                search_contains: true,
                allow_single_deselect: true,
                placeholder_text_multiple: 'Select Products',
                no_results_text: 'No results found',
                width: '100%'
            });
        }

        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || 'dashboard';
        loadContent(page);

        menuItems.forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                menuItems.forEach(link => link.classList.remove('active'));
                this.classList.add('active');
                const url = this.getAttribute('href');
                const params = new URLSearchParams(url.split('?')[1]);
                const clickedPage = params.get('page');
                window.history.pushState({}, '', url);
                loadContent(clickedPage);
            });
        });
    });
</script>




<script>
    const body = document.querySelector("body"),
        modeToggle = body.querySelector(".mode-toggle");
    sidebar = body.querySelector("nav");
    sidebarToggle = body.querySelector(".sidebar-toggle");

    let getMode = localStorage.getItem("mode");
    if (getMode && getMode === "dark") {
        body.classList.toggle("dark");
    }

    let getStatus = localStorage.getItem("status");
    if (getStatus && getStatus === "close") {
        sidebar.classList.toggle("close");
    }

    modeToggle.addEventListener("click", () => {
        body.classList.toggle("dark");
        if (body.classList.contains("dark")) {
            localStorage.setItem("mode", "dark");
        } else {
            localStorage.setItem("mode", "light");
        }
    });

    sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("close");
        if (sidebar.classList.contains("close")) {
            localStorage.setItem("status", "close");
        } else {
            localStorage.setItem("status", "open");
        }
    })
</script>


<script>
    // Check if the URL contains a success parameter
    const urlParams = new URLSearchParams(window.location.search);
    const successParam = urlParams.get('success');

    // If success parameter is present, display an alert
    if (successParam === 'true') {
        const alertContainer = document.getElementById('alertContainer');
        const alertHTML = `
            <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
                Changes saved successfully!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeAlert()">
                    <span aria-hidden="true" style="font-size: 12px;">&times;</span>
                </button>
            </div>
        `;
        alertContainer.innerHTML = alertHTML;

        // Automatically close the alert after 6 seconds
        setTimeout(() => {
            closeAlert();
        }, 6000); // 6 seconds delay before auto closing the alert
    }

    // Function to close the alert
    function closeAlert() {
        const successAlert = document.getElementById('successAlert');
        successAlert.classList.remove('show');
        successAlert.classList.add('fade-out');
        setTimeout(() => {
            alertContainer.innerHTML = ''; // Remove the alert from the DOM
        }, 500); // Delay before removing the alert from the DOM after fading out
    }
</script>


</html>