<?php

// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();

//COMEÇA O SESSION
session_start();
//SE NO SESSION ESTIVER O A TABELA, COLUNA E ID
if (isset($_GET['table']) && $_GET['table'] != "") {

    if(isset($_GET['id']) && $_GET['id'] != "") {
        $id = $_GET['id'];
    }

    if(isset($_GET['id_formula']) && $_GET['id_formula'] != "" && isset($_GET['items_id']) && $_GET['items_id'] != "") {

        $formula_id=$_GET['id_formula'];
        $items_id=$_GET['items_id'];
    }

    if(isset($_GET['formula_location_id']) && $_GET['formula_location_id'] != "" && isset($_GET['id']) && $_GET['id'] != "") {

        $formula_location_id=$_GET['formula_location_id'];
        $id=$_GET['id'];
    }



    //GUARDA NAS VARIÁVEIS
    $table = $_GET['table'];



    //DEFINE A QUERY DE APAGAR
    if($table=="avatars") {

        $query="DELETE FROM avatars WHERE id=$id";
        $query_delete=$query;
    }
    //SE FOR FORMULA_ITENS
    else if($table=="formula_itens") {

        $query="DELETE FROM formula_itens WHERE formula_id=$formula_id AND items_id=$items_id";

        $query_delete=$query;

    }
    //SE FOR FORMULA_LOCATION
    else if($table=="formula_location") {

        $query="DELETE FROM formula_location WHERE id=$id";

        $query_delete=$query;

    }
    //SE FOR FORMULAS
    else if($table=="formulas") {

        $query="DELETE FROM formulas WHERE id=$id AND formula_location_id=$formula_location_id";

        $query_delete=$query;

    }
    //SE FOR ITEMS
    else if($table=="items") {

        $query="DELETE FROM items WHERE id=$id";

        $query_delete=$query;
    }
    //SE FOR MICROORGANISM_SETTINGS
    else if($table=="microorganism_settings") {

        $query="DELETE FROM microorganism_settings WHERE id=$id";

        $query_delete=$query;
    }
    //SE FOR PROFILES
    else if($table=="profiles") {

        $query="DELETE FROM profiles WHERE id=$id";

        $query_delete=$query;
    }



    //QUERY QUE PERMITE APAGAR O REGISTO
    $query_temp_disable = "SET FOREIGN_KEY_CHECKS = 0";

    //DÁ INÍCIO AO STATEMENT
    $stmt_temp_disable = mysqli_stmt_init($local_link);

    //PREPARA O STATEMENT
    if (mysqli_stmt_prepare($stmt_temp_disable, $query_temp_disable)) {

        //EXECUTA O STATEMENT
        if (mysqli_stmt_execute($stmt_temp_disable)) {



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
