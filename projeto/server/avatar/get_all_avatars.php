<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//INICIA O STATEMENT
$stmt = mysqli_stmt_init($local_link);

//DEFINE A QUERY
$query = "SELECT id, path FROM avatars";

//CRIA O ARRAY QUE OS VAI GUARDAR
$avatars = array();

//PREPARA O STATEMENT
if (mysqli_stmt_prepare($stmt, $query)) {

    //DÁ BIND DOS RESULTADOS
    mysqli_stmt_bind_result($stmt, $avatar_id, $avatar_path);

    //EXECUTA O STATEMENT
    if (mysqli_stmt_execute($stmt)) {


        //VAI BUSCAR OS DADOS
        while (mysqli_stmt_fetch($stmt)) {

            //MANDA PARA O ARRAY
            $avatars[$id] = $avatar_path;
        }
    } else {
        echo "Error" . mysqli_error($local_link);
    }
} else {
    echo "Error" . mysqli_error($local_link);
}

//FECHA AS LIGAÇÕES
mysqli_stmt_close($stmt);
mysqli_close($local_link);
