<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

$my_item_id = $data['my_item_id'];
$my_item_qty = $data['my_item_qty'];
$other_item_id = $data['other_item_id'];
$other_item_qty = $data['other_item_qty'];

// TODO use PHP SESSION ID to get the user id
$user_id = 1;

include_once "../connections/connection.php";

$conn = new_db_connection();

$query = "SELECT *
        FROM planets_items_inventory
        WHERE planets_user_id = $user_id AND item_id = $my_item_id AND qty >= $my_item_qty";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {

    $sql = "INSERT INTO market_offers (my_item_id, my_item_qty, other_item_id, other_item_qty, planets_user_id) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $my_item_id, $my_item_qty, $other_item_id, $other_item_qty, $user_id);

    $stmt->execute();

    echo json_encode(['status' => true, 'message' => 'Data Inserted Successfully!']);

    $stmt->close();
} else {
    echo json_encode(['status' => false, 'msg' => 'No items!']);
}
