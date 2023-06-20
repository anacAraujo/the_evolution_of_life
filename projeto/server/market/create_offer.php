<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

session_start();

$my_item = $data['my_item'];
$my_item_qty = $data['my_item_qty'];
$other_item = $data['other_item'];
$other_item_qty = $data['other_item_qty'];

$user_id = $_SESSION["id"];

include_once "../connections/connection.php";

$conn = new_db_connection();

// Get items id
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

// Get items id
$sql = "SELECT id FROM items WHERE symbol = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $other_item);
$stmt->execute();
$result = $stmt->get_result();
$other_item_id = $result->fetch_assoc()['id'];
$stmt->close();

if ($result->num_rows <= 0) {
    echo json_encode(['status' => false, 'message' => 'Not enough items.']);
    return;
}

// Verify the user has the items
$sql = "SELECT *
        FROM planets_items_inventory
        WHERE planets_user_id = ? AND item_id = ? AND qty >= ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $my_item_id, $my_item_qty);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if ($result->num_rows <= 0) {
    echo json_encode(['status' => false, 'message' => 'Not enough items.']);
    return;
}

// Verify num offers per user - max 6 
$sql = "SELECT id
        FROM market_offers
        WHERE planets_user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if ($result->num_rows >= 6) {
    echo json_encode(['status' => false, 'message' => 'All user offers are active.']);
    return;
}

// Create the offer
$sql = "INSERT INTO market_offers (my_item_id, my_item_qty, other_item_id, other_item_qty, planets_user_id) 
    VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii", $my_item_id, $my_item_qty, $other_item_id, $other_item_qty, $user_id);

$stmt->execute();

echo json_encode([
    'status' => true,
    'message' => 'Offer inserted successfully.',
    'session' => $_SESSION
]);

$stmt->close();
