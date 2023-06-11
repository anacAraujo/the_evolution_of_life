<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once "../connections/connection.php";

$conn = new_db_connection();

// TODO complete query select data
$sql = "SELECT mo.id AS offer_id, my_it.id AS my_item_id, my_it.name AS my_item_name,
            other_it.id AS other_item_id, other_it.name AS other_item_name
        FROM market_offers mo
            INNER JOIN items my_it ON mo.my_item_id = my_it.id
            INNER JOIN items other_it ON mo.other_item_id = other_it.id
        WHERE mo.completed = 0";

$result = $conn->query($sql);

if (!$result) {
    echo ("Error description: " . $conn->error);
} else if ($result->num_rows > 0) {
    $response = array();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

    echo json_encode($response);
} else {
    echo json_encode(['msg' => 'No Data!', 'status' => false]);
}
