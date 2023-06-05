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

//VÊ SE OS VALORES VÊM DEFINIDOS
if(isset($_POST['username']) && $_POST['username'] !="" && $_POST['password'] && $_POST['password'] !="") {

    //GUARDA EM VARIÁVEIS
    $new_username = htmlspecialchars($_POST['username']);

    //TRANSORMA EM HASH A NOVA PASSWORD
    $new_psswd= password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);

    //INICIA O STATEMENT
    $stmt=mysqli_stmt_init($local_link);

    //DEFINE A QUERY
    $query="UPDATE users SET username =?,pwd_hash=? WHERE users.id=$id_user ";

    //PREPARA O STATEMENT
    if(mysqli_stmt_prepare($stmt,$query)) {

        //DÁ BIND DOS PARÂMETROS
        mysqli_stmt_bind_param($stmt,'ss',$new_username,$new_psswd);

        //EXECUTA O STATEMENT
        if(mysqli_stmt_execute($stmt)) {

            //DADOS ATUALIZADOS
            header("Location:../profile.php?action=updated");

        }
        //SE DER ERRO NA EXECUÇÃO
        else {

            //VAI PARA A PÁGINA DE ERROS
            header("Location:../errors.php?error=execute");

        }


    }
    //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
    else {

        //VAI PARA A PÁGINA DE ERROS
        header("Location:../errors.php?error=prepare");

    }


}
//SE NÃO TIVER DADOS
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=noData");

}


?>

