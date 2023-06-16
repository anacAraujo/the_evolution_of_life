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
//$land_id = $data['land_id'];
$qty = 1;
//TODO get item symbol from post
//TODO verify in PHP if symbol is H2O or Micro


// TODO get item id given the item symbol (SELECT items)
$item_id = 3;
$user_id = $_SESSION["id"];

include_once "../connections/connection.php";

$conn = new_db_connection();
//TODO verify user has item (SELECT inventory)

//TODO verify if land has items needed (Micro must have water, and water must be empty) (SELECT planets_land_items)

//TODO remove item from inventory (UPDATE inventory)

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
