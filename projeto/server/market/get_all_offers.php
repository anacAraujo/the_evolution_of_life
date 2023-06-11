<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once "../connections/connection.php";

$conn = new_db_connection();

$sql = "select * from market_offers";

$result = $conn->query($sql);

$response = array();

while ($row = $result->fetch_assoc()) {
    $response[] = $row;
}

echo json_encode($response);
