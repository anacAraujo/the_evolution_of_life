<?php
if (isset($_POST["username"]) && isset($_POST["planet"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $planet_name = $_POST["planet"];
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

    $stmt->close();

    // Get id user
    $sql = "SELECT id FROM users WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $id_user = $row['id'];

        $stmt->close();

        // Create planet
        $sql = 'INSERT INTO planets (user_id, id_settings, name) VALUES (?,?,?)';

        $stmt = $conn->prepare($sql);

        $id_settings = 1;
        $stmt->bind_param('iis', $id_user, $id_settings, $planet_name);

        $stmt->execute();

        $stmt->close();

        // Create user inventory
        $sql = "INSERT INTO planets_items_inventory (planets_user_id, item_id, qty)
        SELECT ?, items.id, items.qnt_elements_default
        FROM items";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_user);

        $stmt->execute();

        header("Location: ../../client/login.html");

        $stmt->close();
    }
}
