<?php
session_start();
// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//VAI AO SESSION BUSCAR O USER ID
if(isset($_SESSION['id_user']) && $_SESSION['id_user']!=""){

    //GUARDA NUMA VARIÁVEL
    $id_user=$_SESSION['id_user'];
}

//INICIA O STATEMENT
$stmt=mysqli_stmt_init($local_link);

//DEFINE A QUERY QUE VAI BUSCAR O AVATAR ATUAL DO USER
$query="SELECT path FROM users INNER JOIN avatars ON avatar_id= avatars.id WHERE users.id=$id_user";

//PREPARA O STATEMENT
if(!mysqli_stmt_prepare($stmt,$query)) {

    echo "Error" . mysqli_error;
}
else {

    //DÁ BIND DE RESULTADOS
    mysqli_stmt_bind_result($stmt,$current_avatar);

    //VAI BUSCAR O VALOR
    mysqli_stmt_fetch($stmt);

//EXECUTA O STATEMENT
if(!mysqli_stmt_execute($stmt)) {

    echo "Error" . mysqli_error;

}

}


//FECHA AS LIGAÇÕES
mysqli_stmt_close($stmt);
mysqli_close($local_link);