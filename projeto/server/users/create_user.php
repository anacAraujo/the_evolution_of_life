<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With');

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    include_once "../connections/connection.php";

    $conn = new_db_connection();

    // Verify the user exists
    $sql = "SELECT username
        FROM users
        WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => false, 'message' => 'Username already exists.']);
        return;
    }

    // Create the user
    $sql = "INSERT INTO users (username, pwd_hash) VALUES (?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $pwd_hash);

    $stmt->execute();

    echo json_encode(['status' => true, 'message' => 'User inserted successfully.']);

    $stmt->close();
}
