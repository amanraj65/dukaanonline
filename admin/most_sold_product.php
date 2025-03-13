<?php
session_start();
include('include/config.php');
if(strlen($_SESSION['alogin']) == 0) {	
    header('location:index.php');
} else {
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('d-m-Y h:i:s A', time());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | Most Sold Products</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include('include/header.php'); ?>

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <?php include('include/sidebar.php'); ?>    
                <div class="span9">
                    <div class="content">
                        <div class="module">
                            <div class="module-head">
                                <h3>Most Sold Products</h3>
                            </div>
                            <div class="module-body">
                                <!-- Chart -->
                                <canvas id="mostSoldChart" width="400" height="200"></canvas>
                                <br>
                                <!-- Table -->
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Company</th>
                                            <th>Total Quantity Sold</th>
                                            <th>Total Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = mysqli_query($con, "SELECT p.productName, p.productCompany, SUM(o.quantity) AS total_quantity, 
                                                                         SUM(o.quantity * p.productPrice) AS total_revenue 
                                                                  FROM orders o 
                                                                  JOIN products p ON o.productId = p.id 
                                                                  GROUP BY o.productId 
                                                                  ORDER BY total_quantity DESC 
                                                                  LIMIT 10");
                                        $cnt = 1;
                                        $productNames = [];
                                        $quantities = [];
                                        while ($row = mysqli_fetch_array($query)) {
                                            echo "<tr>
                                                    <td>{$cnt}</td>
                                                    <td>{$row['productName']}</td>
                                                    <td>{$row['productCompany']}</td>
                                                    <td>{$row['total_quantity']}</td>
                                                    <td>₹" . number_format($row['total_revenue'], 2) . "</td>
                                                  </tr>";
                                            array_push($productNames, $row['productName']);
                                            array_push($quantities, $row['total_quantity']);
                                            $cnt++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>

    <script>
        var ctx = document.getElementById('mostSoldChart').getContext('2d');
        var mostSoldChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($productNames); ?>,
                datasets: [{
                    label: 'Quantity Sold',
                    data: <?php echo json_encode($quantities); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Sold'
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>

<?php } ?>