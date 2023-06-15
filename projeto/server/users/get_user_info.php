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
//TODO CALCULAR PROGRESSO

// Get all user info
$sql = 'SELECT id_settings, "name", progress, avatar_id
        FROM planets
            INNER JOIN users ON users.id = planets.user_id
        WHERE users.id = ?';

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

if ($result->num_rows <= 0) {
    echo json_encode(['status' => false, 'message' => 'User not found.']);
    return;
}

$row = $result->fetch_assoc();
echo json_encode($row);
