<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

session_start();

if (!isset($_SESSION["id"])) {
    echo json_encode([
        'status' => false,
        'message' => 'No user logged in.',
        'code' => 'NO_LOGIN'
    ]);
    return;
}

$land_id = $data['land_id'];
$item_symbol = $data['symbol'];
$qty = 1;
$user_id = $_SESSION["id"];
$qty_atual = 0;

//TODO Verify in PHP if symbol is H2O or Micro
if ($item_symbol != "H2O" && $item_symbol != "Organism") {
    echo json_encode([
        'status' => true, 'message' => 'Not found.'
    ]);
    return;
}

include_once "../connections/connection.php";

$conn = new_db_connection();


//TODO Get item id given the item symbol (SELECT items)
$sql = "SELECT id FROM items WHERE symbol = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $item_symbol);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $item_id = $row['id'];
} else {
    $item_id = null;
}


//TODO Verify user has item (SELECT inventory)
$sql = "SELECT qty FROM planets_items_inventory WHERE item_id = ? AND planets_user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $qty_atual = $row['qty'];
} else {
    echo json_encode(['status' => false, 'message' => 'Item not found in user inventory.']);
    return;
}


//TODO verify if land has items needed (Micro must have water, and water must be empty) (SELECT planets_land_items)
$sql = "SELECT qt 
        FROM planets_land_items 
        WHERE user_id = ? AND land_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $item_id);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

if ($result->num_rows > 0 && $item_id === 3) {
    echo json_encode(['status' => false, 'message' => 'Cannot put water. Land must be']);
    return;
}

if ($result->num_rows > 0 && $item_id === 11) {
    $row = $result->fetch_assoc();
    $item_needed_id = $row['id'];
    if ($item_needed_id != 3) {
        echo json_encode(['status' => false, 'message' => 'Cannot put organism. Land must have water']);
        return;
    }
}

//TODO remove item from inventory (UPDATE inventory)
$qty_atual = $qty_atual - 1;
$sql = "UPDATE planets_items_inventory 
        SET qty =? 
        WHERE planets_user_id = ? AND item_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $qty_atual, $user_id, $item_id);

$stmt->execute();

echo json_encode([
    'status' => true, 'message' => 'Item inserted successfully.',
    'session' => $_SESSION
]);

$stmt->close();

// Insert item to land
$sql = "INSERT INTO planets_land_items (item_id, user_id, land_id, qt) 
    VALUES (?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $item_id, $user_id, $land_id, $qty);

$stmt->execute();

echo json_encode([
    'status' => true, 'message' => 'Item inserted successfully.',
    'session' => $_SESSION
]);

$stmt->close();
