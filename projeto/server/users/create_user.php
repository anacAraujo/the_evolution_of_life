<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once "../connections/connection.php";

if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["login"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $link = new_db_connection();

    $stmt = mysqli_stmt_init($link);
    // Antes de inserir deve fazer-se uma consulta à BD para verificar se o username ou email já existem na BD

    $query = "INSERT INTO utilizadores (nome, email, login, password_hash) VALUES (?,?,?,?)";

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'ssss', $username, $email, $login, $password_hash);

        // Devemos validar também o resultado do execute!
        if (mysqli_stmt_execute($stmt)) {
            // Acção de sucesso
            header("Location: ../login.php");
        } else {
            // Acção de erro
            header("Location: ../registo.php");
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
