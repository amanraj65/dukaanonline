<?php
session_start();
include('include/config.php');

// Fetch the number of products below the threshold
$query = mysqli_query($con, "SELECT COUNT(*) as total FROM products WHERE stock <= stock_threshold");
$row = mysqli_fetch_assoc($query);
$lowStockCount = $row['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | Dukaan Online</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <script src="scripts/jquery-1.9.1.min.js"></script>

    <style>
        @keyframes ring-bell {
            0% { transform: rotate(0deg); }
            15% { transform: rotate(-15deg); }
            30% { transform: rotate(15deg); }
            45% { transform: rotate(-10deg); }
            60% { transform: rotate(10deg); }
            75% { transform: rotate(-5deg); }
            100% { transform: rotate(0deg); }
        }

        .bell-ringing {
            animation: ring-bell 1s infinite;
        }
    </style>
</head>
<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                <i class="icon-reorder shaded"></i>
            </a>

            <a class="brand" href="todays-orders.php">
                Dukaan Online Portal | Admin
            </a>

            <div class="nav-collapse collapse navbar-inverse-collapse">
                <ul class="nav pull-right">
                    <li>
                        <a href="#">Admin</a>
                    </li>

                    <!-- Notification Bell -->
                    <li>
                        <a href="notifications.php">
                            <i class="icon-bell <?php echo ($lowStockCount > 0) ? 'bell-ringing' : ''; ?>" style="font-size: 20px;"></i>
                            <?php if ($lowStockCount > 0) { ?>
                                <span class="badge badge-important"><?php echo $lowStockCount; ?></span>
                            <?php } ?>
                        </a>
                    </li>

                    <li class="nav-user dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="images/user.png" class="nav-avatar" />
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="change-password.php">Change Password</a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.nav-collapse -->
        </div>
    </div><!-- /navbar-inner -->
</div><!-- /navbar -->

</body>
</html>