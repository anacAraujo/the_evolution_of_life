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

$land_id = $data['land_id'];
$formula_name = $data['formula_name'];
$user_id = $_SESSION["id"];
$organism_id = 11;

include_once "../connections/connection.php";

$conn = new_db_connection();


// Get item id given the formula name
$sql = 'SELECT formulas.id, formula_itens.items_id, qty, side
        FROM formulas 
            INNER JOIN formula_itens ON formula_itens.formula_id = formulas.id
        WHERE formulas.name = ?';

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $formula_name);

$stmt->execute();

$result = $stmt->get_result();

$stmt->close();

if (!$result) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $formula_id = $row['id'];

    $formula_items = array();

    // Loop through the result set to collect item IDs
    do {
        $item_id = $row['items_id'];
        $qty = $row['qty'];
        $side = $row['side'];
        $formula_items[] = array('item_id' => $item_id, 'qty' => $qty, 'side' => $side);
    } while ($row = $result->fetch_assoc());
} else {
    echo json_encode([
        'status' => false, 'message' => 'Item not found.'
    ]);
    return;
}

// Verify user has items
$all_items_available = true;

foreach ($formula_items as $id_item) {
    $sql = "SELECT qty FROM planets_items_inventory WHERE item_id = ? AND planets_user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_item, $user_id);
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
}

if (!$all_items_available) {
    echo json_encode([
        'status' => false,
        'message' => 'Not enough quantity of items in user inventory.',
    ]);
    return;
}


// Get break duration
$sql = 'SELECT break_duration, max_usage 
        FROM microorganism_settings
        INNER JOIN planets ON id_settings = microorganism_settings.id
        WHERE user_id = ?';
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => false, 'message' => $conn->error]);
    return;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $break_duration = $row['break_duration'];
    $max_usage = $row['max_usage'];

    // Verify if organism is on break
    $sql = 'SELECT * FROM microorganism_usage WHERE planets_land_items_item_id = ? AND planets_land_items_user_id = ? AND planets_land_items_land_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $item_id, $user_id, $land_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $break_start = $row['break_start'];

        if (!empty($break_start)) {
            $break_end = strtotime($break_start) + $break_duration;
            $current_time = time();

            if ($current_time < $break_end) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Microorganism is on break.',
                    'code' => 'MAX_USAGE_REACHED'
                ]);
                return;
            }
        }
        $item_usage = $row['item_usage'];
        $break_start_reset = "null";
        if ($item_usage >= $max_usage) {
            $item_usage_reset = 0;
            $sql = "UPDATE microorganism_usage SET item_usage = ? AND break_start = ?
            WHERE planets_land_items_item_id = ? AND planets_land_items_user_id = ? AND planets_land_items_land_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isiii", $item_usage_reset, $break_start_reset, $item_id, $user_id, $land_id);

            $stmt->execute();
            $stmt->close();
        }
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Break duration not found.']);
    return;
}

echo json_encode([
    'status' => false,
    'message' => 'Microorganism is not on break.'
]);

// Remove and add items from iventory
foreach ($formula_items as $item) {
    $id_item = $item['item_id'];
    $qty = $item['qty'];
    $side = $item['side'];

    // Update planets_items_inventory
    if ($formula_name === "fotossintese") {
        $direcao = 0;
        if ($side === 1) {
            $sql = "UPDATE planets_items_inventory SET qty = qty - ? WHERE item_id = ? AND planets_user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $qty, $id_item, $user_id);
            $stmt->execute();
            $stmt->close();
        } else if ($side === 0) {
            $sql = "UPDATE planets_items_inventory SET qty = qty + ? WHERE item_id = ? AND planets_user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $qty, $id_item, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    } else if ($formula_name === "reproducao") {
        $direcao = 1;
        if ($side === 0) {
            $sql = "UPDATE planets_items_inventory SET qty = qty - ? WHERE item_id = ? AND planets_user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $qty, $id_item, $user_id);
            $stmt->execute();
            $stmt->close();
        } else if ($side === 1) {
            $sql = "UPDATE planets_items_inventory SET qty = qty + ? WHERE item_id = ? AND planets_user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $qty, $id_item, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Add formula do history - duplicated primary key

$sql = "INSERT INTO used_formulas_planet (planets_user_id, formula_id, direction) 
        VALUES (?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $formula_id, $direcao);
$stmt->execute();
$stmt->close();

$conn->commit();

echo json_encode([
    'status' => true,
    'message' => 'Formula inserted successfully.'
]);


// Increment organism actions
// Get current quantity
$sql = "SELECT item_usage FROM microorganism_usage
WHERE planets_land_items_item_id = ? AND planets_land_items_user_id = ? AND planets_land_items_land_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $item_id, $user_id, $land_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $item_usage = $row['item_usage'];
    $new_item_usage = $item_usage + 1;

    // Update planets_items_inventory
    $sql = "UPDATE microorganism_usage SET item_usage = ?
     WHERE planets_land_items_item_id = ? AND planets_land_items_user_id = ? AND planets_land_items_land_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $new_item_usage, $item_id, $user_id, $land_id);

    $stmt->execute();
    $stmt->close();
}


// Start break if organism has made max actions
if ($new_item_usage >= $max_usage) {
    $currentDateTime = date('Y-m-d H:i:s');

    $sql = "UPDATE microorganism_usage SET break_start = ?
    WHERE planets_land_items_item_id = ? AND planets_land_items_user_id = ? AND planets_land_items_land_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $currentDateTime, $item_id, $user_id, $land_id);

    $stmt->execute();
    $stmt->close();
    echo json_encode([
        'status' => false,
        'message' => 'Organism max usage reached.',
        'code' => 'MAX_USAGE_REACHED'
    ]);
    return;
}
