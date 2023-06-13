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

include_once "../connections/connection.php";

$conn = new_db_connection();

// Verify the user has the items
$sql = "SELECT username
        FROM users
        WHERE username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if ($result->num_rows <= 0) {
    echo json_encode(['status' => false, 'message' => 'Username already exists.']);
    return;
}

// Create the offer
$sql = "INSERT INTO utilizadores (nome, email, login, password_hash) VALUES (?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $username, $email, $login, $password_hash);

$stmt->execute();

echo json_encode(['status' => true, 'message' => 'Offer inserted successfully.']);

$stmt->close();
