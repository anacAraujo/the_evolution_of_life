<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once "../connections/connection.php";

session_start();

if (!isset($_SESSION["id"])) {
    echo json_encode([
        'status' => false,
        'message' => 'No user logged in.',
        'code' => 'NO_LOGIN'
    ]);
    return;
}

$user_id = $_SESSION["id"];

$conn = new_db_connection();

$sql = "SELECT planets_user_id, item_id, qty, symbol
        FROM planets_items_inventory
            INNER JOIN items ON items.id = planets_items_inventory.item_id
        WHERE planets_user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

$response = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['qty'] > 0) {
            $response[] = $row;
        }
    }
}

echo json_encode($response);
