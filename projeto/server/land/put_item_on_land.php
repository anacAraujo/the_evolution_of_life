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
$item_symbol = $data['item_symbol'];
$qty = 1;
$user_id = $_SESSION["id"];
$qty_atual = 0;

// Verify in PHP if symbol is H2O or Micro
if ($item_symbol != "H2O" && $item_symbol != "Organism") {
    echo json_encode([
        'status' => false, 'message' => 'Symbol not valid.'
    ]);
    return;
}

include_once "../connections/connection.php";

$conn = new_db_connection();

// Get item id given the item symbol
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
    echo json_encode([
        'status' => false, 'message' => 'Symbol not found.'
    ]);
    return;
}

// Verify user has item
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

if ($qty_atual <= 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Not enough quantity in user inventory.',
        'qty_atual' => $qty_atual,
    ]);
    return;
}

// Verify if land has items needed (Micro must have water, and water must be empty)
$sql = "SELECT qt, symbol 
        FROM planets_land_items 
        INNER JOIN items ON item_id = items.id
        WHERE user_id = ? AND land_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $land_id);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

if ($result->num_rows > 0 && $item_symbol === "H2O") {
    echo json_encode(['status' => false, 'message' => 'Cannot put water. Land must be empty']);
    return;
}

if ($result->num_rows <= 0 && $item_symbol === "Organism") {
    echo json_encode(['status' => false, 'message' => 'Cannot put organism. Land is empty']);
    return;
}

if ($result->num_rows > 0 && $item_symbol === "Organism") {
    $row = $result->fetch_assoc();
    $current_item = $row['symbol'];
    if ($current_item != 'H2O') {
        echo json_encode(['status' => false, 'message' => 'Cannot put organism. Land must have water']);
        return;
    }
}

$item_usage = 0;
//$current_time = new DateTime('now', new DateTimeZone('Europe/Lisbon'));

$current_time = date('Y-m-d H:i:s');
$int_land = (int)$land_id;
try {
    if ($item_symbol === "Organism") {
        //TODO Insert Organism to microorganism_usage
        $sql = "INSERT INTO microorganism_usage (break_start, item_usage, planets_land_items_item_id, planets_land_items_user_id, planets_land_items_land_id) 
        VALUES (?,?,?,?,?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "Error: " . $conn->error; // Exibe uma mensagem de erro caso a preparação da consulta falhe
        } else {
            var_dump($current_time);
            var_dump($item_usage);
            var_dump($item_id);
            var_dump($user_id);
            var_dump($int_land);

            $stmt->bind_param("siiii", $current_time, $item_usage, $item_id, $user_id, $int_land);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Remove item from inventory
    $qty_atual = $qty_atual - $qty;

    // Start transaction
    $conn->begin_transaction();

    $sql = "UPDATE planets_items_inventory 
            SET qty = ? 
            WHERE planets_user_id = ? AND item_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $qty_atual, $user_id, $item_id);
    $stmt->execute();
    $stmt->close();

    // Insert item to land
    $sql = "INSERT INTO planets_land_items (item_id, user_id, land_id, qt) 
            VALUES (?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $item_id, $user_id, $land_id, $qty);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    echo json_encode([
        'status' => true,
        'message' => 'Item inserted successfully.',
        'land_id' => $land_id,
        'session' => $_SESSION
    ]);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
