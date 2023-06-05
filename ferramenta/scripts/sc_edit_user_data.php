<?php
session_start();
// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//VAI AO SESSION BUSCAR O ID DO USER
if(isset($_SESSION['id_user']) && $_SESSION['id_user']!=""){

    //GUARDA NUMA VARIÁVEL
    $id_user=$_SESSION['id_user'];
}

//VAI BUSCAR O QUE O UTILIZADOR ESCREVEU
if(isset($_POST['pssw']) && $_POST['pssw']!="" && isset($_POST['pssw_repeat']) && $_POST['pssw_repeat'] !="") {

    //GUARDA NAS VARIÁVEIS
    $psswd=htmlspecialchars($_POST['pssw']);
    $psswd_repeat=htmlspecialchars($_POST['pssw_repeat']);

    //VERIFICA SE SÃO IGUAIS
    if($psswd===$psswd_repeat) {

        //INICIA O STATEMENT
        $stmt=mysqli_stmt_init($local_link);

        //DEFINE A QUERY
        $query="SELECT username,pwd_hash FROM users WHERE users.id=$id_user";

        //PREPARA O STATEMENT
        if(mysqli_stmt_prepare($stmt,$query)) {

            //EXECUTA O STATEMENT
            if(mysqli_stmt_execute($stmt)) {

                //DÁ BIND DOS RESULTADOS
                mysqli_stmt_bind_result($stmt,$username,$psswd_hash_BD);

                //VAI BUSCAR OS DADOS
                if (!mysqli_stmt_fetch($stmt)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=fetch");
                }

                //COMPARA AS PASSWORDS
                if(password_verify($psswd, $psswd_hash_BD)) {

                    //MANDA PARA A SESSÃO O USERNAME
                    $_SESSION['username']=$username;

                    //VAI PARA O LOCAL ONDE VAI EDITAR
                    header("Location:../edit_profile_data.php");
                }
                //SE FOREM DIFERENTES
                else {

                    //VAI PARA PERFIL E DIZ QUE AS CREDENCIAIS ESTÃO ERRADAS
                    header("Location:../profile.php?error=wrong");
                }

            }
            //SE DER ERRO NA EXECUÇÃO DO STATEMENT
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
    //SE NÃO FOREM IGUAIS
    else {

        //VOLTA À PÁGINA
        header("Location:../profile.php?error=noMatch");
    }

}

?>