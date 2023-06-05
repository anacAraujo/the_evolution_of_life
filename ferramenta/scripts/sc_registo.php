<?php

require_once "../connections/connection.php";

//SE TODOS OS VALORES ESTIVEREM PREENCHIDOS
if (isset($_POST["Username"]) && isset($_POST["Password"]) && isset($_POST["Repeat_Password"])) {

    //GUARDA-OS NAS RESPETIVAS VARIÁVEIS
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $password_repeat = $_POST['Repeat_Password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $password_repeat_hash = password_hash($password_repeat, PASSWORD_DEFAULT);

    //COMPARA AS PASSWORDS
    if (password_verify($password_hash,$password_repeat_hash)) {

        //VAI PARA REGISTO COM INDICAÇÃO
        header("Location:../register.php?psswd=noMatch");

    } //SE CORRESPONDEREM
    else {

        //QUERY DE VERIFICAÇÃO NA BD
        $query_select = "SELECT * FROM users WHERE username = ?";

        //QUERY DE INSERÇÃO NA BD
        $query_insert = "INSERT INTO users(username, pwd_hash) VALUES (?,?)";

        //LINK À BD
        $local_link = new_db_connection();

        //INICIA O STATEMENT
        $stmt = mysqli_stmt_init($local_link);

        // Antes de inserir deve fazer-se uma consulta à BD para verificar se o username ou email já existem na BD
        //PREPARA A STATEMENT
        if (mysqli_stmt_prepare($stmt, $query_select)) {

            //BIND DOS PARÂMETROS
            mysqli_stmt_bind_param($stmt, 's', $username);

            //Executa o statement
            mysqli_stmt_execute($stmt);

            //VERIFICA SE EXISTEM RESULTADOS
            mysqli_stmt_store_result($stmt);

            //SE EXISTIREM RESULTADOS
            if (mysqli_stmt_num_rows($stmt) > 0) {

                //SE EXISTIREM
                header("Location:../register.php?error=registered");

            } else {

                if (mysqli_stmt_prepare($stmt, $query_insert)) {

                    //BIND DOS PARÂMETROS
                    mysqli_stmt_bind_param($stmt, 'ss', $username, $password_hash);

                    // Devemos validar também o resultado do execute!
                    if (mysqli_stmt_execute($stmt)) {

                        // Acção de sucesso
                        header("Location:../login.php");

                    } else {

                        // Acção de erro
                        header("Location:../register.php");
                    }
                } else {

                    // Acção de erro
                    echo "Error:" . mysqli_error($local_link);
                }

                //FECHA O STATEMENT E A LIGAÇÃO
                mysqli_stmt_close($stmt);
                mysqli_close($local_link);
            }
        } //SENÃO AVISA
        else {

            echo "<script>alert('O registo não foi bem sucedido! Tente novamente.');</script>";
        }

    }
}



