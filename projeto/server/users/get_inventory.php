<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once "../connections/connection.php";

$conn = new_db_connection();

// TODO use PHP SESSION ID to get the user id
$sql = "SELECT *
        FROM planets_items_inventory
        WHERE planets_user_id = 1";

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
