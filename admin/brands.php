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

// Fetch brands with count of products
$query = "SELECT b.brand_id, b.brand_status, b.brand_name, b.brand_photo, COUNT(p.product_id) AS product_count
          FROM brands b
          LEFT JOIN products p ON b.brand_id = p.brand_id
          GROUP BY b.brand_id";
$result = mysqli_query($conn, $query);

// Fetch all rows and store them in an array
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
$count_query = "SELECT COUNT(*) AS total_brands FROM brands";
$count_result = mysqli_query($conn, $count_query);
$total_brands = mysqli_fetch_assoc($count_result)['total_brands'];
// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">
        body {
            background-color: #f8f9fa !important
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .mb-0,
        .my-0 {
            margin-bottom: 0 !important;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        .dark th {
            color: white;
        }

        /* user-dashboard-info-box */
        .user-dashboard-info-box .prod-list .thumb {
            margin-right: 20px;
        }

        .user-dashboard-info-box .prod-list .thumb img {
            width: 80px;
            height: 80px;
            -o-object-fit: cover;
            object-fit: cover;
            overflow: hidden;
            border-radius: 50%;
        }

        .user-dashboard-info-box .titre {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: 30px 0;
        }

        .user-dashboard-info-box .prod-list td {
            vertical-align: middle;
        }

        .user-dashboard-info-box td li {
            margin: 0 4px;
        }

        .user-dashboard-info-box .table thead th {
            border-bottom: none;
        }

        .table.manage-prod-top th {
            border: 0;
        }

        .user-dashboard-info-box .candidate-list-favourite-time .candidate-list-favourite {
            margin-bottom: 10px;
        }

        .table.manage-prod-top {
            min-width: 650px;
        }

        .user-dashboard-info-box .candidate-list-details ul {
            color: #969696;
        }

        /* Candidate List */
        .candidate-list {
            background: #ffffff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            border-bottom: 1px solid #eeeeee;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: 20px;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .candidate-list:hover {
            -webkit-box-shadow: 0px 0px 34px 4px rgba(33, 37, 41, 0.06);
            box-shadow: 0px 0px 34px 4px rgba(33, 37, 41, 0.06);
            position: relative;
            z-index: 99;
        }

        .candidate-list:hover a.candidate-list-favourite {
            color: #e74c3c;
            -webkit-box-shadow: -1px 4px 10px 1px rgba(24, 111, 201, 0.1);
            box-shadow: -1px 4px 10px 1px rgba(24, 111, 201, 0.1);
        }

        .candidate-list .candidate-list-image {
            margin-right: 25px;
            -webkit-box-flex: 0;
            -ms-flex: 0 0 80px;
            flex: 0 0 80px;
            border: none;
        }

        .candidate-list .candidate-list-image img {
            width: 80px;
            height: 80px;
            -o-object-fit: cover;
            object-fit: cover;
        }

        .candidate-list-titre {
            margin-bottom: 5px;
        }

        .candidate-list-details ul {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-bottom: 0px;
        }

        .candidate-list-details ul li {
            margin: 5px 10px 5px 0px;
            font-size: 13px;
        }

        .candidate-list .candidate-list-favourite-time {
            margin-left: auto;
            text-align: center;
            font-size: 13px;
            -webkit-box-flex: 0;
            -ms-flex: 0 0 90px;
            flex: 0 0 90px;
        }

        .candidate-list .candidate-list-favourite-time span {
            display: block;
            margin: 0 auto;
        }

        .candidate-list .candidate-list-favourite-time .candidate-list-favourite {
            display: inline-block;
            position: relative;
            height: 40px;
            width: 40px;
            line-height: 40px;
            border: 1px solid #eeeeee;
            border-radius: 100%;
            text-align: center;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            margin-bottom: 20px;
            font-size: 16px;
            color: #646f79;
        }

        .candidate-list .candidate-list-favourite-time .candidate-list-favourite:hover {
            background: #ffffff;
            color: #e74c3c;
        }

        .candidate-banner .candidate-list:hover {
            position: inherit;
            -webkit-box-shadow: inherit;
            box-shadow: inherit;
            z-index: inherit;
        }

        .bg-white {
            background-color: #ffffff !important;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        .mb-0,
        .my-0 {
            margin-bottom: 0 !important;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        .user-dashboard-info-box .prod-list .thumb {
            margin-right: 20px;
        }
    </style>

    <style type="text/css">
        body {
            margin-top: 20px;
        }

        .p-15px {
            padding: 15px;
        }

        .border-color-gray {
            border-color: #f2f3fa;
        }

        .border-all-1 {
            border: 1px solid;
        }

        .hover-top {
            position: relative;
            top: 0;
        }

        .m-15px-tb {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .overlay-link {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            border: 0;
        }

        .border-radius-50 {
            border-radius: 50%;
        }

        .icon-50 {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 21px;
        }

        .white-color {
            color: #ffffff;
        }

        .theme-bg {
            background-color: #0050d8;
        }

        .icon-50 i.number {
            font-style: normal;
        }

        .icon-50 i {
            line-height: 50px;
        }

        .p-20px-l {
            padding-left: 20px;
        }

        .p-10px-lr {
            padding-left: 10px;
            padding-right: 10px;
        }

        .p-0px-tb {
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .border-radius-15 {
            border-radius: 15px;
        }

        .white-color {
            color: #ffffff;
        }

        .theme2nd-bg {
            background-color: #53d267;
        }

        .m-0px {
            margin: 0px;
        }

        .font-small {
            font-size: .75rem;
            line-height: 1rem;
        }

        .green-bg-alt {
            background-color: rgba(17, 226, 121, 0.1);
        }

        .green-bg {
            background-color: #11e279;
        }

        .green-after:after {
            background-color: #11e279;
        }

        .green-before:before {
            background-color: #11e279;
        }

        .green-color-alt {
            color: rgba(17, 226, 121, 0.65);
        }

        .green-color {
            color: #11e279;
        }

        .blue-bg-alt {
            background-color: rgba(21, 178, 236, 0.1);
        }

        .blue-bg {
            background-color: #15b2ec;
        }

        .blue-after:after {
            background-color: #15b2ec;
        }

        .blue-before:before {
            background-color: #15b2ec;
        }

        .blue-color-alt {
            color: rgba(21, 178, 236, 0.65);
        }

        .blue-color {
            color: #15b2ec;
        }

        .pink-bg-alt {
            background-color: rgba(241, 38, 153, 0.1);
        }

        .pink-bg {
            background-color: #f12699;
        }

        .pink-after:after {
            background-color: #f12699;
        }

        .pink-before:before {
            background-color: #f12699;
        }

        .pink-color-alt {
            color: rgba(241, 38, 153, 0.65);
        }

        .pink-color {
            color: #f12699;
        }

        .body-bg-alt {
            background-color: rgba(113, 128, 150, 0.1);
        }

        .body-bg {
            background-color: #718096;
        }

        .body-after:after {
            background-color: #718096;
        }

        .body-before:before {
            background-color: #718096;
        }

        .body-color-alt {
            color: rgba(113, 128, 150, 0.65);
        }

        .body-color {
            color: #718096;
        }

        .white-color-light {
            color: rgba(255, 255, 255, 0.65);
        }

        .bg-transparent {
            background-color: transparent;
        }

        .theme-g-bg {
            background: linear-gradient(to right, #0050d8, #002a72);
        }

        .dark-g-bg {
            background: linear-gradient(50deg, #273444 0, #272b44 100%);
        }

        .yellow-bg {
            background-color: #ffbe3d;
        }

        .border-radius-50 {
            border-radius: 50%;
        }

        .icon-50 {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 21px;
        }

        .box-shadow-only-hover:hover {
            box-shadow: 0 1.5rem 4rem rgba(22, 28, 45, 0.1);
        }

        .border-color-gray {
            border-color: #f2f3fa !important;
        }

        .border-all-1 {
            border: 1px solid;
        }

        .brand-image-container {
            width: 70px;
            /* Adjust the width as needed */
            height: 70px;
            /* Adjust the height as needed */
            overflow: hidden;
            border-radius: 50%;
        }

        .brand-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensure the image covers the container */
            border-radius: 50%;
        }


        .action-buttons {
            opacity: 0;
            position: absolute;
            top: 15px;
            right: 15px;
            transition: opacity 0.3s ease;
        }

        .box-shadow-only-hover:hover .action-buttons {
            opacity: 1;
        }

        .media-body {
            position: relative;
        }

        .media-body h6 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .media-body .badge {
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>

</head>


<body>
    <!-- Add New Brand Modal -->
    <div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addBrandForm" method="post" action="add_brand.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBrandModalLabel">Add New Brand</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="brandName">Brand Name</label>
                            <input type="text" class="form-control" id="brandName" name="brandName" required>
                        </div>
                        <div class="form-group">
                            <label for="brandPhoto">Brand Photo</label>
                            <input type="file" class="form-control-file" id="brandPhoto" name="brandPhoto">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add this modal after the </body> tag -->
    <div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editBrandForm" method="post" action="edit_brand.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editBrandName">Brand Name</label>
                            <input type="text" class="form-control" id="editBrandName" name="editBrandName" required>
                        </div>
                        <div class="form-group">
                            <label for="editBrandPhoto">Brand Photo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="editBrandPhoto" name="editBrandPhoto">
                                <label class="custom-file-label" for="editBrandPhoto">Choose file</label>
                            </div>
                            <div id="currentPhotoPreview" class="mt-2">
                                <!-- Display the current photo preview here -->
                                <!-- This will be updated dynamically using JavaScript -->
                            </div>
                        </div>

                        <input type="hidden" id="editBrandId" name="editBrandId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
    <div class="container mt-3 mb-4">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h5 class="card-title mb-0">Brand List <span class="text-muted fw-normal">(<?php echo $total_brands; ?>)</span></h5>
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

                    <a href="#" data-toggle="modal" data-target="#addBrandModal" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Add New</a>

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
            <div class="col-lg-12 mt-4 mt-lg-0">
                <div class="row" id="brandList">
                    <?php foreach ($rows as $row) : ?>
                        <?php
                        // Determine CSS class based on brand status
                        $status_class = ($row['brand_status'] == 0) ? 'status-inactive' : 'status-active';
                        // Determine status icon
                        $status_icon = ($row['brand_status'] == 0) ? 'far fa-eye-slash' : 'far fa-eye';
                        ?>
                        <div class="col-sm-6 col-lg-4 m-15px-tb brand-item">
                            <div class="media box-shadow-only-hover hover-top border-all-1 border-color-gray p-15px position-relative">
                                <div class="brand-image-container">
                                    <img class="img-fluid brand-image" src="<?php echo $row['brand_photo']; ?>" alt="<?php echo $row['brand_name']; ?>">
                                </div>

                                <div class="p-20px-l media-body">
                                    <h6 class="m-5px-tb"><?php echo $row['brand_name']; ?></h6>
                                    <a href="index.php?page=product&brand_id=<?php echo $row['brand_id']; ?>" class="badge badge-primary"><?php echo $row['product_count']; ?> Products</a>
                                    <div class="action-buttons">
                                        <ul class="list-unstyled mb-0 d-flex justify-content-end align-items-center">
                                            <li>
                                                <a href="#" data-toggle="tooltip" title="<?php echo ($row['brand_status'] == 0) ? 'Activate' : 'Deactivate'; ?>" class="text-primary toggle-brand-status" data-id="<?php echo $row['brand_id']; ?>">
                                                    <i class="<?php echo $status_icon; ?> fa-sm"></i> <!-- Status icon -->
                                                </a>
                                            </li>
                                            <li class="ml-3">
                                                <a href="#" class="text-info edit-brand" data-toggle="modal" data-target="#editBrandModal" data-id="<?php echo $row['brand_id']; ?>" data-name="<?php echo $row['brand_name']; ?>" data-photo="<?php echo $row['brand_photo']; ?>" title="Edit">
                                                    <i class="bx bx-pencil bx-xs"></i> <!-- Smaller pencil icon -->
                                                </a>
                                            </li>
                                            <li class="ml-3">
                                                <a href="#" class="text-danger delete-brand" data-id="<?php echo $row['brand_id']; ?>" title="Delete">
                                                    <i class="bx bx-trash bx-xs"></i> <!-- Smaller trash icon -->
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                </table>
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
</body>

</html>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setupPagination() {
        // Your pagination data
        const totalBrands = <?php echo $total_brands; ?>; // Total number of brands
        const itemsPerPage = 9; // Number of brands per page
        const maxPageLinks = 3; // Maximum number of pagination links to display

        // Function to display brands for the given page number
        function displayBrands(page) {
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalBrands);
            const brandItems = document.querySelectorAll(".brand-item");

            brandItems.forEach(function(brandItem, index) {
                if (index >= startIndex && index < endIndex) {
                    brandItem.style.display = "block"; // Show the brand item
                } else {
                    brandItem.style.display = "none"; // Hide the brand item
                }
            });
        }

        // Function to generate pagination links
        function generatePaginationLinks(currentPage) {
            const totalPages = Math.ceil(totalBrands / itemsPerPage);
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
                    displayBrands(prevPage);
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
                    displayBrands(page);
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
                    displayBrands(nextPage);
                    generatePaginationLinks(nextPage);
                }
            });
            paginationLinks.appendChild(nextLi);
        }

        // Initially display first page and generate pagination links
        displayBrands(1);
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
    $(document).on('click', '.toggle-brand-status', function(e) {
        e.preventDefault();
        var brandId = $(this).data('id');
        var icon = $(this).find('i');
        var currentStatus = icon.hasClass('fa-eye') ? 0 : 1; // Toggle the status

        $.ajax({
            url: 'update_brand_status.php',
            type: 'POST',
            data: {
                brandId: brandId,
                status: currentStatus
            },
            success: function(response) {
                if (response === 'success') {
                    // Toggle eye icon and update class
                    icon.toggleClass('fa-eye fa-eye-slash');
                    // Toggle status class
                    $(this).closest('.brand-item').toggleClass('status-active status-inactive');
                } else {
                    console.error('Failed to update brand status. Response: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#brandSearchInput').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            $('.brand-item').each(function() {
                var brandName = $(this).find('h6').text().toLowerCase();
                if (brandName.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('.edit-brand').click(function() {
            var brandId = $(this).data('id');
            var brandName = $(this).data('name');
            var brandPhoto = $(this).data('photo');

            $('#editBrandId').val(brandId);
            $('#editBrandName').val(brandName);

            var photoPreview = $('<img>').attr('src', brandPhoto).addClass('img-fluid').css('max-width', '100%');
            $('#currentPhotoPreview').html(photoPreview);

            $('#editBrandPhoto').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var newPhotoPreview = $('<img>').attr('src', e.target.result).addClass('img-fluid').css('max-width', '100%');
                        $('#currentPhotoPreview').html(newPhotoPreview);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });
        });

        $('.delete-brand').click(function() {
            var brandId = $(this).data('id');

            if (confirm('Are you sure you want to delete this brand?')) {
                $.ajax({
                    url: 'delete_brand.php',
                    method: 'POST',
                    data: {
                        brandId: brandId
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        });

        $('#editBrandForm').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'edit_brand.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    location.reload();
                }
            });
        });

        $('#addBrandForm').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>




</body>

</html>