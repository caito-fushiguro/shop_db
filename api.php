<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set headers to allow cross-origin requests (CORS). Adjust as needed.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Read operation
    $result = $conn->query("SELECT * FROM orders");
    $orders = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    echo json_encode($orders);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create operation
    $data = json_decode(file_get_contents("php://input"));

    $user_id = $data->user_id;
    $name = $data->name;
    $number = $data->number;
    $email = $data->email;
    $method = $data->method;
    $address = $data->address;
    $total_products = $data->total_products;
    $total_price = $data->total_price;
    $placed_on = $data->placed_on;
    $payment_status = $data->payment_status;

    $sql = "INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$total_price', '$placed_on', '$payment_status')";
    $conn->query($sql);

    echo json_encode(array("message" => "Order created."));
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update operation
    $data = json_decode(file_get_contents("php://input"));

    $id = $data->id;
    // Handle the specific fields you want to update

    // An example update query for payment_status
    $payment_status = $data->payment_status;

    $sql = "UPDATE orders SET payment_status = '$payment_status' WHERE id = $id";
    $conn->query($sql);

    echo json_encode(array("message" => "Order updated."));
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete operation
    $id = $_GET['id'];

    $sql = "DELETE FROM orders WHERE id = $id";
    $conn->query($sql);

    echo json_encode(array("message" => "Order deleted."));
}

// Close the database connection
$conn->close();
?>
