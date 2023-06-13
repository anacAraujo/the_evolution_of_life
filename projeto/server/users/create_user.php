<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once "../connections/connection.php";

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $link = new_db_connection();

    $stmt = mysqli_stmt_init($link);

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
}
