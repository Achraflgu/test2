<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'db_connection.php';

// Fetch contacts from the database
$sql = "SELECT c.*, 
               COALESCE(cu.customer_name, c.name) AS contact_name, 
               COALESCE(cu.customer_email, c.email) AS contact_email 
        FROM contact c 
        LEFT JOIN customers cu ON c.customer_id = cu.customer_id
        ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
<head>
    <meta charset="utf-8">
    <title>Contacts Grid Cards - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style type="text/css">
        body {
            margin-top: 20px;
            background-color: #eee;
        }

        .card {
            margin-bottom: 24px;
            box-shadow: 0 2px 3px #e4e8f0;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #eff0f2;
            border-radius: 1rem;
        }

        .avatar-md {
            height: 4rem;
            width: 4rem;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #f1f3f7;
            border: 1px solid #eff0f2;
            border-radius: 0.75rem;
        }

        .avatar-title {
            align-items: center;
            background-color: #3b76e1;
            color: #fff;
            display: flex;
            font-weight: 500;
            height: 100%;
            justify-content: center;
            width: 100%;
        }

        .bg-soft-primary {
            background-color: rgba(59, 118, 225, .25) !important;
        }

        a {
            text-decoration: none !important;
        }

        .badge-soft-danger {
            color: #f56e6e !important;
            background-color: rgba(245, 110, 110, .1);
        }

        .badge-soft-success {
            color: #63ad6f !important;
            background-color: rgba(99, 173, 111, .1);
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 75%;
            font-weight: 500;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.75rem;
        }
        ol, ul {
     padding-left: 0rem; 
}
    </style>
</head>

<body>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <h5 class="card-title">Contact List <span class="text-muted fw-normal ms-2">(<?php echo mysqli_num_rows($result); ?>)</span></h5>
                </div>
            </div>
        </div>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt class="avatar-md rounded-circle img-thumbnail" /></div>
                                <div class="flex-1 ms-3">
                                    <h5 class="font-size-16 mb-1"><a href="#" class="text-dark"><?php echo $row['contact_name'] ?? 'Anonymous'; ?></a></h5>
                                    <span class="badge badge-soft-success mb-0"><?php echo $row['created_at']; ?></span>

                                </div>
                            </div>
                            <div class="mt-3 pt-1">
                                <p class="text-muted mb-0">
                                <i class="mdi mdi-email font-size-15 align-middle pe-2 text-primary"></i> <a href="mailto:<?php echo $row['contact_email']; ?>"><?php echo $row['contact_email']; ?></a>

                                </p>
                                <p class="text-muted mb-0 mt-2">
                                <i class="mdi mdi-file-document font-size-15 align-middle pe-2 text-primary"></i> <?php echo $row['subject']; ?>

                                </p>
                                <p class="text-muted mb-0 mt-2">
                                    <i class="mdi mdi-message-text font-size-15 align-middle pe-2 text-primary"></i> <?php echo $row['message']; ?>
                                </p>
                            </div>
                            <div class="d-flex gap-2 pt-4">
                                <button type="button" class="btn btn-primary btn-sm w-50" onclick="location.href='mailto:<?php echo $row['contact_email']; ?>'">
                                    <i class="bx bx-message-square-dots me-1"></i> Contact
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>