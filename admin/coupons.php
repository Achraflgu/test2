<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

$sql = "SELECT * FROM coupons";
$result = mysqli_query($conn, $sql);

$countQuery = "SELECT COUNT(*) AS promoCount FROM coupons";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$promoCount = $countRow['promoCount'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">


    <title>Assign Project List - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" />
    <style type="text/css">
        body {
            margin-top: 20px;
        }

        .card {
            border-radius: 10px;
            border: none;
            position: relative;
            margin-bottom: 30px;
        }

        .card .card-header {
            border-bottom-color: #f9f9f9;
            line-height: 30px;
            -ms-grid-row-align: center;
            align-self: center;
            width: 100%;
            padding: 10px 25px;
            display: flex;
            align-items: center;
        }

        .card .card-header,
        .card .card-body,
        .card .card-footer {
            background-color: transparent;
            padding: 20px 25px;
        }

        .card-header:first-child {
            border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
        }

        .card-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, .03);
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .table:not(.table-sm) thead th {
            border-bottom: none;
            background-color: #e9e9eb;
            color: #666;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .table .table-img img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid #bbbbbb;
            -webkit-box-shadow: 5px 6px 15px 0px rgba(49, 47, 49, 0.5);
            -moz-box-shadow: 5px 6px 15px 0px rgba(49, 47, 49, 0.5);
            -ms-box-shadow: 5px 6px 15px 0px rgba(49, 47, 49, 0.5);
            box-shadow: 5px 6px 15px 0px rgba(49, 47, 49, 0.5);
            text-shadow: 0 0 black;
        }

        .table .team-member-sm {
            width: 32px;
            -webkit-transition: all 0.25s ease;
            -o-transition: all 0.25s ease;
            -moz-transition: all 0.25s ease;
            transition: all 0.25s ease;
        }

        .table .team-member {
            position: relative;
            width: 30px;
            white-space: nowrap;
            border-radius: 1000px;
            vertical-align: bottom;
            display: inline-block;
        }

        .table .order-list li img {
            border: 2px solid #ffffff;
            box-shadow: 4px 3px 6px 0 rgba(0, 0, 0, 0.2);
        }

        .table .team-member img {
            width: 100%;
            max-width: 100%;
            height: auto;
            border: 0;
            border-radius: 1000px;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        .table .order-list li+li {
            margin-left: -14px;
            background: transparent;
        }

        .avatar.avatar-sm {
            font-size: 12px;
            height: 30px;
            width: 30px;
        }

        .avatar {
            background: #6777ef;
            border-radius: 50%;
            color: #e3eaef;
            display: inline-block;
            font-size: 16px;
            font-weight: 300;
            margin: 0;
            position: relative;
            vertical-align: middle;
            line-height: 1.28;
            height: 45px;
            width: 45px;
        }

        .table .order-list li .badge {
            background: rgba(228, 222, 222, 0.8);
            color: #6b6f82;
            margin-bottom: 6px;
        }

        .badge {
            vertical-align: middle;
            padding: 7px 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            border-radius: 30px;
            font-size: 12px;
        }

        .progress-bar {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-direction: column;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            flex-direction: column;
            -ms-flex-pack: center;
            -webkit-box-pack: center;
            justify-content: center;
            overflow: hidden;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            background-color: #007bff;
            -webkit-transition: width .6s ease;
            transition: width .6s ease;
        }

        .bg-success {
            background-color: #54ca68 !important;
        }

        .bg-purple {
            background-color: #9c27b0 !important;
            color: #fff;
        }

        .bg-cyan {
            background-color: #10cfbd !important;
            color: #fff;
        }

        .bg-red {
            background-color: #f44336 !important;
            color: #fff;
        }

        .progress {
            -webkit-box-shadow: 0 0.4rem 0.6rem rgba(0, 0, 0, 0.15);
            box-shadow: 0 0.4rem 0.6rem rgba(0, 0, 0, 0.15);
        }

        /* Custom styling for the chosen dropdown */
        .chzn-container {
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .chzn-container-active .chzn-drop {
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        /* Custom styling for the search input */
        .chzn-container .chosen-search input[type="text"] {
            border: none;
            background-color: #fff;
            border-radius: 5px;
            padding: 5px 10px;
            width: calc(100% - 20px);
        }

        /* Custom styling for the clear icon */
        .chzn-container .chosen-choices .search-choice-close {
            margin-top: 5px;
        }

        /* Custom styling for dropdown options */
        .chzn-container .chosen-results li {
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .chzn-container .chosen-results li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>


<body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />



    <!-- Add Coupon Modal -->
    <!-- Add Coupon Modal -->
    <div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="addCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addCouponForm" method="post" action="add_coupon.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCouponModalLabel">Add Coupon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="addCouponName">Coupon Name</label>
                            <input type="text" class="form-control" id="addCouponName" name="addCouponName" required>
                        </div>
                        <div class="form-group">
                            <label for="addCouponCode">Coupon Code</label>
                            <input type="text" class="form-control" id="addCouponCode" name="addCouponCode">
                        </div>
                        <div class="form-group">
                            <label for="addDiscount">Discount</label>
                            <input type="number" class="form-control" id="addDiscount" name="addDiscount">
                        </div>
                        <div class="form-group">
                            <label for="addExpiryDate">Expiry Date</label>
                            <input type="date" class="form-control" id="addExpiryDate" name="addExpiryDate">
                        </div>
                        <div class="form-group">
                            <label for="addLimitUsage">Limit Usage</label>
                            <input type="number" class="form-control" id="addLimitUsage" name="addLimitUsage">
                        </div>
                        <div class="form-group">
                            <label for="addProducts">Products</label>
                            <select data-placeholder="Select Products" name="addProducts[]" class="chzn-select" multiple="multiple" tabindex="6">
                                <?php
                                // Fetch all products from the database
                                $query_all_products = "SELECT product_id, product_name FROM products";
                                $result_all_products = mysqli_query($conn, $query_all_products);

                                // Loop through each product and create an option for the select dropdown
                                while ($row = mysqli_fetch_assoc($result_all_products)) {
                                    echo "<option value='" . $row['product_id'] . "'>" . $row['product_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Edit Modal -->
    <div class="modal fade" id="editCouponModal" tabindex="-1" role="dialog" aria-labelledby="editCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editCouponForm" method="post" action="edit_coupon.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCouponModalLabel">Edit Coupon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editCouponName">Coupon Name</label>
                            <input type="text" class="form-control" id="editCouponName" name="editCouponName" required>
                        </div>
                        <div class="form-group">
                            <label for="editCouponCode">Coupon Code</label>
                            <input type="text" class="form-control" id="editCouponCode" name="editCouponCode">
                        </div>
                        <div class="form-group">
                            <label for="editProducts">Products</label>
                            <select data-placeholder="Select Products" name="editProducts[]" class="chzn-select" multiple="multiple" tabindex="6">
                                <?php
                                // Check if coupon_id is set in POST data
                                if (isset($_POST['coupon_id']) && $_POST['coupon_id'] != '') {
                                    // Fetch product IDs associated with the coupon
                                    $couponId = $_POST['coupon_id'];
                                    $query = "SELECT product_id FROM product_coupons WHERE coupon_id = $couponId";
                                    $result = mysqli_query($conn, $query);

                                    // Array to store product IDs associated with the coupon
                                    $selectedProducts = array();
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $selectedProducts[] = $row['product_id'];
                                    }

                                    // Fetch all products from the database
                                    $query_all_products = "SELECT product_id, product_name FROM products";
                                    $result_all_products = mysqli_query($conn, $query_all_products);

                                    // Loop through each product and create an option for the select dropdown
                                    while ($row = mysqli_fetch_assoc($result_all_products)) {
                                        $productId = $row['product_id'];
                                        $isSelected = in_array($productId, $selectedProducts) ? 'selected' : '';
                                        echo "<option value='" . $productId . "' $isSelected>" . $row['product_name'] . "</option>";
                                    }
                                } else {
                                    // Fetch all products from the database if no coupon is selected
                                    $query_all_products = "SELECT product_id, product_name FROM products";
                                    $result_all_products = mysqli_query($conn, $query_all_products);

                                    // Loop through each product and create an option for the select dropdown
                                    while ($row = mysqli_fetch_assoc($result_all_products)) {
                                        echo "<option value='" . $row['product_id'] . "'>" . $row['product_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>





                        <div class="form-group">
                            <label for="editDiscount">Discount</label>
                            <input type="number" class="form-control" id="editDiscount" name="editDiscount">
                        </div>
                        <div class="form-group">
                            <label for="editExpiryDate">Expiry Date</label>
                            <input type="date" class="form-control" id="editExpiryDate" name="editExpiryDate">
                        </div>
                        <div class="form-group">
                            <label for="editLimitUsage">Limit Usage</label>
                            <input type="number" class="form-control" id="editLimitUsage" name="editLimitUsage">
                        </div>

                        <input type="hidden" id="editCouponId" name="editCouponId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h5 class="card-title mb-0">Coupon Code List <span class="text-muted fw-normal">(<?php echo $promoCount; ?>)</span></h5>
                </h5>

            </div>
            <div class="col-md-4">
                <form class="d-flex">
                    <input id="brandSearchInput" class="form-control mx-auto me-2" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
            <div class="col-md-4">

                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a aria-current="page" href="#" class="router-link-active router-link-exact-active nav-link active" data-bs-toggle="tooltip" data-bs-placement="top" title="List">
                                <i class="bx bx-list-ul"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="top" title="Grid">
                                <i class="bx bx-grid-alt"></i>
                            </a>
                        </li>
                    </ul>

                    <a href="#" data-toggle="modal" data-target="#addCouponModal" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Add New</a>

                    <div class="dropdown">
                        <a class="btn btn-link text-muted py-1 font-size-16 shadow-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-dots-horizontal-rounded"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Coupon Name</th>
                                        <th>Coupon Code</th>
                                        <th>Products</th>
                                        <th>Discount</th>
                                        <th>Expiry Date</th>
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Iterate over each coupon and generate table rows
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Calculate remaining
                                        $remaining = $row['limit_usage'] - $row['usage_count'];

                                        // Determine status
                                        $status = ($remaining <= 0 || strtotime($row['expiry_date']) < time()) ? 'Expired' : 'Active';

                                        // Fetch product photos associated with the coupon
                                        $couponId = $row['coupon_id'];
                                        $productPhotos = array();
                                        if ($conn) { // Check if the database connection is open
                                            $productPhotosQuery = "SELECT p.product_photo FROM products p INNER JOIN product_coupons pc ON p.product_id = pc.product_id WHERE pc.coupon_id = $couponId";
                                            $productPhotosResult = mysqli_query($conn, $productPhotosQuery);
                                            if ($productPhotosResult) { // Check if the query was successful
                                                while ($photoRow = mysqli_fetch_assoc($productPhotosResult)) {
                                                    $productPhotos[] = $photoRow['product_photo'];
                                                }
                                            } else {
                                                // Handle query error
                                            }
                                        } else {
                                            // Handle database connection error
                                        }

                                        echo "<tr>";
                                        echo "<td>" . $row['coupon_name'] . "</td>";
                                        echo "<td>" . $row['coupon_code'] . "</td>";
                                        echo "<td class='text-truncate'>";
                                        echo "<ul class='list-unstyled order-list m-b-0'>";
                                        // Display maximum of 3 product photos
                                        $count = 0;
                                        foreach ($productPhotos as $photo) {
                                            if ($count < 3) {
                                                echo "<li class='team-member team-member-sm'><img class='rounded-circle' style='width: 30px; height: 30px;' src='$photo' alt='product'></li>";
                                            } else {
                                                break;
                                            }
                                            $count++;
                                        }
                                        // Show count of remaining photos
                                        $remainingCount = count($productPhotos) - $count;
                                        if ($remainingCount > 0) {
                                            echo "<li class='avatar avatar-sm' style='position: relative;'><span class='badge badge-primary' style='width: 30px; height: 30px;position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>+$remainingCount</span></li>";
                                        }
                                        echo "</ul>";
                                        echo "</td>";
                                        echo "<td>" . $row['discount'] . "% off</td>";
                                        echo "<td>" . $row['expiry_date'] . "</td>";
                                        echo "<td>" . $remaining . "</td>"; // Show remaining
                                        echo "<td>" . $status . "</td>"; // Show status
                                        echo "<td>";
                                        echo "<div class='action-icons'>";
                                        echo "<a href='#' class='edit-icon' data-toggle='tooltip' title='Edit' data-id='" . $row['coupon_id'] . "' data-name='" . $row['coupon_name'] . "'><i class='fas fa-edit'></i></a>&nbsp;";
                                        echo "<a href='#' class='delete-icon' data-toggle='tooltip' title='Delete' data-id='" . $row['coupon_id'] . "'><i class='far fa-trash-alt'></i></a>";
                                        echo "</div>";



                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>




                                    <!-- Add more coupon code rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mt-4 pt-2 col-lg-12">
                <ul class="pagination justify-content-center" id="paginationLinks">
                    <li class="page-item disabled">
                        <a class="page-link" tabindex="-1" href="#"><i class="mdi mdi-chevron-double-left fs-15"></i></a>
                    </li>
                    <!-- Pagination links will be dynamically generated here -->
                    <li class="page-item">
                        <a class="page-link" href="#"><i class="mdi mdi-chevron-double-right fs-15"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
function setupPagination() {
    // Your pagination data
    const totalItems = <?php echo $promoCount; ?>; // Total number of coupons
    const itemsPerPage = 10; // Number of coupons per page
    const maxPageLinks = 3; // Maximum number of pagination links to display

    // Function to display coupons for the given page number
    function displayCoupons(page) {
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        const tableRows = document.querySelectorAll(".table tbody tr");

        tableRows.forEach(function(row, index) {
            if (index >= startIndex && index < endIndex) {
                row.style.display = "table-row"; // Show the table row
            } else {
                row.style.display = "none"; // Hide the table row
            }
        });
    }

    // Function to generate pagination links
    function generatePaginationLinks(currentPage) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const paginationLinks = document.getElementById("paginationLinks");

        paginationLinks.innerHTML = ""; // Clear existing links

        // Determine the range of page links to display around the current page
        const startPage = Math.max(1, currentPage - Math.floor(maxPageLinks / 2));
        const endPage = Math.min(totalPages, startPage + maxPageLinks - 1);

        // Create "Previous" link
        const previousLi = document.createElement("li");
        previousLi.classList.add("page-item");
        previousLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous">
                            <i class="mdi mdi-chevron-double-left fs-15"></i>
                        </a>`;
        previousLi.addEventListener("click", function(event) {
            event.preventDefault();
            const prevPage = currentPage - 1;
            if (prevPage >= 1) {
                displayCoupons(prevPage);
                generatePaginationLinks(prevPage);
            }
        });
        paginationLinks.appendChild(previousLi);

        // Create page links
        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement("li");
            li.classList.add("page-item");
            if (i === currentPage) {
                li.classList.add("active");
            }
            li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
            li.addEventListener("click", function(event) {
                event.preventDefault();
                const page = parseInt(event.target.getAttribute("data-page"));
                displayCoupons(page);
                generatePaginationLinks(page);
            });
            paginationLinks.appendChild(li);
        }

        // Create "Next" link
        const nextLi = document.createElement("li");
        nextLi.classList.add("page-item");
        nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next">
                        <i class="mdi mdi-chevron-double-right fs-15"></i>
                    </a>`;
        nextLi.addEventListener("click", function(event) {
            event.preventDefault();
            const nextPage = currentPage + 1;
            if (nextPage <= totalPages) {
                displayCoupons(nextPage);
                generatePaginationLinks(nextPage);
            }
        });
        paginationLinks.appendChild(nextLi);
    }

    // Initially display first page and generate pagination links
    displayCoupons(1);
    generatePaginationLinks(1);
}

// Call setupPagination function based on the page loading method
// If using DOMContentLoaded
document.addEventListener("DOMContentLoaded", function() {
    setupPagination();
});

// If using window.onload
window.onload = function() {
    setupPagination();
};

// If using inline execution
setupPagination();
</script>

    <script>
        $(document).ready(function() {
            $('#editCouponModal').on('shown.bs.modal', function() {
                $(".chzn-select").chosen({
                    search_contains: true, // Allow searching within options
                    allow_single_deselect: true, // Allow deselecting a single option
                    placeholder_text_multiple: 'Select Products', // Placeholder text for multiple selection
                    no_results_text: 'No results found', // Message when no results are found
                    width: '100%' // Set the width of the dropdown
                });
            });

            // Edit button click event
            // Edit button click event
            $(document).on('click', '.edit-icon', function() {
                var couponId = $(this).data('id'); // Retrieve the coupon ID from data-id attribute
                console.log('Edit Icon Coupon ID:', couponId); // Log the coupon ID

                // AJAX request to fetch coupon details
                $.ajax({
                    url: 'fetch_coupon_details.php',
                    method: 'POST',
                    data: {
                        coupon_id: couponId
                    },
                    success: function(response) {
                        console.log('Response:', response); // Log the response data
                        var couponData = JSON.parse(response); // Parse the JSON response

                        // Update coupon data in the modal fields
                        $('#editCouponId').val(couponData.coupon_id); // Add this line to set the hidden input field value
                        $('#editCouponName').val(couponData.coupon_name);
                        $('#editCouponCode').val(couponData.coupon_code);
                        $('#editDiscount').val(couponData.discount);
                        $('#editExpiryDate').val(couponData.expiry_date);
                        $('#editLimitUsage').val(couponData.limit_usage);

                        // Select the associated products in the dropdown
                        $('.chzn-select').val(couponData.product_ids);
                        $('.chzn-select').trigger('chosen:updated');

                        console.log('Coupon Data:', couponData);

                        // Show the modal
                        $('#editCouponModal').modal('show');
                    }
                });
            });

            // Submit edit form
            $('#editCouponForm').submit(function(e) {
                e.preventDefault();

                // Serialize form data
                var formData = $(this).serialize();

                // AJAX request to update coupon details
                $.ajax({
                    url: 'edit_coupon.php',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log('Edit Coupon Response:', response);
                        var responseData = JSON.parse(response);
                        if (responseData.success) {
                            // Handle success
                            console.log('Coupon updated successfully');
                            location.reload();
                            // Optionally, reload or update the coupon list
                        } else {
                            // Handle error
                            console.log('Error updating coupon:', responseData.error);
                        }
                        // Close the modal
                        $('#editCouponModal').modal('hide');
                    }
                });
            });



            // Delete button click event
            $(document).on('click', '.delete-icon', function() {
                if (confirm('Are you sure you want to delete this coupon?')) {
                    var couponId = $(this).data('id'); // Retrieve the coupon ID from data-id attribute
                    $.ajax({
                        url: 'delete_coupon.php',
                        method: 'POST',
                        data: {
                            coupon_id: couponId
                        },
                        success: function(response) {
                            // Handle success, like reloading the page or updating the table
                            location.reload(); // Reload the page for demonstration purposes
                        }
                    });
                }
            });
        });

        // Submit add coupon form
        $('#addCouponForm').submit(function(e) {
            e.preventDefault();

            // Serialize form data
            var formData = $(this).serialize();

            // AJAX request to add new coupon
            $.ajax({
                url: 'add_coupon.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Add Coupon Response:', response);
                    var responseData = JSON.parse(response);
                    if (responseData.success) {
                        // Handle success
                        console.log('Coupon added successfully');
                        // Reload the page
                        location.reload();
                    } else {
                        // Handle error
                        console.log('Error adding coupon:', responseData.error);
                    }
                    // Close the modal
                    $('#addCouponModal').modal('hide');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Hide edit and delete icons initially
            $('.action-icons').hide();

            // Show edit and delete icons on hover
            $('tbody').on('mouseenter', 'tr', function() {
                $(this).find('.action-icons').show();
            }).on('mouseleave', 'tr', function() {
                $(this).find('.action-icons').hide();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to filter table rows based on search input
            function filterTableRows() {
                var searchText = $('#brandSearchInput').val().toLowerCase(); // Retrieve the search input value
                $('tbody tr').each(function() { // Iterate over each table row in the tbody
                    var rowData = $(this).text().toLowerCase(); // Get the text content of the row and convert to lowercase
                    if (rowData.includes(searchText)) { // Check if the row contains the search text
                        $(this).show(); // If it does, show the row
                    } else {
                        $(this).hide(); // If it doesn't, hide the row
                    }
                });
            }

            // Call the filterTableRows function when the search input changes
            $('#brandSearchInput').on('input', filterTableRows);
        });
    </script>

</body>

</html>