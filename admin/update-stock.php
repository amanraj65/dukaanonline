<?php
include('include/config.php');
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Failed to update stock."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $stock = intval($_POST['stock']);

    if ($product_id > 0 && $stock >= 0) {
        $query = "UPDATE products SET stock = ? WHERE id = ?";
        $stmt = $con->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ii", $stock, $product_id);
            if ($stmt->execute()) {
                $response = ["status" => "success", "message" => "Stock updated successfully."];
            } else {
                $response["message"] = "Failed to execute update: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $response["message"] = "Failed to prepare statement: " . $con->error;
        }
    } else {
        $response["message"] = "Invalid product ID or stock value.";
    }
}

echo json_encode($response);
?>