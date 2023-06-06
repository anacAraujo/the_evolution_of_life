<?php
require_once "../connections/connection.php";

//VERIFICA SE OS VALORES ESTÃO PREENCHIDOS E GUARDA NAS VARIÁVEIS
if (isset($_POST["Username"]) && isset($_POST["Password"])) {
    $username = $_POST['Username'];
    $password = $_POST['Password'];


    //LIGA À BASE DE DADOS
    $local_link = new_db_connection();

    //INICIA O STATEMENT
    $stmt = mysqli_stmt_init($local_link);

    //GUARDA A QUERY A EXECUTAR
    $query = "SELECT users.id,profiles_id,pwd_hash FROM users INNER JOIN profiles ON profiles.id = profiles_id WHERE username LIKE ?";

    //PREPARA A QUERY
    if (mysqli_stmt_prepare($stmt, $query)) {

        //DÁ BIND DO LOGIN
        mysqli_stmt_bind_param($stmt, 's', $username);

        //SE A EXECUÇÃO DO STATEMENT TIVER SUCESSO
        if (mysqli_stmt_execute($stmt)) {

            //GUARDA OS RESULTADOS DA QUERY
            mysqli_stmt_bind_result($stmt, $id, $perfil, $password_hash);

            //FAZ FETCH E VERIFICA A PASSWORD
            if (mysqli_stmt_fetch($stmt)) {

                //SE FOR IGUAL
                if (password_verify($password, $password_hash)) {

                    // Guardar sessão de utilizador
                    session_start();
                    $_SESSION['id_user'] = $id;
                    $_SESSION["profile_id"] = $perfil;

                    // Feedback de sucesso
                    //VOLTA PARA INDEX
                    header("Location:../index.php");
                } else {

                    // Password está errada
                    header("Location:../login.php?error=incorrect");
                }
            } else {

                // Login não existe
                header("Location:../login.php?error=incorrect");
            }
        } else {

            // Acção de erro
            header("Location:../login.php");
        }
    } else {

        // Acção de erro
        header("Location:../login.php");
    }

    //FECHA O STATEMENT E A LIGAÇÃO
    mysqli_stmt_close($stmt);
    mysqli_close($local_link);

    //SE NÃO DER, MOSTRA

} else {
    echo "<script>alert('O registo não foi bem sucedido! Tente novamente.');</script>";
}
