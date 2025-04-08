<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Remove Bootstrap Icons link -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }

        .welcome-section {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.7;
        }

        .stat-card h3 {
            font-size: 1.75rem;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <?php include "include/slidebar.php"; ?>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">

            </div>
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="row mb-4">
                    <div class="col">
                        <h2 class="mb-0">Dashboard</h2>
                    </div>
                </div>

                <div class="welcome-section">
                    <h4>Welcome back, Admin</h4>
                    <p class="text-muted mb-0">Here's what's happening with your system today.</p>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <i class="bi bi-book text-primary"></i>
                            <h3>254</h3>
                            <p class="text-muted">Total Books</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <i class="bi bi-people text-success"></i>
                            <h3>1,120</h3>
                            <p class="text-muted">Total Users</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <i class="bi bi-cart3 text-warning"></i>
                            <h3>56</h3>
                            <p class="text-muted">New Orders</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center">
                            <i class="bi bi-currency-dollar text-danger"></i>
                            <h3>$3,254</h3>
                            <p class="text-muted">Total Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


</body>

</html>