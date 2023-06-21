<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

session_start();

$my_item = $data['my_item'];
$user_id = $_SESSION["id"];

include_once "../connections/connection.php";

$conn = new_db_connection();

// Get item id
$sql = "SELECT id FROM items WHERE symbol = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $my_item);
$stmt->execute();
$result = $stmt->get_result();
$my_item_id = $result->fetch_assoc()['id'];
$stmt->close();

if ($result->num_rows <= 0) {
    echo json_encode(['status' => false, 'message' => 'Not enough items.']);
    return;
}

// Update progress
$sql = "UPDATE planets_items_inventory 
            SET qty = ? 
            WHERE planets_user_id = ? AND item_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $my_item_id);
$stmt->execute();
$stmt->close();
