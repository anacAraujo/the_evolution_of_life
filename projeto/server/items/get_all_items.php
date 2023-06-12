<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once "../connections/connection.php";

$conn = new_db_connection();

$sql = "SELECT *
        FROM items";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

$response = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

echo json_encode($response);
