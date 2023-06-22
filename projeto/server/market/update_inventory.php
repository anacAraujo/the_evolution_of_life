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

if (!isset($data['land_id']) || !isset($data['formula_name'])) {
    echo json_encode([
        'status' => false,
        'message' => 'No data.'
    ]);
    return;
}

$my_item_id = $data['my_item_id'];
$my_item_qty = $data['my_item_qty'];
$other_item = $data['other_item'];
$other_item_qty = $data['other_item_qty'];
$planets_user_id = $data['planets_user_id'];
$user_id = $_SESSION["id"];

include_once "../connections/connection.php";

$conn = new_db_connection();

// Verify if user has item
$sql = "SELECT qty FROM planets_items_inventory WHERE item_id = ? AND planets_user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $other_item, $user_id);
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

    if ($qty_atual <= 0 && $item_id !== 11) {
        $all_items_available = false;
        break;
    }
} else {
    $all_items_available = false;
    break;
}

//update user login preço
$sql = "UPDATE planets_items_inventory SET qty = qty -?
WHERE planets_user_id = ? AND item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $other_item_qty, $user_id, $other_item);

$stmt->execute();
$stmt->close();

//update user login compra
$sql = "UPDATE planets_items_inventory SET qty = qty + ?
WHERE planets_user_id = ? AND item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $my_item_id, $user_id, $my_item_qty);

$stmt->execute();
$stmt->close();

//update user who offerd login preço
$sql = "UPDATE planets_items_inventory SET qty = qty + ?
WHERE planets_user_id = ? AND item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $my_item_id, $user_id, $my_item_qty);

$stmt->execute();
$stmt->close();

//update user who offerd compra
$sql = "UPDATE planets_items_inventory SET qty = qty - ?
WHERE planets_user_id = ? AND item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $other_item, $user_id, $other_item_qty);

$stmt->execute();
$stmt->close();
