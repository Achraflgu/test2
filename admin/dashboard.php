<?php
// Check if the HTTP_REFERER is set and if it's coming from index.php
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'index.php') !== false) {
    // Check if the user is already logged in as admin, if not redirect to login.php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit(); // Stop further execution
    }

    include_once 'db_connection.php';

    // Query to get the count of customers
    $sql = "SELECT COUNT(*) AS customer_count FROM customers";
    $result = mysqli_query($conn, $sql);

    // Check if the query executed successfully
    if ($result) {
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);
        // Get the customer count
        $customerCount = $row['customer_count'];
    } else {
        // Handle error if the query fails
        $customerCount = "Error fetching count";
    }




    $sql = "SELECT COUNT(*) AS order_count FROM orders";
    $result = mysqli_query($conn, $sql);

    // Check if the query executed successfully
    if ($result) {
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);
        // Get the order count
        $orderCount = $row['order_count'];
    } else {
        // Handle error if the query fails
        $orderCount = "Error fetching count";
    }



    $sql = "SELECT SUM(total_amount) AS total_sales FROM orders";
    $result = mysqli_query($conn, $sql);

    // Check if the query executed successfully
    if ($result) {
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);
        // Get the total sales amount
        $totalSales =  number_format($row['total_sales'], 3) . " DT"; // Format the amount as currency
    } else {
        // Handle error if the query fails
        $totalSales = "Error fetching total sales";
    }

    $query = "SELECT 
              customers.customer_name, 
              customers.customers_photo, 
              orders.order_date, 
              CASE 
                  WHEN orders.payment_status = 'pending-paid' OR orders.payment_status = 'pending-unpaid' THEN 'pending' 
                  ELSE orders.payment_status 
              END AS payment_status
          FROM customers
          INNER JOIN orders ON customers.customer_id = orders.customer_id
          ORDER BY orders.order_date DESC
          LIMIT 5";
 // Change the limit as needed to fetch the desired number of recent orders

    $result = mysqli_query($conn, $query);
} else {
    // Redirect back to index.php if the request is not coming from index.php
    header("Location: index.php");
    exit(); // Stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin-top: 0;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        :root {

            --light: #F9F9F9;
            --blue: #3C91E6;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
        }

        html {
            overflow-x: hidden;
        }

        body.dark {
            --light: #3a3b3c;
            --grey: #060714;
            --dark: #FBFBFB;
        }

        body {
            background: var(--grey);
            overflow-x: hidden;
        }





        /* SIDEBAR */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100%;
            background: var(--light);
            z-index: 2000;
            font-family: var(--lato);
            transition: .3s ease;
            overflow-x: hidden;
            scrollbar-width: none;
        }

        #sidebar::--webkit-scrollbar {
            display: none;
        }

        #sidebar.hide {
            width: 60px;
        }

        #sidebar .brand {
            font-size: 24px;
            font-weight: 700;
            height: 56px;
            display: flex;
            align-items: center;
            color: var(--blue);
            position: sticky;
            top: 0;
            left: 0;
            background: var(--light);
            z-index: 500;
            padding-bottom: 20px;
            box-sizing: content-box;
        }

        #sidebar .brand .bx {
            min-width: 60px;
            display: flex;
            justify-content: center;
        }

        #sidebar .side-menu {
            width: 100%;
            margin-top: 48px;
        }

        #sidebar .side-menu li {
            height: 48px;
            background: transparent;
            margin-left: 6px;
            border-radius: 48px 0 0 48px;
            padding: 4px;
        }

        #sidebar .side-menu li.active {
            background: var(--grey);
            position: relative;
        }

        #sidebar .side-menu li.active::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            top: -40px;
            right: 0;
            box-shadow: 20px 20px 0 var(--grey);
            z-index: -1;
        }

        #sidebar .side-menu li.active::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            bottom: -40px;
            right: 0;
            box-shadow: 20px -20px 0 var(--grey);
            z-index: -1;
        }

        #sidebar .side-menu li a {
            width: 100%;
            height: 100%;
            background: var(--light);
            display: flex;
            align-items: center;
            border-radius: 48px;
            font-size: 16px;
            color: var(--dark);
            white-space: nowrap;
            overflow-x: hidden;
        }

        #sidebar .side-menu.top li.active a {
            color: var(--blue);
        }

        #sidebar.hide .side-menu li a {
            width: calc(48px - (4px * 2));
            transition: width .3s ease;
        }

        #sidebar .side-menu li a.logout {
            color: var(--red);
        }

        #sidebar .side-menu.top li a:hover {
            color: var(--blue);
        }

        #sidebar .side-menu li a .bx {
            min-width: calc(60px - ((4px + 6px) * 2));
            display: flex;
            justify-content: center;
        }

        /* SIDEBAR */





        /* CONTENT */
        #content {
            position: relative;
            width: calc(100% - 280px);
            left: 280px;
            transition: .3s ease;
        }

        #sidebar.hide~ {
            width: calc(100% - 60px);
            left: 60px;
        }










        /* MAIN */
        main {
            width: 100%;
            font-family: var(--poppins);
            max-height: calc(100vh - 56px);
            overflow-y: auto;
        }

        main .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            grid-gap: 16px;
            flex-wrap: wrap;
        }

        main .head-title .left h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }

        main .head-title .left .breadcrumb {
            display: flex;
            align-items: center;
            grid-gap: 16px;
        }

        main .head-title .left .breadcrumb li {
            color: var(--dark);
        }

        main .head-title .left .breadcrumb li a {
            color: var(--dark-grey);
            pointer-events: none;
        }

        main .head-title .left .breadcrumb li a.active {
            color: var(--blue);
            pointer-events: unset;
        }

        main .head-title .btn-download {
            height: 36px;
            padding: 0 16px;
            border-radius: 36px;
            background: var(--blue);
            color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            grid-gap: 10px;
            font-weight: 500;
        }




        main .box-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            grid-gap: 24px;
            margin-top: 36px;
        }

        main .box-info li {
            padding: 24px;
            background: var(--light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
        }

        main .box-info li .bx {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            font-size: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        main .box-info li:nth-child(1) .bx {
            background: var(--light-blue);
            color: var(--blue);
        }

        main .box-info li:nth-child(2) .bx {
            background: var(--light-yellow);
            color: var(--yellow);
        }

        main .box-info li:nth-child(3) .bx {
            background: var(--light-orange);
            color: var(--orange);
        }

        main .box-info li .text h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }

        main .box-info li .text p {
            color: var(--dark);
        }





        main .table-data {
            display: flex;
            flex-wrap: wrap;
            grid-gap: 24px;
            margin-top: 24px;
            width: 100%;
            color: var(--dark);
        }

        main .table-data>div {
            border-radius: 20px;
            background: var(--light);
            padding: 24px;
            overflow-x: auto;
        }

        main .table-data .head {
            display: flex;
            align-items: center;
            grid-gap: 16px;
            margin-bottom: 24px;
        }

        main .table-data .head h3 {
            margin-right: auto;
            font-size: 24px;
            font-weight: 600;
        }

        main .table-data .head .bx {
            cursor: pointer;
        }

        main .table-data .order {
            flex-grow: 1;
            flex-basis: 500px;
        }

        main .table-data .order table {
            width: 100%;
            border-collapse: collapse;
        }

        main .table-data .order table th {
            padding-bottom: 12px;
            font-size: 13px;
            text-align: left;
            border-bottom: 1px solid var(--grey);
        }

        main .table-data .order table td {
            padding: 16px 0;
        }

        main .table-data .order table tr td:first-child {
            display: flex;
            align-items: center;
            grid-gap: 12px;
            padding-left: 6px;
        }

        main .table-data .order table td img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        main .table-data .order table tbody tr:hover {
            background: var(--grey);
        }

        main .table-data .order table tr td .status {
            font-size: 10px;
            padding: 6px 16px;
            color: var(--light);
            border-radius: 20px;
            font-weight: 700;
        }

        main .table-data .order table tr td .status.completed {
            background: var(--blue);
        }

        main .table-data .order table tr td .status.process {
            background: var(--yellow);
        }

        main .table-data .order table tr td .status.pending {
            background: var(--orange);
        }

        main .table-data .order table tr td .status.failed {
    background: var(--red); /* Adjust color as needed */
}


        main .table-data .todo {
            flex-grow: 1;
            flex-basis: 300px;
        }

        main .table-data .todo .todo-list {
            width: 100%;
        }

        main .table-data .todo .todo-list li {
            width: 100%;
            margin-bottom: 16px;
            background: var(--grey);
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        main .table-data .todo .todo-list li .bx {
            cursor: pointer;
        }

        main .table-data .todo .todo-list li.completed {
            border-left: 10px solid var(--blue);
        }

        main .table-data .todo .todo-list li.not-completed {
            border-left: 10px solid var(--orange);
        }

        main .table-data .todo .todo-list li:last-child {
            margin-bottom: 0;
        }

        /* MAIN */
        /* CONTENT */









        @media screen and (max-width: 768px) {


            main .box-info {
                grid-template-columns: 1fr;
            }

            main .table-data .head {
                min-width: 420px;
            }

            main .table-data .order table {
                min-width: 420px;
            }

            main .table-data .todo .todo-list {
                min-width: 420px;
            }
        }

        /* charts */

        .charts {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-gap: 20px;
            width: 100%;
            padding: 20px;
            padding-top: 0;
        }

        .chart {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
            width: 100%;
        }

        .chart h2 {
            margin-bottom: 5px;
            font-size: 20px;
            color: #666;
            text-align: center
        }

        @media (max-width:880px) {

            /* .topbar {
        grid-template-columns: 1.6fr 6fr 0.4fr 1fr;
    } */
            .fa-bell {
                justify-self: left;
            }

            .cards {
                width: 100%;
                padding: 35px 20px;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                grid-gap: 20px;
            }

            .charts {
                grid-template-columns: 1fr;
            }

            .doughnut-chart {
                padding: 50px;
            }

            #doughnut {
                padding: 50px;
            }
        }

        @media (max-width:500px) {
            .topbar {
                grid-template-columns: 1fr 5fr 0.4fr 1fr;
            }

            .logo h2 {
                font-size: 20px;
            }

            .search {
                width: 80%;
            }

            .search input {
                padding: 0 20px;
            }

            .fa-bell {
                margin-right: 5px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .doughnut-chart {
                padding: 10px;
            }

            #doughnut {
                padding: 0px;
            }

            .user {
                width: 40px;
                height: 40px;
            }
        }
        .hover-effect:hover {
    background-color: #f0f0f0;
    cursor: pointer;
}

    </style>
</head>

<body>
    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Home</a>
                    </li>
                </ul>
            </div>
            <a href="#" class="btn-download" id="btn-download-pdf">
                <i class='bx bxs-cloud-download'></i>
                <span class="text">Download PDF</span>
            </a>
            <script>
                document.getElementById('btn-download-pdf').addEventListener('click', function() {
                    window.print();
                });
            </script>

        </div>

        <ul class="box-info">
    <li onclick="location.href='http://localhost/msport/admin/index.php?page=orders';" class="hover-effect">
        <i class='bx bxs-calendar-check'></i>
        <span class="text">
            <h3><?php echo $orderCount; ?></h3>
            <p>Orders</p>
        </span>
    </li>
    <li onclick="location.href='http://localhost/msport/admin/index.php?page=customers';" class="hover-effect">
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3><?php echo $customerCount; ?></h3>
            <p>Customers</p>
        </span>
    </li>
    <li onclick="location.href='http://localhost/msport/admin/index.php?page=payments';" class="hover-effect">
        <i class='bx bxs-dollar-circle'></i>
        <span class="text">
            <h3><?php echo $totalSales; ?></h3>
            <p>Total Sales</p>
        </span>
    </li>
</ul>



        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Recent Orders</h3>
                    <i class='bx bx-search'></i>
                    <i class='bx bx-filter'></i>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Date Order</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $row['customers_photo']; ?>" alt="Customer Photo">
                                        <p><?php echo $row['customer_name']; ?></p>
                                    </td>
                                    <td><?php echo date('d-m-Y', strtotime($row['order_date'])); ?></td>
                                    <td>
    <span class="status <?php
        switch ($row['payment_status']) {
            case 'pending':
                echo 'process'; // Both "pending-paid" and "pending-unpaid" will be treated as "Pending"
                break;
            case 'complete':
                echo 'completed';
                break;
            case 'failed':
                echo 'failed'; // Specific class for "failed"
                break;
            case 'paid':
                echo 'paid'; // Specific class for "paid"
                break;
            default:
                echo ''; // No specific class for other cases
        }
        
        
    ?>"><?php echo $row['payment_status']; ?></span>
</td>

                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="3">No recent orders found.</td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>

            <?php
            // Include database connection
            include_once 'db_connection.php'; // Assuming you have this file

            // Query to retrieve the top 5 most sold products
            $query = "SELECT p.product_name, p.product_stock_quantity, SUM(oi.quantity) AS total_sold
          FROM products p
          INNER JOIN orderitems oi ON p.product_id = oi.product_id
          GROUP BY p.product_id
          ORDER BY total_sold DESC
          LIMIT 5";

            $result = mysqli_query($conn, $query);
            ?>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <style>
                .todo {
                    width: 80%;
                    margin: 0 auto;
                }

                .sales-list {
                    list-style-type: none;
                    padding: 0;
                }

                .sales-item {
                    margin-bottom: 10px;
                }

                canvas {
                    margin-top: 20px;
                    max-width: 400px;
                    margin-left: auto;
                    margin-right: auto;
                    display: block;
                }
            </style>
            <div class="todo">
                <div class="head">
                    <h3>Best Products Soldet</h3>
                </div>
                <ul class="sales-list">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $productNames = [];
                        $quantitiesSold = [];
                        while ($row = mysqli_fetch_assoc($result)) {
                            $productNames[] = $row['product_name'];
                            $quantitiesSold[] = $row['total_sold'];
                        }
                    ?>
                        <canvas id="pie-chart"></canvas>
                        <script>
                            var ctx = document.getElementById('pie-chart').getContext('2d');
                            var myChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: <?php echo json_encode($productNames); ?>,
                                    datasets: [{
                                        label: 'Products Sold',
                                        data: <?php echo json_encode($quantitiesSold); ?>,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.7)',
                                            'rgba(54, 162, 235, 0.7)',
                                            'rgba(255, 206, 86, 0.7)',
                                            'rgba(75, 192, 192, 0.7)',
                                            'rgba(153, 102, 255, 0.7)'
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: false,
                                    maintainAspectRatio: false,
                                    legend: {
                                        position: 'right'
                                    }
                                }
                            });
                        </script>
                    <?php
                    } else {
                    ?>
                        <li class="no-products">No products found.</li>
                    <?php
                    }
                    ?>
                </ul>
            </div>

            <?php
            // Close database connection
            mysqli_close($conn);
            ?>



        </div>
        <div class="todo">

        </div>
    </main>





</body>

</html>