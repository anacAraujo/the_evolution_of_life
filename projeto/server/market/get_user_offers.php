<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once "../connections/connection.php";

$conn = new_db_connection();

session_start();

$user_id = $_SESSION["id"];

$sql = "SELECT *
        FROM market_offers mo
        WHERE mo.completed = 0 AND planets_user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

$response = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $my_item_id = $row['my_item_id'];
        $other_item_id = $row['other_item_id'];

        // Consulta para obter o símbolo do my_item
        $my_item_symbol_sql = "SELECT symbol
                               FROM items
                               WHERE id = ?";

        $stmt = $conn->prepare($my_item_symbol_sql);
        $stmt->bind_param("i", $my_item_id);
        $stmt->execute();
        $my_item_symbol_result = $stmt->get_result();

        if (!$my_item_symbol_result) {
            echo json_encode(['status' => false, 'message' => $conn->error]);
            return;
        }

        if ($my_item_symbol_result->num_rows > 0) {
            $my_item_symbol_row = $my_item_symbol_result->fetch_assoc();
            $row['my_item_symbol'] = $my_item_symbol_row['symbol'];
        }

        // Consulta para obter o símbolo do other_item
        $other_item_symbol_sql = "SELECT symbol
                                  FROM items
                                  WHERE id = ?";

        $stmt = $conn->prepare($other_item_symbol_sql);
        $stmt->bind_param("i", $other_item_id);
        $stmt->execute();
        $other_item_symbol_result = $stmt->get_result();

        if (!$other_item_symbol_result) {
            echo json_encode(['status' => false, 'message' => $conn->error]);
            return;
        }

        if ($other_item_symbol_result->num_rows > 0) {
            $other_item_symbol_row = $other_item_symbol_result->fetch_assoc();
            $row['other_item_symbol'] = $other_item_symbol_row['symbol'];
        }

        $response[] = $row;
    }
}

echo json_encode($response);
