<?php

// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//COMEÇA O SESSION
session_start();
//SE NO SESSION ESTIVER O A TABELA, COLUNA E ID
if (isset($_GET['table']) && $_GET['table'] != "" && isset($_GET['col']) && $_GET['col'] != "" && isset($_GET['id']) && $_GET['id'] != "") {

    //GUARDA NAS VARIÁVEIS
    $table = $_GET['table'];
    $id = $_GET['id'];
    $col = $_GET['col'];

    //QUERY QUE PERMITE APAGAR O REGISTO
    $query_temp_disable = "SET FOREIGN_KEY_CHECKS = 0";

    //DÁ INÍCIO AO STATEMENT
    $stmt_temp_disable = mysqli_stmt_init($local_link);

    //PREPARA O STATEMENT
    if (mysqli_stmt_prepare($stmt_temp_disable, $query_temp_disable)) {

        //EXECUTA O STATEMENT
        if (mysqli_stmt_execute($stmt_temp_disable)) {

            //QUERY PARA APAGAR
            $query_delete = "DELETE FROM $table WHERE id=$id";

            //DÁ INÍCIO AO STATEMENT
            $stmt_delete = mysqli_stmt_init($local_link);

            //PREPARA O STATEMENT
            if (mysqli_stmt_prepare($stmt_delete, $query_delete)) {

                //EXECUTA O STATEMENT
                if (mysqli_stmt_execute($stmt_delete)) {

                    //QUERY QUE PERMITE LIGAR A VERIFICAÇÃO
                    $query_enable = "SET FOREIGN_KEY_CHECKS = 1";

                    //DÁ INÍCIO AO STATEMENT
                    $stmt_enable = mysqli_stmt_init($local_link);

                    //PREPARA O STATEMENT
                    if (mysqli_stmt_prepare($stmt_enable, $query_enable)) {

                        //EXECUTA O STATEMENT
                        if (mysqli_stmt_execute($stmt_enable)) {
                            //SUCESSO
                            header("Location:../tables.php?table=$table&action=deleted");


                        } //SE DER ERRO
                        else {
                            header("Location:../tables.php?table=$table&action=notDeleted");;
                        }

                    }

                } //SENÃO
                else {
                    header("Location:../tables.php?table=$table&action=notDeleted");;
                }
            } //ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        } //SENÃO
        else {
            header("Location:../tables.php?table=$table&action=notDeleted");;
        }
    } //ERRO NA PREPARAÇÃO DO STATEMENT
    else {
        //VAI PARA A PÁGINA DE ERROS
        header("Location:../errors.php?error=prepare");
    }


//FECHA O STATEMENT E A LIGAÇÃO
    mysqli_stmt_close($stmt_enable);
    mysqli_stmt_close($stmt_delete);
    mysqli_stmt_close($stmt_temp_disable);
    mysqli_close($local_link);


} //SENÃO VAI PARA A PÁGINA DE ERRO
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=noData");


}


?>