<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once "../connections/connection.php";

$conn = new_db_connection();

$sql = "SELECT *
        FROM market_offers mo
        WHERE mo.completed = 0";

$result = $conn->query($sql);

if (!$result) {
    echo ("Error description: " . $conn->error);
} else if ($result->num_rows > 0) {
    $response = array();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

    echo json_encode($response);
}
