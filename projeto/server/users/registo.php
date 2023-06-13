<?php
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $pwd_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

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
        echo "Username already exists!";
        echo "<a href='../../client/registo.html'>Try again</a>";
        return;
    }

    // Create the user
    $sql = "INSERT INTO users (username, pwd_hash) VALUES (?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $pwd_hash);

    $stmt->execute();

    header("Location: ../../client/login.html");

    $stmt->close();
}
