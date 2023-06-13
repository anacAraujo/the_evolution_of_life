<?php

require_once "../connections/connection.php";

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $link = new_db_connection();

    $stmt = mysqli_stmt_init($link);

    $query = "SELECT users.id, username, profiles_id, pwd_hash 
    FROM users
    WHERE username LIKE ?";

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $username);

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $id_user, $username, $id_profil, $pwd_hash);

            if (mysqli_stmt_fetch($stmt)) {
                if (password_verify($password, $pwd_hash)) {
                    // Guardar sessão de utilizador
                    session_start();
                    $_SESSION["id"] = $id_user;
                    $_SESSION["username"] = $username;
                    $_SESSION["perfil"] = $id_profil;


                    // Feedback de sucesso
                    header("Location: ../../client/index.html");
                } else {
                    // Password está errada
                    echo "Incorrect credentials!";
                    echo "<a href='../../client/login.html'>Try again</a>";
                }
            } else {
                // Username não existe
                echo "Incorrect credentials!";
                echo "<a href='../../client/login.html'>Try again</a>";
            }
        } else {
            // Acção de erro
            echo "Error:" . mysqli_stmt_error($stmt);
        }
    } else {
        // Acção de erro
        echo "Error:" . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    echo "Campos do formulário por preencher";
}
