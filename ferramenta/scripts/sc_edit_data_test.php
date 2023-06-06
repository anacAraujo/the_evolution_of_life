<?php

session_start();
var_dump($_POST);
// conexão à base de dados
include_once "../connections/connection.php";

// Create a new DB connection
$local_link = new_db_connection();


//SE NO SESSION ESTIVER O A TABELA, COLUNA E ID
if (isset($_SESSION['table']) && $_SESSION['table'] != "" && isset($_SESSION['id']) && $_SESSION['id'] != "") {

    //GUARDA NAS VARIÁVEIS
    $table = $_SESSION['table'];
    $id = $_SESSION['id'];

    //VÊ QUE TABELA VAIS ATUALIZAR
    //SE FOR AVATARS
    if($table=="avatars") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if(isset($_POST['path']) && $_POST['path'] !="") {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $avatar_path=htmlspecialchars($_POST['path']);

            //INICIA O STATEMENT QUE ALTERA A TABELA AVATARES
            $stmt_change_avatars=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_avatars="INSERT INTO avatars(path) VALUE=$avatar_path";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_avatars,$query_change_avatars)) {

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_avatars)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_avatars);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }




        }
        else {
            //VAI PARA A PÁGINA DE ERROS
            header("Location:../errors.php?error=noData");
        }
    }
    //SE A TABELA FOR FORMULAS
    else if($table=="formulas") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['id']) && $_POST['id']!="") && (isset($_POST['formula_location_id']) && $_POST['formula_location_id'] !="") && (isset($_POST['name']) && $_POST['name'] !="")) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $formula_id=htmlspecialchars($_POST['id']);
            $formula_location_id=htmlspecialchars($_POST['formula_location_id']);
            $formula_name=htmlspecialchars($_POST['name']);

            //INICIA O STATEMENT QUE ALTERA A TABELA FORMULAS
            $stmt_change_formulas=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_formulas="UPDATE formulas SET formula_location_id=?,name=? WHERE id=$formula_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_formulas,$query_change_formulas)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_formulas,'is',$formula_location_id,$formula_name);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_formulas)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_formulas);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }




        }
        else {
            //VAI PARA A PÁGINA DE ERROS
            header("Location:../errors.php?error=noData");
        }

    }
    //SE A TABELA FOR FORMULAS_ITENS
    else if($table=="formula_itens") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['formula_id']) && $_POST['formula_id']!="") && (isset($_POST['items_id']) && $_POST['items_id'] !="") && (isset($_POST['qty']) && $_POST['qty'] !="") && (isset($_POST['side']) && $_POST['side'] !="")) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $formula_id=htmlspecialchars($_POST['id']);
            $formula_itens_items_id=htmlspecialchars($_POST['items_id']);
            $formula_itens_qty=htmlspecialchars($_POST['qty']);
            $formula_itens_side=htmlspecialchars($_POST['side']);

            //INICIA O STATEMENT QUE ALTERA A TABELA FORMULA_ITENS
            $stmt_change_formula_itens=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_formula_itens="UPDATE formula_itens SET formula_id=?,items_id=?,qty=?,side=? WHERE id=$formula_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_formula_itens,$query_change_formula_itens)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_formula_itens,'iiii',$formula_id,$formula_itens_items_id,$formula_itens_qty,$formula_itens_side);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_formula_itens)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_formula_itens);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }
    //SE A TABELA FOR FORMULAS_LOCATION
    else if($table=="formula_location") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['id']) && $_POST['id']!="") && (isset($_POST['name']) && $_POST['name'] !="")) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $formula_location_id=htmlspecialchars($_POST['id']);
            $formula_location_name=htmlspecialchars($_POST['name']);

            //INICIA O STATEMENT QUE ALTERA A TABELA FORMULA LOCATION
            $stmt_change_formula_location=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_formula_location="UPDATE formula_location SET name=? WHERE id=$formula_location_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_formula_location,$query_change_formula_location)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_formula_location,'s',$formula_location_name);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_formula_location)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_formula_location);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }
    //SE A TABELA FOR ITEMS
    else if($table=="items") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['id']) && $_POST['id']!="") && (isset($_POST['name']) && $_POST['name'] !="") && (isset($_POST['symbol']) && $_POST['symbol'] !="") && (isset($_POST['goal']) && $_POST['goal'] !="") && (isset($_POST['qnt_elements_default']) && $_POST['qnt_elements_default'] !="")) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $items_id=htmlspecialchars($_POST['id']);
            $items_name=htmlspecialchars($_POST['name']);
            $items_symbol=htmlspecialchars($_POST['symbol']);
            $items_goal=htmlspecialchars($_POST['goal']);
            $items_qnt_elements_default=htmlspecialchars($_POST['qnt_elements_default']);

            //INICIA O STATEMENT QUE ALTERA A TABELA ITEMS
            $stmt_change_items=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_items="UPDATE items SET name=?, symbol=?, goal=?, qnt_elements_default=?,WHERE id=$items_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_items,$query_change_items)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_items,'sssi',$items_name,$items_symbol,$items_goal,$items_qnt_elements_default);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_items)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_items);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }
    //SE A TABELA FOR microorganism_settings
    else if($table=="microorganism_settings") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['id']) && $_POST['id']!="") && (isset($_POST['max_usage']) && $_POST['max_usage'] !="") && (isset($_POST['break_duration']) && $_POST['break_duration'] !="") && (isset($_POST['perc_progress']) && $_POST['perc_progress'] !="") ) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $microorganism_settings_id=htmlspecialchars($_POST['id']);
            $microorganism_settings_max_usage=htmlspecialchars($_POST['max_usage']);
            $microorganism_settings_break_duration=htmlspecialchars($_POST['break_duration']);
            $microorganism_settings_perc_progress=htmlspecialchars($_POST['perc_progress']);


            //INICIA O STATEMENT QUE ALTERA A TABELA microorganism_settings
            $stmt_change_microorganism_settings=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_microorganism_settings="UPDATE microorganism_settings SET max_usage=?, break_duration=?, perc_progress=? WHERE id=$microorganism_settings_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_microorganism_settings,$query_change_microorganism_settings)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_microorganism_settings,'iii',$microorganism_settings_max_usage,$microorganism_settings_break_duration,$microorganism_settings_perc_progress);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_microorganism_settings)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_microorganism_settings);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }
    //SE A TABELA FOR planets_items_inventory
    else if($table=="planets_items_inventory") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['planets_user_id']) && $_POST['planets_user_id']!="") && (isset($_POST['item_id']) && $_POST['item_id'] !="") && (isset($_POST['qty']) && $_POST['qty'] !="") ) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $planets_items_inventory_planets_user_id=htmlspecialchars($_POST['planets_user_id']);
            $planets_items_inventory_item_id=htmlspecialchars($_POST['item_id']);
            $planets_items_inventory_qty=htmlspecialchars($_POST['qty']);

            //INICIA O STATEMENT QUE ALTERA A TABELA microorganism_settings
            $stmt_change_planets_items_inventory=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_planets_items_inventory="UPDATE planets_items_inventory SET planets_user_id=?, item_id=?, qty=? WHERE planets_user_id=$planets_items_inventory_planets_user_id AND item_id=$planets_items_inventory_item_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_planets_items_inventory,$query_change_planets_items_inventory)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_planets_items_inventory,'iii',$planets_items_inventory_planets_user_id,$planets_items_inventory_item_id,$planets_items_inventory_qty);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_planets_items_inventory)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_planets_items_inventory);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }
    //SE A TABELA FOR planets_land_items
    else if($table=="planets_land_items") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['item_id']) && $_POST['item_id']!="") && (isset($_POST['user_id']) && $_POST['user_id'] !="") && (isset($_POST['land_id']) && $_POST['land_id'] !="") && (isset($_POST['qt']) && $_POST['qt'] !="") ) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $planets_land_items_item_id=htmlspecialchars($_POST['item_id']);
            $planets_land_items_user_id=htmlspecialchars($_POST['user_id']);
            $planets_land_items_land_id=htmlspecialchars($_POST['land_id']);
            $planets_land_items_qt=htmlspecialchars($_POST['qt']);



            //INICIA O STATEMENT QUE ALTERA A TABELA microorganism_settings
            $stmt_change_planets_land_items=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_planets_land_items="UPDATE planets_land_items SET item_id=?, user_id=?, land_id=?,qt=? WHERE item_id=$planets_land_items_item_id AND user_id=$planets_land_items_item_id AND land_id=$planets_land_items_land_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_planets_land_items,$query_change_planets_land_items)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_planets_land_items,'iiii',$planets_land_items_item_id,$planets_land_items_user_id,$planets_land_items_land_id,$planets_land_items_qt);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_planets_land_items)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    echo $query_change_planets_land_items;

                    echo $planets_land_items_qt;
                    //SUCESSO
                    //header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_planets_land_items);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }
    //SE A TABELA FOR profiles
    else if($table=="profiles") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['id']) && $_POST['id']!="") && (isset($_POST['type']) && $_POST['type'] !="")) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $profiles_id=htmlspecialchars($_POST['id']);
            $profiles_type=htmlspecialchars($_POST['type']);


            //INICIA O STATEMENT QUE ALTERA A TABELA profiles
            $stmt_change_profiles=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_profiles="UPDATE profiles SET type=? WHERE id=$profiles_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_profiles,$query_change_profiles)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_profiles,'s',$profiles_type);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_profiles)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_profiles);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }
    //SE A TABELA FOR users
    else if($table=="users") {

        //PROCURA NO POST OS VALORES QUE PODEM ENTRAR NESSA TABELA
        if((isset($_POST['id']) && $_POST['id']!="") && (isset($_POST['username']) && $_POST['username'] !="") && (isset($_POST['avatar_id']) && $_POST['avatar_id'] !="") && (isset($_POST['profiles_id']) && $_POST['profiles_id'] !="") && (isset($_POST['date']) && $_POST['date'] !="")&& (isset($_POST['active']) && $_POST['active'] !="") && (isset($_POST['last_login']) && $_POST['last_login'] !="")) {

            //GUARDA NAS VARIÁVEIS ESSES VALORES
            $users_id=htmlspecialchars($_POST['id']);
            $users_username=htmlspecialchars($_POST['username']);
            $users_avatar_id=htmlspecialchars($_POST['avatar_id']);
            $users_profiles_id=htmlspecialchars($_POST['profiles_id']);
            $users_date=htmlspecialchars($_POST['date']);
            $users_active=htmlspecialchars($_POST['active']);
            $users_last_login=htmlspecialchars($_POST['last_login']);


            //INICIA O STATEMENT QUE ALTERA A TABELA profiles
            $stmt_change_users=mysqli_stmt_init($local_link);

            //DEFINE A QUERY DE ALTERAÇÃO PERMITADA || EVITA O ID
            $query_change_users="UPDATE profiles SET type=? WHERE id=$users_id";

            //PREPARA ESSE STATEMENT
            if(mysqli_stmt_prepare($stmt_change_users,$query_change_users)) {

                //DÁ BIND DOS PARÂMETROS
                mysqli_stmt_bind_param($stmt_change_users,'iii',$users_avatar_id,$users_profiles_id,$users_active);

                //EXECUTA O STATEMENT
                if(!mysqli_stmt_execute($stmt_change_users)) {

                    //VAI PARA A PÁGINA DE ERROS
                    header("Location:../errors.php?error=execute");

                }
                else {

                    //SUCESSO
                    header("Location:../tables.php?table=$table&action=edited");
                }
                //FECHA O STATEMENT
                mysqli_stmt_close($stmt_change_users);
            }
            //SE DER ERRO NA PREPARAÇÃO DO STATEMENT
            else {
                //VAI PARA A PÁGINA DE ERROS
                header("Location:../errors.php?error=prepare");
            }

        }

    }


} //SENÃO VAI PARA A PÁGINA DE ERRO
else {

    //VAI PARA A PÁGINA DE ERROS
    header("Location:../errors.php?error=noData");

}
mysqli_close($local_link);
