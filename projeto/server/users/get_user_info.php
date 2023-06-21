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
// CALCULAR PROGRESSO
$sql = "SELECT item_id, qty, goal, qnt_elements_default
        FROM planets_items_inventory
            INNER JOIN items
            ON planets_items_inventory.item_id = items.id
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
$progress = 0;
$num_elementos = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $num_elementos++;
        $item_qty = $row['qty'];
        $item_goal = $row['goal'];
        $qnt_elements_default = $row['qnt_elements_default'];
        if ($item_qty != 0 && $item_goal != 0) {
            if ($item_goal > $item_qty) {
                $progress = ($item_qty - $qnt_elements_default) / ($item_goal - $qnt_elements_default) * 100;
            } elseif ($item_goal < $item_qty) {
                $progress = ($qnt_elements_default - $item_qty) / ($item_goal - $qnt_elements_default) * 100;
            } else {
                $progress += 100;
            }
        }
    }

    if ($num_elementos != 0) {
        $progress = $progress / $num_elementos;
    } else {
        $progress = 0;
    }
    $progress = max(0, min($progress, 100));

    //var_dump($progress);
}

// Update progress
$sql = "UPDATE planets 
            SET progress = ? 
            WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $progress, $user_id);
$stmt->execute();
$stmt->close();


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
