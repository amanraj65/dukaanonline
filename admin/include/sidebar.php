<div class="span3">
    <div class="sidebar">
        <ul class="widget widget-menu unstyled">
            <li>
                <a href="todays-orders.php">
                    <i class="icon-tasks"></i>
                    Today's Orders
                    <?php
                    $f1 = "00:00:00";
                    $from = date('Y-m-d') . " " . $f1;
                    $t1 = "23:59:59";
                    $to = date('Y-m-d') . " " . $t1;
                    $result = mysqli_query($con, "SELECT * FROM Orders WHERE orderDate BETWEEN '$from' AND '$to'");
                    $num_rows1 = mysqli_num_rows($result);
                    ?>
                    <b class="label orange pull-right"><?php echo htmlentities($num_rows1); ?></b>
                </a>
            </li>
            <li>
                <a href="pending-orders.php">
                    <i class="icon-tasks"></i>
                    Pending Orders
                    <?php
                    $status = 'Delivered';
                    $ret = mysqli_query($con, "SELECT * FROM Orders WHERE orderStatus!='$status' OR orderStatus IS NULL");
                    $num = mysqli_num_rows($ret);
                    ?>
                    <b class="label orange pull-right"><?php echo htmlentities($num); ?></b>
                </a>
            </li>
            <li>
                <a href="delivered-orders.php">
                    <i class="icon-inbox"></i>
                    Delivered Orders
                    <?php
                    $status = 'Delivered';
                    $rt = mysqli_query($con, "SELECT * FROM Orders WHERE orderStatus='$status'");
                    $num1 = mysqli_num_rows($rt);
                    ?>
                    <b class="label green pull-right"><?php echo htmlentities($num1); ?></b>
                </a>
            </li>
            <li>
                <a href="most_sold_product.php">
                    <i class="icon-bar-chart"></i>
                    Most Sold Products
                </a>
            </li>
        </ul>

        <ul class="widget widget-menu unstyled">
            <li><a href="category.php"><i class="menu-icon icon-tasks"></i> Create Category </a></li>
            <li><a href="subcategory.php"><i class="menu-icon icon-tasks"></i> Sub Category </a></li>
            <li><a href="insert-product.php"><i class="menu-icon icon-paste"></i> Insert Product </a></li>
            <li><a href="manage-products.php"><i class="menu-icon icon-table"></i> Manage Products </a></li>
        </ul>

        <ul class="widget widget-menu unstyled">
            <li>
                <a href="notifications.php">
                    <i class="icon-bell"></i>
                    Stock Notifications
                    <?php
                    $stock_query = mysqli_query($con, "SELECT COUNT(*) AS low_stock FROM products WHERE stock <= stock_threshold");
                    $stock_result = mysqli_fetch_assoc($stock_query);
                    $low_stock_count = $stock_result['low_stock'];
                    ?>
                    <b class="label red pull-right"><?php echo htmlentities($low_stock_count); ?></b>
                </a>
            </li>
        </ul>

        <ul class="widget widget-menu unstyled">
            <li><a href="user-logs.php"><i class="menu-icon icon-tasks"></i> User Login Log </a></li>
            <li>
                <a href="logout.php">
                    <i class="menu-icon icon-signout"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div><!--/.sidebar-->
</div><!--/.span3-->