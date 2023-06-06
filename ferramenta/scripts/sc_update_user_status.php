<?php
// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o campo 'active' foi enviado como array
    if (isset($_POST['active']) && is_array($_POST['active'])) {
        // Conexão com o banco de dados
        include_once "../connections/connection.php";
        $local_link = new_db_connection();

        // Loop pelos valores enviados no dropdown
        foreach ($_POST['active'] as $userId => $activeValue) {
            // Sanitiza os valores
            $userId = mysqli_real_escape_string($local_link, $userId);
            $activeValue = mysqli_real_escape_string($local_link, $activeValue);

            // Atualiza o status do usuário no banco de dados
            $query = "UPDATE users SET active = '$activeValue' WHERE users.id = '$userId'";

            if (mysqli_query($local_link, $query)) {
                // Atualização bem-sucedida
                header("Location:../user_status.php");
            } else {
                // Erro na atualização
                echo "Erro ao atualizar o status para o usuário com ID $userId: " . mysqli_error($local_link);
            }
        }

        // Fecha a conexão com o banco de dados
        mysqli_close($local_link);
    } else {
        // Campo 'active' não foi enviado corretamente como array
        echo "Campo 'active' não foi enviado corretamente.";
    }
} else {
    // Método de requisição inválido
    echo "Método de requisição inválido.";
}
